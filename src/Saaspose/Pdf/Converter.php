<?php

namespace Saaspose\Pdf;

use Saaspose\Common\Product;
use Saaspose\Common\SaasposeApp;
use Saaspose\Common\Utils;
use Saaspose\Common\AbstractConverter;
use Saaspose\Exception\SaasposeException as Exception;

/**
* converts pages or document into different formats
*/
class Converter extends AbstractConverter
{

	/**
	 * convert a document to given saveFormat
	 */
	public function convert($fileName, $saveFormat = 'Pdf')
	{
		return static::baseConvert('pdf', $fileName, $saveFormat);
	}

	/**
    * convert a particular page to image with specified size
	* @param string $pageNumber
	* @param string $imageFormat
	* @param string $width
	* @param string $height
	*/
    public function convertToImagebySize($fileName, $pageNumber, $imageFormat = 'png', $width, $height)
    {
       try {
			$strURI = sprintf("%s/pdf/%s/pages/%s?format=%s&width=%s&height=%s",
							Product::$baseProductUri,
							$fileName,
							$pageNumber,
							$imageFormat,
							$width,
							$height);

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::validateOutput($responseStream);

       		if ($v_output === "") {
				$newFileName = Utils::getFileName($fileName). "_" . $pageNumber . "." . $imageFormat;
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . $newFileName);
				return $newFileName;
			} else {
				throw new Exception($v_output);
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
	public function convertToImage($fileName, $pageNumber, $imageFormat = 'png')
	{
		try {
			$strURI = sprintf("%s/pdf/%s/pages/%s?format=%s",
							Product::$baseProductUri,
							$fileName,
							$pageNumber,
							$imageFormat);

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				$newFileName = Utils::getFileName($fileName). "_" . $pageNumber . "." . $imageFormat;
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . $newFileName);
				return $newFileName;
			} else {
				throw new Exception($v_output);
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
			if (empty($inputFile)) {
				throw new Exception("No file name specified");
			}

			if (empty($outputFormat)) {
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