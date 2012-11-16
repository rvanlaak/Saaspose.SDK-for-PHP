<?php

namespace Saaspose\Cells;

use Saaspose\Common\Utils;
use Saaspose\Common\Product;
use Saaspose\Exception\SaasposeException as Exception;

/**
* This class contains features to work with text
*/
class TextEditor
{
	public $fileName = "";

    public function __construct($fileName)
    {
        $this->fileName = $fileName;

        //check whether file is set or not
        if ($this->fileName == "") {
        	throw new Exception("No file name specified");
        }
    }


	/**
    * Finds a speicif text from Excel document or a worksheet
	*/
	public function findText()
	{
		$parameters = func_get_args();

		//set parameter values
		if(count($parameters)==1) {
			$text = $parameters[0];
		} else if(count($parameters)==2) {
			$worksheetName = $parameters[0];
			$text = $parameters[1];
		} else {
			throw new Exception("Invalid number of arguments");
		}

		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						((count($parameters)==2)? "/worksheets/" . $worksheetName : "") .
						"/findText?text=" . $text;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "", "");

			$json = json_decode($responseStream);

			return $json->TextItems->TextItemList;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets text items from the whole Excel file or a specific worksheet
	*/
	public function getTextItems()
	{
		$parameters = func_get_args();

		//set parameter values
		if (count($parameters) > 0) {
			$worksheetName = $parameters[0];
		}

		try {
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						((isset($parameters[0]))? "/worksheets/" . $worksheetName . "/textItems" : "/textItems");

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->TextItems->TextItemList;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Replaces all instances of old text with new text in the Excel document or a particular worksheet
	* @param string $oldText
	* @param string $newText
	*/
	public function replaceText()
	{
		$parameters = func_get_args();

		//set parameter values
		if(count($parameters)==2) {
			$oldText = $parameters[0];
			$newText = $parameters[1];
		} else if(count($parameters)==3) {
			$oldText = $parameters[1];
			$newText = $parameters[2];
			$worksheetName = $parameters[0];
		} else {
			throw new Exception("Invalid number of arguments");
		}

		try {
			//Build URI to replace text
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName .
						((count($parameters)==3)? "/worksheets/" . $worksheetName : "") .
						"/replaceText?oldValue=" . $oldText . "&newValue=" . $newText;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "", "");

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