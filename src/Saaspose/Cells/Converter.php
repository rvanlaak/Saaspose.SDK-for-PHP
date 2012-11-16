<?php

namespace Saaspose\Cells;

use Saaspose\Common\Product;
use Saaspose\Common\AbstractConverter;
use Saaspose\Common\SaasposeApp;
use Saaspose\Common\Utils;
use Saaspose\Exception\SaasposeException as Exception;

/**
* converts pages or document into different formats
*/
class Converter extends AbstractConverter
{
	/**
    * converts a document to given saveformat
	*/
	public static function convert($fileName, $saveFormat = 'xls')
	{
		return static::baseConvert('cells', $fileName, $saveFormat);
	}

	/**
    * converts a sheet to image
	* @param string $worksheetName
	* @param string $imageFormat
	*/
	public static function convertToImage($fileName, $imageFormat, $worksheetName)
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $fileName . "/worksheets/" .
			          $worksheetName . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($fileName).
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
    * converts a document to outputFormat
	* @param string $outputFormat
	*/
	public static function save($fileName, $outputFormat)
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $fileName . "?format=" . $outputFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($fileName). "." . $outputFormat;
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
	public static function worksheetToImage($fileName, $worksheetName, $imageFormat)
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $fileName . "/worksheets/" .
			          $worksheetName . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($fileName).
				"_" . $worksheetName . "." . $imageFormat;
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
	public static function pictureToImage($fileName, $worksheetName, $pictureIndex, $imageFormat)
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $fileName . "/worksheets/" .
			          $worksheetName . "/pictures/" . $pictureIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($fileName).
				"_" . $worksheetName . "." . $imageFormat;
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
	public static function oleObjectToImage($fileName, $worksheetName, $objectIndex, $imageFormat)
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $fileName . "/worksheets/" .
			          $worksheetName . "/oleobjects/" . $objectIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($fileName).
				"_" . $worksheetName . "." . $imageFormat;
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
	public static function chartToImage($fileName, $worksheetName, $chartIndex, $imageFormat)
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $fileName . "/worksheets/" .
			          $worksheetName . "/charts/" . $chartIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($fileName).
				"_" . $worksheetName . "." . $imageFormat;
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
	public function autoShapeToImage($fileName, $worksheetName, $shapeIndex, $imageFormat)
	{
		try {
			//Build URI
			$strURI = Product::$baseProductUri . "/cells/" . $fileName . "/worksheets/" .
			          $worksheetName . "/autoshapes/" . $shapeIndex . "?format=" . $imageFormat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			//Send request and receive response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			//Validate output
			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				//Save ouput file
				$outputPath = SaasposeApp::$outputLocation . Utils::getFileName($fileName).
				"_" . $worksheetName . "." . $imageFormat;
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