<?php

namespace Saaspose\Pdf;

/*
* converts pages or document into different formats
*/
class PDFConverter
{
	public $FileName = "";
	public $saveformat = "";

    public function PDFConverter($fileName)
    {
        $this->FileName = $fileName;

		$this->saveformat =  "Pdf";
    }

	/*
    * convert a particular page to image with specified size
	* @param string $pageNumber
	* @param string $imageFormat
	* @param string $width
	* @param string $height
	*/

    public function ConvertToImagebySize($pageNumber, $imageFormat, $width, $height)
    {
       try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/pages/" . $pageNumber . "?format=" . $imageFormat . "&width=" . $width . "&height=" . $height;

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				Utils::saveFile($responseStream, SaasposeApp::$OutPutLocation . Utils::getFileName($this->FileName). "_" . $pageNumber . "." . $imageFormat);
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

	/*
    * convert a particular page to image with default size
	* @param string $pageNumber
	* @param string $imageFormat
	*/
	public function ConvertToImage($pageNumber, $imageFormat)
	{
		try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "/pages/" . $pageNumber . "?format=" . $imageFormat;

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				Utils::saveFile($responseStream, SaasposeApp::$OutPutLocation . Utils::getFileName($this->FileName). "_" . $pageNumber . "." . $imageFormat);
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

	/*
    * convert a document to SaveFormat
	*/
	public function Convert()
	{
		try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/pdf/" . $this->FileName . "?format=" . $this->saveformat;

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				if($this->saveformat == "html")
					$save_format = "zip";
				else
					$save_format = $this->saveformat;

				Utils::saveFile($responseStream, SaasposeApp::$OutPutLocation . Utils::getFileName($this->FileName). "." . $save_format);
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

	/*
    * Convert PDF to different file format without using storage
	* $param string $inputFile
	* @param string $outputFilename
	* @param string $outputFormat
	*/
	public function ConvertLocalFile($inputFile="",$outputFilename="",$outputFormat="")
	{
		try
		{
			//check whether file is set or not
			if ($inputFile == "")
				throw new Exception("No file name specified");

			if ($outputFormat == "")
				throw new Exception("output format not specified");


			$strURI = Product::$BaseProductUri . "/pdf/convert?format=" . $outputFormat;

			if(!file_exists($inputFile))
			{
				throw new Exception("input file doesn't exist.");
			}


			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::uploadFileBinary($signedURI, $inputFile , "xml");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				if($outputFormat == "html")
					$save_format = "zip";
				else
					$save_format = $outputFormat;

				if($outputFilename == "")
				{
					$outputFilename = Utils::getFileName($inputFile). "." . $save_format;
				}

				Utils::saveFile($responseStream, SaasposeApp::$OutPutLocation . $outputFilename);
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

}