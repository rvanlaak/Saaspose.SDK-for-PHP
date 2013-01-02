<?php

namespace Saaspose\Pdf;

use Saaspose\Common\SaasposeApp;

use Saaspose\Common\Product;
use Saaspose\Exception\SaasposeException as Exception;
use Saaspose\Common\Utils;

/**
* This class contains features to work with text
*/
class TextEditor
{
	public $fileName = "";

    public function __construct($fileName)
    {
        $this->fileName = $fileName;

        if ($this->fileName == "") {
        	throw new Exception("No file name specified");
        }
    }

	/**
    * Gets raw text from the whole PDF file or a specific page
	*/
	public function getText($pageNumber=null)
	{
		try {
			$strURI = sprintf('%s/pdf/%s%s/TextItems',
					Product::$baseProductUri,
					$this->fileName,
					(isset($pageNumber) && !empty($pageNumber)) ? "/pages/" . $pageNumber : ""
				);

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			$rawText = "";
			foreach ($json->TextItems->List as $textItem) {
				$rawText .= $textItem->Text;
			}
			return $rawText;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets text items from the whole PDF file or a specific page
	*/
	public function getTextItems($pageNumber=null)
	{
		try {
			$strURI = sprintf('%s/pdf/%s%s/TextItems',
						Product::$baseProductUri,
						$this->fileName,
						(isset($pageNumber) && !empty($pageNumber)) ? "/pages/" . $pageNumber : ""
				);

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);
			
			if ($json == 'Incorect file format') {
				throw new Exception('Maximum number of API requests used. See Saaspose API History log for more info.');
			}
			
			return $json->TextItems->List;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets count of the fragments from a particular page
	* $pageNumber
	*/
	public function getFragmentCount($pageNumber)
	{
		try {
			$strURI = Product::$baseProductUri . "/pdf/" . $this->fileName . "/pages/" . $pageNumber . "/fragments";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->TextItems->List);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	/**
    * Gets count of the segments in a fragment
	* @param number $pageNumber
	* @param number $fragmentNumber
	*/
	public function getSegmentCount($pageNumber="", $fragmentNumber="")
	{
		try {
			if ($pageNumber == "") {
				throw new Exception("page number not specified");
			}

			if ($fragmentNumber == "") {
				throw new Exception("fragment number not specified");
			}

			$strURI = Product::$baseProductUri . "/pdf/" . $this->fileName . "/pages/" . $pageNumber . "/fragments/" . $fragmentNumber;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->TextItems->List);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets TextFormat of a particular Fragment
	* $pageNumber
	* $fragmentNumber
	*/
	public function getTextFormat($pageNumber, $fragmentNumber)
	{
		try {
			$strURI = Product::$baseProductUri . "/pdf/" . $this->fileName . "/pages/" . $pageNumber .
						"/fragments/" . $fragmentNumber . "/textformat";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->TextFormat;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Replaces all instances of old text with new text in a PDF file or a particular page
	* @param string $oldText
	* @param string $newText
	*/
	public function replaceText($oldText, $newText, $isRegularExpression, $pageNumber=null)
	{
		try {
			//Build JSON to post
			$fieldsArray = array(
					'OldValue'	=> $oldText,
					'NewValue'	=> $newText,
					'Regex'		=> $isRegularExpression
			);
			$json = json_encode($fieldsArray);

			//Build URI to replace text
			$strURI = sprintf("%s/slides/%s%s/replaceText",
						Product::$baseProductUri,
						$this->fileName,
						(!is_null($pageNumber) ?  "/pages/" . $pageNumber : ""));

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "json", $json);

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				//Save doc on server
				$folder = new Folder();
				$outputStream = $folder->getFile($this->fileName);
				$outputPath = SaasposeApp::$outputLocation . $this->fileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}