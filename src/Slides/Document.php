<?php

namespace Saaspose\Slides;

use Saaspose\Common\Product;
use Saaspose\Common\Utils;
use Saaspose\Exception\SaasposeException as Exception;

/**
* Deals with PowerPoint document level aspects
*/
class Document
{
	public $fileName = "";

	public function __construct($fileName)
	{
		//set default values
		$this->fileName = $fileName;

		//check whether file is set or not
		if ($this->fileName == "") {
			throw new Exception("No file name specified");
		}
	}

	/**
    * Finds the slide count of the specified PowerPoint document
	*/
	public function getSlideCount()
	{
		try {
			//Build URI to get a list of slides
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->Slides->SlideList);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Replaces all instances of old text with new text in a presentation or a particular slide
	* @param string $oldText
	* @param string $newText
	*/
	public function replaceText()
	{
		// FIXME do not use func_get_args
		$parameters = func_get_args();

		//set parameter values
		if (count($parameters) == 2) {
			$oldText = $parameters[0];
			$newText = $parameters[1];
		} else if(count($parameters) == 3) {
			$oldText = $parameters[0];
			$newText = $parameters[1];
			$slideNumber = $parameters[2];
		} else {
			throw new Exception("Invalid number of arguments");
		}

		try {
			//Build URI to replace text
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . ((isset($parameters[2]))? "/slides/" . $slideNumber: "") .
						"/replaceText?oldValue=" . $oldText . "&newValue=" . $newText . "&ignoreCase=true";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save doc on server
				$folder = new Folder();
				$outputStream = $folder->getFile($this->fileName);
				$outputPath = SaasposeApp::$outputLocation . $this->fileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets all the text items in a slide or presentation
	*/
	public function getAllTextItems()
	{
		// FIXME do not use func_get_args
		$parameters = func_get_args();

		//set parameter values
		if (count($parameters) > 0) {
			$slideNumber = $parameters[0];
			$withEmpty = $parameters[1];
		}

		try {
			//Build URI to get all text items
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName .
						((isset($parameters[0]))? "/slides/" . $slideNumber . "/textItems?withEmpty=" . $withEmpty: "/textItems");

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->TextItems->Items;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Deletes all slides from a presentation
	*/
	public function deleteAllSlides()
	{
		try {
			//Build URI to replace text
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "DELETE", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save doc on server
				$folder = new Folder();
				$outputStream = $folder->getFile($this->fileName);
				$outputPath = SaasposeApp::$outputLocation . $this->fileName;
				Utils::saveFile($outputStream, $outputPath);
				return "";

			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Get Document's properties
	*/
	public function getDocumentProperties()
	{
		try {
			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/documentProperties";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			if($json->Code == 200) {
				return $json->DocumentProperties->List;
			} else {
				return false;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Get Resource Properties information like document source format, IsEncrypted, Issigned and document properties
	* @param string $propertyName
	*/
	public function getDocumentProperty($propertyName)
	{
		try {
			if ($propertyName == "") {
				throw new Exception("Property Name not specified");
			}

			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/presentation/documentProperties/" . $propertyName;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);


			if($json->Code == 200) {
				return $json->DocumentProperty;
			} else {
				return false;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Remove All Document's properties
	*/
	public function removeAllProperties()
	{
		try{
			//check whether files are set or not
			if ($this->fileName == "") {
				throw new Exception("Base file not specified");
			}

			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/documentProperties";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "DELETE");

			$json = json_decode($responseStream);

			if (is_object($json)) {
				if($json->Code == 200) {
					return true;
				} else {
					return false;
				}
			}
			return true;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Delete a document property
	* @param string $propertyName
	*/
	public function deleteDocumentProperty($propertyName)
	{
		try {
			if ($propertyName == "") {
				throw new Exception("Property Name not specified");
			}

			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/documentProperties/" . $propertyName;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "DELETE", "", "");

			$json = json_decode($responseStream);

			if($json->Code == 200) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Set document property
	* @param string $propertyName
	* @param string $propertyValue
	*/
	public function setProperty($propertyName="", $propertyValue="")
	{
		try {
			if ($propertyName == "") {
				throw new Exception("Property Name not specified");
			}
			if ($propertyValue == "") {
				throw new Exception("Property Value not specified");
			}

			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/documentProperties/" . $propertyName;

			$put_data_arr['Value'] = $propertyValue;

			$put_data = json_encode($put_data_arr);

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT", "json", $put_data);

			$json = json_decode($responseStream);

			if ($json->Code == 200) {
				return $json->DocumentProperty;
			} else {
				return false;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Add custom document properties
	* @param array $propertiesList
	*/
	public function addCustomProperty($propertiesList = "")
	{
		try {
			if ($propertiesList == "") {
				throw new Exception("Properties not specified");
			}

			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/documentProperties";

			$put_data = json_encode($propertiesList);

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT", "json", $put_data);

			$json = json_decode($responseStream);

			return $json;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    *saves the document into various formats
	* @param string $outputPath
	* @param string $saveFormat
	*/
    public function saveAs($outputPath="", $saveFormat="")
    {
       try {
			if ($outputPath == "") {
				throw new Exception("Output path not specified");
			}

			if ($saveFormat == "") {
				throw new Exception("Save format not specified");
			}

			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "?format=" . $saveFormat;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				Utils::saveFile($responseStream, $outputPath . Utils::getFileName($this->fileName) . "." . $saveFormat);
				return true;
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/**
    * Saves a particular slide into various formats
	* @param number $slideNumber
	* @param string $outputPath
	* @param string $saveFormat
	*/
    public function saveSlideAs($slideNumber="", $outputPath="", $saveFormat="")
    {
       try {
			if ($outputPath == "")
				throw new Exception("Output path not specified");

			if ($saveFormat == "")
				throw new Exception("Save format not specified");

			if ($slideNumber == "")
				throw new Exception("Slide number not specified");

			$strURI = Product::$baseProductUri . "/slides/" . $this->fileName . "/slides/$slideNumber?format=" . $saveFormat;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				Utils::saveFile($responseStream, $outputPath . Utils::getFileName($this->fileName) . "." . $saveFormat);
				return true;
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }
}