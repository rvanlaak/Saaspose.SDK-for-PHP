<?php

namespace Saaspose\Cells;

use Saaspose\Common\Utils;
use Saaspose\Common\Product;
use Saaspose\Exception\SaasposeException as Exception;

/*
* converts pages or document into different formats
*/
class Converter
{
	public $fileName = "";
	public $WorksheetName = "";
	public $saveformat = "";

	public function __construct()
	{
		$parameters = func_get_args();

		//set default values
		if (isset($parameters[0])) {
			$this->fileName = $parameters[0];
		}

		if (isset($parameters[1])) {
			$this->WorksheetName =  $parameters[1];
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
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "?format=" . $this->saveformat;

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

	/*
    * converts a sheet to image
	* @param string $worksheetName
	* @param string $imageFormat
	*/
	public function ConvertToImage($imageFormat, $worksheetName){
		try{
			//check whether file and sheet is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");

			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
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
			}
			else
				return $v_output;
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/*
    * converts a document to outputFormat
	* @param string $outputFormat
	*/
	public function Save($outputFormat){
		try{
			//check whether file is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");

			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "?format=" . $outputFormat;

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
			}
			else
				return $v_output;
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/*
    * converts a sheet to image
	* @param string $imageFormat
	*/
	public function WorksheetToImage($imageFormat){
		try{
			//check whether file and sheet is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");
			if ($this->WorksheetName == "")
				throw new Exception("No worksheet specified");

			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->WorksheetName . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->WorksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			}
			else
				return $v_output;
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/*
    * saves a specific picture from a specific sheet as image
	* @param $pictureIndex
	* @param $imageFormat
	*/
	public function PictureToImage($pictureIndex, $imageFormat){
		try{
			//check whether file and sheet is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");
			if ($this->WorksheetName == "")
				throw new Exception("No worksheet specified");

			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->WorksheetName . "/pictures/" . $pictureIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->WorksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			}
			else
				return $v_output;
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/*
    * saves a specific OleObject from a specific sheet as image
	* @param $objectIndex
	* @param $imageFormat
	*/
	public function OleObjectToImage($objectIndex, $imageFormat){
		try{
			//check whether file and sheet is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");
			if ($this->WorksheetName == "")
				throw new Exception("No worksheet specified");

			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->WorksheetName . "/oleobjects/" . $objectIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->WorksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			}
			else
				return $v_output;
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/*
    * saves a specific chart from a specific sheet as image
	* @param $chartIndex
	* @param $imageFormat
	*/
	public function ChartToImage($chartIndex, $imageFormat){
		try{
			//check whether file and sheet is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");
			if ($this->WorksheetName == "")
				throw new Exception("No worksheet specified");

			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->WorksheetName . "/charts/" . $chartIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->WorksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			}
			else
				return $v_output;
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}

	/*
    * saves a specific auto-shape from a specific sheet as image
	* @param $shapeIndex
	* @param $imageFormat
	*/
	public function AutoShapeToImage($shapeIndex, $imageFormat){
		try{
			//check whether file and sheet is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");
			if ($this->WorksheetName == "")
				throw new Exception("No worksheet specified");

			//Build URI
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
			          $this->WorksheetName . "/autoshapes/" . $shapeIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($this->fileName).
				"_" . $this->WorksheetName . "." . $imageFormat;
				Utils::saveFile($responseStream, $outputPath);
				return $outputPath;
			}
			else
				return $v_output;
		} catch (Exception $e){
			throw new Exception($e->getMessage());
		}
	}
}