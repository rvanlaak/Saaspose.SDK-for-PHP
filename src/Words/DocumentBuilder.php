<?php

namespace Saaspose\Words;

use Saaspose\Storage\Folder;

use Saaspose\Exception\SaasposeException as Exception;
use Saaspose\Common\Product;
use Saaspose\Common\Utils;

/**
* Deals with Word document builder aspects
*/
class DocumentBuilder
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
    * Inserts water mark text into the document.
	* @param string $fileName
	* @param string $text
	* @param string $rotationAngle
	*/
	public function insertWatermarkText($text, $rotationAngle)
	{
       try {
			//Build JSON to post
			$fieldsArray = array('Text'=>$text, 'RotationAngle'=>$rotationAngle);
			$json = json_encode($fieldsArray);

			//build URI to insert watermark text
			$strURI = Product::$BaseProductUri . "/words/" . $fileName . "/insertWatermarkText";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "json", $json);

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save docs on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($fileName);
				$outputPath = SaasposeApp::$outputLocation . $fileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Inserts water mark image into the document.
	* @param string $fileName
	* @param string $imageFile
	* @param string $rotationAngle
	*/
	public function insertWatermarkImage($imageFile, $rotationAngle)
	{
       try {
			//build URI to insert watermark image
			$strURI = Product::$BaseProductUri . "/words/" . $fileName .
					"/insertWatermarkImage?imageFile=" . $imageFile . "&rotationAngle=" . $rotationAngle;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "json", '');

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save doc on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($fileName);
				$outputPath = SaasposeApp::$outputLocation . $fileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/**
    * Replace a text with the new value in the document
	* @param string $fileName
	* @param string $oldValue
	* @param string $newValue
	* @param string $isMatchCase
	* @param string $isMatchWholeWord
	*/
	public function replaceText($fileName, $oldValue, $newValue, $isMatchCase, $isMatchWholeWord)
	{
       try {
			//Build JSON to post
			$fieldsArray = array('OldValue'=>$oldValue, 'NewValue'=>$newValue,
									'IsMatchCase'=>$isMatchCase, 'IsMatchWholeWord'=>$isMatchWholeWord);
			$json = json_encode($fieldsArray);

			//build URI to replace text
			$strURI = Product::$BaseProductUri . "/words/" . $fileName . "/replaceText";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "json", $json);

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save docs on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($fileName);
				$outputPath = SaasposeApp::$outputLocation . $fileName;
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

