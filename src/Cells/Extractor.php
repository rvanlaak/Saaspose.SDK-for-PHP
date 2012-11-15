<?php

namespace Saaspose\Cells;

use Saaspose\Common\Utils;
use Saaspose\Common\Product;
use Saaspose\Exception\SaasposeException as Exception;

/**
* converts pages or document into different formats
*/
class Extractor
{
	public $fileName = "";

	public function __construct($fileName)
	{
		//set default values
		$this->fileName = $fileName;

		//check whether file and sheet is set or not
		if ($this->fileName == "") {
			throw new Exception("No file name specified");
		}
	}

	/**
    * saves a specific picture from a specific sheet as image
	* @param $worksheetName
	* @param $pictureIndex
	* @param $imageFormat
	*/
	public function getPicture($worksheetName, $pictureIndex, $imageFormat)
	{
		try {
			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $worksheetName . "/pictures/" . $pictureIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $worksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
    * saves a specific OleObject from a specific sheet as image
	* @param $worksheetName
	* @param $objectIndex
	* @param $imageFormat
	*/
	public function getOleObject($worksheetName, $objectIndex, $imageFormat)
	{
		try {
			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $worksheetName . "/oleobjects/" . $objectIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $worksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return "";

			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
    * saves a specific chart from a specific sheet as image
	* @param $worksheetName
	* @param $chartIndex
	* @param $imageFormat
	*/
	public function getChart($worksheetName, $chartIndex, $imageFormat)
	{
		try {
			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $worksheetName . "/charts/" . $chartIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $worksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return "";

			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
    * saves a specific auto-shape from a specific sheet as image
	* @param $worksheetName
	* @param $shapeIndex
	* @param $imageFormat
	*/
	public function getAutoShape($worksheetName, $shapeIndex, $imageFormat)
	{
		try {
			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $worksheetName . "/autoshapes/" . $shapeIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $worksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return "";

			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}
}