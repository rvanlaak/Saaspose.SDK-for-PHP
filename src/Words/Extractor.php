<?php

namespace Saaspose\Words;

use Saaspose\Exception\SaasposeException as Exception;
use Saaspose\Common\Product;
use Saaspose\Common\Utils;

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
    * Gets Text items list from document
	*/
	public function getText()
	{
		try {
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/textItems";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->TextItems->List;
			//echo $json->TextItems->List[0]->Text;
			//return count($json->Images->List);

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Get the OLE drawing object from document
	* @param int $index
	* @param string $OLEFormat
	*/
    public function getOleData($index, $OLEFormat)
    {
      	try {
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/drawingObjects/" . $index . "/oleData";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "_" . $index . "." . $OLEFormat);
				return "";

			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

 	/**
    * Get the Image drawing object from document
	* @param int $index
	* @param string $renderformat
	*/
    public function getImageData($index, $renderformat)
    {
       try {
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/drawingObjects/" . $index . "/ImageData";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "_" . $index . "." . $renderformat);
				return "";

			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/**
    * Convert drawing object to image
	* @param int $index
	* @param string $renderformat
	*/
    public function convertDrawingObject($index, $renderformat)
    {
       try {
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/drawingObjects/" . $index . "?format=" . $renderformat;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . Utils::getFileName($this->fileName). "_" . $index . "." . $renderformat);
				return "";
			} else {
				return $v_output;
			}
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
    }

	/**
    * Get the List of drawing object from document
	*/
    public function getDrawingObjectList()
    {
       try {
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/drawingObjects";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			if ($json->Code == 200) {
				return $json->DrawingObjects->List;
			} else {
				return false;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }

	/**
    * Get the drawing object from document
	* @param string $objectURI
	* @param string $outputPath
	*/
    public function getDrawingObject($objectURI="", $outputPath="")
    {
       try {
			if ($outputPath == "") {
				throw new Exception("Output path not specified");
			}

			if ($objectURI == "") {
				throw new Exception("Object URI not specified");
			}

			$url_arr = explode("/",$objectURI);
			$objectIndex = end($url_arr);

			$strURI = $objectURI;

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			if ($json->Code == 200) {

				if($json->DrawingObject->ImageDataLink != "") {
					$strURI = $strURI . "/imageData";
					$outputPath = $outputPath . "\\DrawingObject_" . $objectIndex . ".jpeg";
				} else if($json->DrawingObject->OLEDataLink != "") {
					$strURI = $strURI . "/oleData";
					$outputPath = $outputPath . "\\DrawingObject_" . $objectIndex . ".xlsx";
				} else {
					$strURI = $strURI . "?format=jpeg";
					$outputPath = $outputPath . "\\DrawingObject_" . $objectIndex . ".jpeg";
				}

				$signedURI = Utils::sign($strURI);

				$responseStream = Utils::processCommand($signedURI, "GET", "", "");

				$v_output = Utils::ValidateOutput($responseStream);

				if ($v_output === "") {
					Utils::saveFile($responseStream, $outputPath);
					return true;
				} else {
					return $v_output;
				}

			} else {
				return false;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }


	/**
    * Get the List of drawing object from document
	* @param string outputPath
	*/
    public function getDrawingObjects($outputPath="")
    {
       try {
			if ($outputPath == "") {
				throw new Exception("Output path not specified");
			}

			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/drawingObjects";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			if ($json->Code == 200) {
				foreach($json->DrawingObjects->List as $object) {
					$this->getDrawingObject($object->link->Href,$outputPath);
				}
			} else {
				return false;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
    }
}
