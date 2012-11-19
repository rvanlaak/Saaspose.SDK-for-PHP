<?php

namespace Saaspose\Slides;

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
	 * convert a document to given saveFormat
	 */
	public function convert($fileName, $saveFormat = 'PPT')
	{
		return static::baseConvert('slides', $fileName, $saveFormat);
	}

	/**
    * Saves a particular slide into various formats with specified width and height
	*/
	public function convertToImage($fileName, $saveFormat = 'PPT', $slideNumber, $imageFormat)
	{
		try {
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/" . $slideNumber . "?format=" . $imageFormat;

			$signedURI = Utils::sign($strURI);
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");
			Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "." . $this->saveformat);

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
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/" . $slideNumber . "?format=" . $imageFormat . "&width=" . $width . "&height=" . $height;

			$signedURI = Utils::sign($strURI);
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");
			Utils::saveFile($responseStream, SaasposeApp::$outputLocation . "output." . $imageFormat);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

}