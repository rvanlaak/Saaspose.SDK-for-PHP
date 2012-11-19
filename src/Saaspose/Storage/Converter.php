<?php

namespace Saaspose\Storage;

use Saaspose\Exception\SaasposeException as Exception;
use Saaspose\Common\Product;
use Saaspose\Common\SaasposeApp;
use Saaspose\Common\Utils;

/**
* converts pages or document into different formats
*/
class Converter
{

	/**
    * Saves a particular slide into various formats with specified width and height
	*/
	public function convertToImage($fileName, $saveFormat = 'PPT', $slideNumber, $imageFormat)
	{
		try {
			//check whether file is set or not
			if ($fileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$baseProductUri . "/slides/" . $fileName . "/slides/" . $slideNumber . "?format=" . $imageFormat;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($fileName). "." . $saveformat);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Saves a particular slide into various formats with specified width and height
	*/
	public function convertToImagebySize($fileName, $saveFormat = 'PPT', $slideNumber, $imageFormat, $width, $height)
	{
		try {
			//check whether file is set or not
			if ($fileName == "") {
				throw new Exception("No file name specified");
			}

			$strURI = Product::$baseProductUri . "/slides/" . $fileName . "/slides/" . $slideNumber . "?format=" . $imageFormat . "&width=" . $width . "&height=" . $height;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			Utils::saveFile($responseStream, SaasposeApp::$outputLocation . "output." . $imageFormat);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * convert a document to the given saveFormat
	*/
	public function convert($fileName, $saveFormat = 'PPT')
	{
		try {
			//check whether file is set or not
			if ($fileName == "") {
				throw new Exception("No file name specified");
			}

			$strURI = Product::$baseProductUri . "/slides/" . $fileName . "?format=" . $saveformat;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($fileName). "." . $saveformat);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}