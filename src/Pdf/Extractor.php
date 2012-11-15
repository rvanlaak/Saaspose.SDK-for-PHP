<?php

namespace Saaspose\Pdf;

/*
* Extract various types of information from the document
*/
use Saaspose\Common\Utils;
use Saaspose\Exception\Exception as Exception;

class Extractor
{
	public $fileName = "";

    public function __constructor($fileName)
    {
        $this->fileName = $fileName;
    }


	/**
    * Gets number of images in a specified page
	* @param $pageNumber
	*/
	public function getImageCount($pageNumber)
	{
		try {
			//check whether file is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/pdf/" . $this->fileName . "/pages/" . $pageNumber . "/images";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->Images->List);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Get the particular image from the specified page with the default image size
	* @param int $pageNumber
	* @param int $imageIndex
	* @param string $imageFormat
	*/
    public function getImageDefaultSize($pageNumber, $imageIndex, $imageFormat)
    {
       try {
			//check whether file is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/pdf/" . $this->fileName . "/pages/" . $pageNumber . "/images/" . $imageIndex . "?format=" . $imageFormat;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "_" . $imageIndex . "." . $imageFormat);
				return "";
			}
			else
				return $v_output;
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
    }

	/**
    * Get the particular image from the specified page with the default image size
	* @param int $pageNumber
	* @param int $imageIndex
	* @param string $imageFormat
	* @param int $imageWidth
	* @param int $imageHeight
	*/
    public function getImageCustomSize($pageNumber, $imageIndex, $imageFormat, $imageWidth, $imageHeight)
    {
       try {
			//check whether file is set or not
			if ($this->fileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/pdf/" . $this->fileName . "/pages/" . $pageNumber . "/images/" . $imageIndex . "?format=" . $imageFormat . "&width=" . $imageWidth . "&height=" . $imageHeight;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "_" . $imageIndex . "." . $imageFormat);
				return "";
			}
			else
				return $v_output;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }
}