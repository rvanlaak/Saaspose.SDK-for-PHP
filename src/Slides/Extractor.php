<?php

namespace Saaspose\Slides;

use Saaspose\Common\Product;
use Saaspose\Common\Utils;
use Saaspose\Exception\SaasposeException as Exception;

/**
* Extract various types of information from the document
*/
class Extractor
{
	public $fileName = "";

    public function __construct($fileName)
    {
        $this->fileName = $fileName;

        //check whether file is set or not
        if ($this->fileName == "") {
        	throw new Exception("No file name specified");
        }
    }


	/**
    * Gets total number of images in a presentation
	*/
	public function getImageCount()
	{
		try {
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/images";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->Images->List);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}


	/**
    * Gets number of images in the specified slide
	* @param $slidenumber
	*/
	public function getSlideImageCount($slidenumber)
	{
		try {
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/" . $slidenumber . "/images";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->Images->List);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets all shapes from the specified slide
	* @param $slidenumber
	*/
	public function getShapes($slidenumber)
	{
		try {
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/" . $slidenumber . "/shapes";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			$shapes = array();

			foreach ($json->ShapeList->Links as $shape) {

				$signedURI = Utils::sign($shape->Uri->Href);
				$responseStream = Utils::processCommand($signedURI, "GET", "", "");
				$json = json_decode($responseStream);
				$shapes[] = $json;
			}
			return $shapes;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Get color scheme from the specified slide
	* $slideNumber
	*/
	public function getColorScheme($slideNumber)
	{
		try {
			//Build URI to get color scheme
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/" . $slideNumber . "/theme/colorScheme";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->ColorScheme;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Get font scheme from the specified slide
	* $slideNumber
	*/
	public function getFontScheme($slideNumber)
	{
		try {
			//Build URI to get font scheme
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/" . $slideNumber . "/theme/fontScheme";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->FontScheme;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Get format scheme from the specified slide
	* $slideNumber
	*/
	public function getFormatScheme($slideNumber)
	{
		try {
			//Build URI to get format scheme
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/" . $slideNumber . "/theme/formatScheme";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->FormatScheme;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets placeholder count from a particular slide
	* $slideNumber
	*/
	public function getPlaceholderCount($slideNumber)
	{
		try {
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/" . $slideNumber . "/placeholders";

			//Build URI to get placeholders
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->Placeholders->PlaceholderLinks);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets a placeholder from a particular slide
	* $slideNumber
	* $placeholderIndex
	*/
	public function getPlaceholder($slideNumber, $placeholderIndex)
	{
		try {
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/" . $slideNumber . "/placeholders/" . $placeholderIndex;

			//Build URI to get placeholders
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->Placeholder;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}