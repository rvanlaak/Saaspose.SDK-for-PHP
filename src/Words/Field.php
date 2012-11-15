<?php

namespace Saaspose\Words;

use Saaspose\Storage\Folder;
use Saaspose\Common\Product;
use Saaspose\Exception\SaasposeException as Exception;

/**
* Deals with Word document builder aspects
*/
class Field
{
	public $fileName;

	public function __construct($fileName)
	{
		$this->fileName = $fileName;

		//check whether files are set or not
		if ($this->fileName == "") {
			throw new Exception("File not specified");
		}
	}

	/**
    * Inserts page number filed into the document.
	* @param string $fileName
	* @param string $alignment
	* @param string $format
	* @param string $isTop
	* @param string $setPageNumberOnFirstPage
	*/
	public function insertPageNumber($alignment, $format, $isTop, $setPageNumberOnFirstPage)
	{
       try {
			//Build JSON to post
			$fieldsArray = array('Format'=>$format, 'Alignment'=>$alignment,
									'IsTop'=>$isTop, 'SetPageNumberOnFirstPage'=>$setPageNumberOnFirstPage);

			$json = json_encode($fieldsArray);

			//build URI to insert page number
			$strURI = Product::$baseProductUri . "/words/" . $fileName . "/insertPageNumbers";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "json", $json);

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save docs on server
				$folder = new Folder();
				$outputStream = $folder->getFile($fileName);
				$outputPath = SaasposeApp::$outputLocation . $fileName;
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
    * Gets all merge filed names from document.
	* @param string $fileName
	*/
	public function getMailMergeFieldNames()
	{
		try {
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName . "/mailMergeFieldNames";

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->FieldNames->List;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}

