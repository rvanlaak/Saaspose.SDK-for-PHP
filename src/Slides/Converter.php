<?php

namespace Saaspose\Slides;

use Saaspose\Common\Product;
use Saaspose\Common\Utils;
use Saaspose\Exception\SaasposeException as Exception;

/**
* converts pages or document into different formats
*/
class Converter
{
	public $fileName = "";
	public $saveformat = "";

	public function __construct($fileName)
	{
		//set default values
		$this->fileName = $fileName;

		$this->saveformat =  "PPT";

		//check whether file is set or not
		if ($this->fileName == "") {
			throw new Exception("No file name specified");
		}
	}

	/**
    * Saves a particular slide into various formats with specified width and height
	* @param string $slideNumber
	* @param string $imageFormat
	*/
	public function convertToImage($slideNumber, $imageFormat)
	{
		try {
			$strURI = Product::$BaseProductUri . "/slides/" . $this->fileName . "/slides/" . $slideNumber . "?format=" . $imageFormat;

			$signedURI = Utils::sign($strURI);
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");
			Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "." . $this->saveformat);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Saves a particular slide into various formats with specified width and height
	* @param string $slideNumber
	* @param string $imageFormat
	* @param string $width
	* @param string $height
	*/
	public function convertToImagebySize($slideNumber, $imageFormat, $width, $height)
	{
		try {
			$strURI = Product::$BaseProductUri . "/slides/" . $this->fileName . "/slides/" . $slideNumber . "?format=" . $imageFormat . "&width=" . $width . "&height=" . $height;

			$signedURI = Utils::sign($strURI);
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");
			Utils::saveFile($responseStream, SaasposeApp::$outputLocation . "output." . $imageFormat);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * convert a document to SaveFormat
	*/
	public function convert()
	{
		try {
			$strURI = Product::$BaseProductUri . "/slides/" . $this->fileName . "?format=" . $this->saveformat;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "." . $this->saveformat);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}