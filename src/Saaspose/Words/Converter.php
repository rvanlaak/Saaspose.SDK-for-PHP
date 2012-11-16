<?php

namespace Saaspose\Words;

use Saaspose\Exception\SaasposeException as Exception;
use Saaspose\Common\Product;
use Saaspose\Common\SaasposeApp;
use Saaspose\Common\Utils;

/**
 * converts pages or document into different formats
 */
class Converter
{

	public $fileName = "";
	public $saveformat = "";

	public function __construct($fileName)
	{
		//set default values
		$this->fileName = $fileName;

		$this->saveformat = "Doc";

		//check whether file is set or not
		if ($this->fileName == "") {
			throw new Exception("No file name specified");
		}
	}

	/**
	 * convert a document to SaveFormat
	 */
	public function convert()
	{
		try {
			//build URI
			$strURI = Product::$baseProductUri . "/words/" . $this->fileName
					. "?format=" . $this->saveformat;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				Utils::saveFile($responseStream,
						SaasposeApp::$outputLocation
								. Utils::getFileName($this->fileName) . "."
								. $this->saveformat);
				return "";
			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
