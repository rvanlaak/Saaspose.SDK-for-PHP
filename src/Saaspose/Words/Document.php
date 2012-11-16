<?php

namespace Saaspose\Words;

use Saaspose\Common\SaasposeApp;
use Saaspose\Exception\SaasposeException as Exception;
use Saaspose\Common\Product;
use Saaspose\Common\Utils;

/**
* Deals with Word document level aspects
*/
class Document
{

    public $fileName = "";

	public function __construct($fileName)
    {
    	$this->fileName = $fileName;

    	//check whether files are set or not
    	if ($this->fileName == "") {
    		throw new Exception("Base file not specified");
    	}
    }

	/**
    * Appends a list of documents to this one.
	* @param string $appendDocs (List of documents to append)
	* @param string $importFormatModes
	* @param string $sourceFolder (name of the folder where documents are present)
	*/
	public function appendDocument($appendDocs, $importFormatModes, $sourceFolder)
	{
       try {
			//check whether required information is complete
			if (count($appendDocs) != count($importFormatModes))
				throw new Exception("Please specify complete documents and import format modes");

			//Build JSON to post
			$json = '{ "DocumentEntries": [';

			for ($i = 0; $i < count($appendDocs); $i++) {
				$json .= '{ "Href": "' . $sourceFolder . $appendDocs[$i] .
					'", "ImportFormatMode": "' . $importFormatModes[$i] . '" }' .
					(($i < (count($appendDocs) - 1)) ? ',' : '');
			}

            $json .= '  ] }';

			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/appendDocument";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "json", $json);

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				//Save merged docs on server
				$folder = new Folder();
				$outputStream = $folder->getFile($sourceFolder . (($sourceFolder == '') ? '' : '/') . $this->fileName);
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
    * Get Resource Properties information like document source format, IsEncrypted, IsSigned and document properties
	*/
	public function getDocumentInfo()
	{
       try {
			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			if($json->Code == 200)
				return $json->Document;
			else
				return false;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Get Resource Properties information like document source format, IsEncrypted, IsSigned and document properties
	* @param string $propertyName
	*/
	public function getProperty($propertyName)
	{
       try {
			if ($propertyName == "")
				throw new Exception("Property Name not specified");

			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/documentProperties/" . $propertyName;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);


			if($json->Code == 200)
				return $json->DocumentProperty;
			else
				return false;

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
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/documentProperties/" . $propertyName;

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
    * Delete a document property
	* @param string $propertyName
	*/
	public function deleteProperty($propertyName)
	{
       try {
			if ($propertyName == "") {
				throw new Exception("Property Name not specified");
			}

			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/documentProperties/" . $propertyName;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "DELETE", "", "");

			$json = json_decode($responseStream);

			if ($json->Code == 200) {
				return true;
			} else {
				return false;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Get Document's properties
	*/
	public function getProperties()
	{
       try {
			//build URI to merge Docs
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/documentProperties";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			if ($json->Code == 200) {
				return $json->DocumentProperties->List;
			} else {
				return false;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Convert Document to different file format without using storage
	* $param string $inputPath
	* @param string $outputPath
	* @param string $outputFormat
	*/
	public function convertLocalFile($inputPath="", $outputPath="", $outputFormat="")
	{
		try {
			//check whether file is set or not
			if ($inputPath == "") {
				throw new Exception("No file name specified");
			}

			if ($outputFormat == "") {
				throw new Exception("output format not specified");
			}

			$strURI = Product::$baseProductUri . "/words/convert?format=" . $outputFormat;

			if (!file_exists($inputPath)) {
				throw new Exception("input file doesn't exist.");
			}

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::uploadFileBinary($signedURI, $inputPath , "xml");

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				if ($outputFormat == "html") {
					$save_format = "zip";
				} else {
					$save_format = $outputFormat;
				}

				if ($outputPath == "") {
					$outputPath = Utils::getFileName($inputPath). "." . $save_format;
				}

				Utils::saveFile($responseStream, SaasposeApp::$outputLocation . $outputPath);
				return true;

			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

}

