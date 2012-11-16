<?php

namespace Saaspose\Pdf;

use Saaspose\Common\Utils;
use Saaspose\Common\Product;
use Saaspose\Exception\Exception as Exception;

/**
* converts pages or document into different formats
*/
class Converter
{
	public $fileName = "";
	public $saveformat = "";

    public function __construct($fileName)
    {
        $this->fileName = $fileName;

		$this->saveformat =  "Pdf";

		//check whether file is set or not
		if ($this->fileName == "") {
			throw new Exception("No file name specified");
		}
    }

	/**
    * convert a particular page to image with specified size
	* @param string $pageNumber
	* @param string $imageFormat
	* @param string $width
	* @param string $height
	*/
    public function convertToImagebySize($pageNumber, $imageFormat, $width, $height)
    {
       try {
			$strURI = Product::$baseProductUri . "/pdf/" . $this->fileName . "/pages/" . $pageNumber . "?format=" . $imageFormat . "&width=" . $width . "&height=" . $height;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "_" . $pageNumber . "." . $imageFormat);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/**
    * convert a particular page to image with default size
	* @param string $pageNumber
	* @param string $imageFormat
	*/
	public function convertToImage($pageNumber, $imageFormat)
	{
		try {
			$strURI = Product::$baseProductUri . "/pdf/" . $this->fileName . "/pages/" . $pageNumber . "?format=" . $imageFormat;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "_" . $pageNumber . "." . $imageFormat);
				return "";
			} else {
				return $v_output;
			}

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
			$strURI = Product::$baseProductUri . "/pdf/" . $this->fileName . "?format=" . $this->saveformat;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				if($this->saveformat == "html") {
					$save_format = "zip";
				} else {
					$save_format = $this->saveformat;
				}

				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "." . $save_format);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Convert PDF to different file format without using storage
	* $param string $inputFile
	* @param string $outputfileName
	* @param string $outputFormat
	*/
	public function convertLocalFile($inputFile="", $outputfileName="", $outputFormat="")
	{
		try {
			//check whether file is set or not
			if ($inputFile == "") {
				throw new Exception("No file name specified");
			}

			if ($outputFormat == "") {
				throw new Exception("output format not specified");
			}

			$strURI = Product::$baseProductUri . "/pdf/convert?format=" . $outputFormat;

			if (!file_exists($inputFile)) {
				throw new Exception("input file doesn't exist.");
			}

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::uploadFileBinary($signedURI, $inputFile , "xml");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				if($outputFormat == "html") {
					$save_format = "zip";
				} else {
					$save_format = $outputFormat;
				}

				if($outputfileName == "") {
					$outputfileName = Utils::getFileName($inputFile). "." . $save_format;
				}

				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . $outputfileName);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

}