<?php

namespace Saaspose\Barcode;

use Saaspose\Common\Utils;

use Saaspose\Common\Product;
use Saaspose\Exception\SaasposeException as Exception;

/**
* reads barcodes from images
*/
class Reader
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
    * reads all or specific barcodes from images
	* @param string $symbology
	*/
	public function read($symbology)
	{
	    try {
            //build URI to read barcode
			$strURI = Product::$BaseProductUri . "/barcode/" . $this->fileName . "/recognize?" .
						(!isset($symbology) || trim($symbology)==='' ? "type=" : "type=" . $symbology);

			//sign URI
			$signedURI = Utils::sign($strURI);

			//get response stream
			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			//returns a list of extracted barcodes
			return $json->Barcodes;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}