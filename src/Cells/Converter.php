<?php

namespace Saaspose\Cells;

use Saaspose\Common\Utils;
use Saaspose\Common\Product;
use Saaspose\Exception\SaasposeException as Exception;

/**
* converts pages or document into different formats
*/
class Converter
{
	public $fileName = "";
	public $worksheetName = "";
	public $saveformat = "";

	public function __construct()
	{
		$parameters = func_get_args();

		//set default values
		if (isset($parameters[0]) && $parameters[0] != '') {
			$this->fileName = $parameters[0];
		} else {
			throw new Exception("No file name specified");
		}

		if (isset($parameters[1])) {
			$this->worksheetName =  $parameters[1];
		}
		$this->saveformat =  "xls";
	}

	/**
    * converts a document to saveformat
	*/
	public function convert()
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName . "?format=" . $this->saveformat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "." . $this->saveformat;
				Utils::saveFile($responseStream, $outputPath);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * converts a sheet to image
	* @param string $worksheetName
	* @param string $imageFormat
	*/
	public function convertToImage($imageFormat, $worksheetName)
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $worksheetName . "?format=" . $imageFormat;

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

	/*
    * converts a document to outputFormat
	* @param string $outputFormat
	*/
	public function save($outputFormat)
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName . "?format=" . $outputFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "." . $outputFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
    * converts a sheet to image
	* @param string $imageFormat
	*/
	public function worksheetToImage($imageFormat)
	{
		try {
			if ($this->worksheetName == "") {
				throw new Exception("No worksheet specified");
			}

			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->worksheetName . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->worksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
    * saves a specific picture from a specific sheet as image
	* @param $pictureIndex
	* @param $imageFormat
	*/
	public function pictureToImage($pictureIndex, $imageFormat)
	{
		try {
			if ($this->worksheetName == "") {
				throw new Exception("No worksheet specified");
			}

			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->worksheetName . "/pictures/" . $pictureIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->worksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
    * saves a specific OleObject from a specific sheet as image
	* @param $objectIndex
	* @param $imageFormat
	*/
	public function oleObjectToImage($objectIndex, $imageFormat)
	{
		try {
			if ($this->worksheetName == "") {
				throw new Exception("No worksheet specified");
			}

			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->worksheetName . "/oleobjects/" . $objectIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->worksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
    * saves a specific chart from a specific sheet as image
	* @param $chartIndex
	* @param $imageFormat
	*/
	public function chartToImage($chartIndex, $imageFormat)
	{
		try {
			if ($this->worksheetName == "") {
				throw new Exception("No worksheet specified");
			}

			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->worksheetName . "/charts/" . $chartIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->worksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/**
    * saves a specific auto-shape from a specific sheet as image
	* @param $shapeIndex
	* @param $imageFormat
	*/
	public function autoShapeToImage($shapeIndex, $imageFormat)
	{
		try {
			if ($this->worksheetName == "") {
				throw new Exception("No worksheet specified");
			}

			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->worksheetName . "/autoshapes/" . $shapeIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->worksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			} else {
				return $v_output;
			}

		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}
}