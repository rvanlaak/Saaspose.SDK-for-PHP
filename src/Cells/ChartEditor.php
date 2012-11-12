<?php

namespace Saaspose\Cells;

use Saaspose\Common\Utils;
use Saaspose\Common\Product;
use Saaspose\Exception\SaasposeException as Exception;
use Saaspose\Storage\Folder;

/**
* This class contains features to work with charts
*/
class ChartEditor
{
	public $fileName = "";
	public $worksheetName = "";

    public function __construct($fileName, $worksheetName)
    {
        $this->fileName 		= $fileName;
		$this->worksheetName 	= $worksheetName;

		//check whether file is set or not
		if ($this->fileName == "") {
			throw new Exception("No file name specified");
		}

		//check whether workshett name is set or not
		if ($this->worksheetName == "") {
			throw new Exception("Worksheet name not specified");
		}
    }

	/**
    * Adds a new chart
	* $chartType
	* $upperLeftRow
	* $upperLeftColumn
	* $lowerRightRow
	* $lowerRightColumn
	*/
	public function addChart($chartType, $upperLeftRow, $upperLeftColumn, $lowerRightRow, $lowerRightColumn)
	{
		try {
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/charts?chartType=" .
						$chartType . "&upperLeftRow=" . $upperLeftRow . "&upperLeftColumn=" .
						$upperLeftColumn . "&lowerRightRow=" . $lowerRightRow .
						"&lowerRightColumn=" . $lowerRightColumn;

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save doc on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($this->fileName);
				$outputPath = SaasposeApp::$OutPutLocation . $this->fileName;
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
    * Deletes a chart
	* $chartIndex
	*/
	public function deleteChart($chartIndex)
	{
		try {
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/charts/" . $chartIndex;

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "DELETE", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save doc on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($this->fileName);
				$outputPath = SaasposeApp::$OutPutLocation . $this->fileName;
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
    * Gets ChartArea of a chart
	* $chartIndex
	*/
	public function getChartArea($chartIndex)
	{
		try {
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName .
						"/worksheets/" . $this->worksheetName . "/charts/" . $chartIndex . "/chartArea";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->ChartArea;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets fill format of the ChartArea of a chart
	* $chartIndex
	*/
	public function getFillFormat($chartIndex)
	{
		try {
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
						$this->worksheetName . "/charts/" . $chartIndex . "/chartArea/fillFormat";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->FillFormat;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/**
    * Gets border of the ChartArea of a chart
	* $chartIndex
	*/
	public function getBorder($chartIndex)
	{
		try {
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/worksheets/" .
						$this->worksheetName . "/charts/" . $chartIndex . "/chartArea/border";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->Line;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}