<?php

namespace Saaspose\Words;

/*
* Extract various types of information from the document
*/
class WordExtractor
{
	public $FileName = "";

    public function WordExtractor($fileName)
    {
        $this->FileName = $fileName;
    }


	/*
    * Gets Text items list from document
	*/

	public function GetText()
	{
		try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/words/" . $this->FileName . "/textItems";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->TextItems->List;
			//echo $json->TextItems->List[0]->Text;
			//return count($json->Images->List);

		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Get the OLE drawing object from document
	* @param int $index
	* @param string $OLEFormat
	*/

    public function GetoleData($index, $OLEFormat)
    {
       try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/words/" . $this->FileName . "/drawingObjects/" . $index . "/oleData";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				Utils::saveFile($responseStream, SaasposeApp::$OutPutLocation . Utils::getFileName($this->FileName). "_" . $index . "." . $OLEFormat);
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
    * Get the Image drawing object from document
	* @param int $index
	* @param string $renderformat
	*/

    public function GetimageData($index, $renderformat)
    {
       try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/words/" . $this->FileName . "/drawingObjects/" . $index . "/ImageData";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				Utils::saveFile($responseStream, SaasposeApp::$OutPutLocation . Utils::getFileName($this->FileName). "_" . $index . "." . $renderformat);
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
    * Convert drawing object to image
	* @param int $index
	* @param string $renderformat
	*/

    public function ConvertDrawingObject($index, $renderformat)
    {
       try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/words/" . $this->FileName . "/drawingObjects/" . $index . "?format=" . $renderformat;

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{
				Utils::saveFile($responseStream, SaasposeApp::$OutPutLocation . Utils::getFileName($this->FileName). "_" . $index . "." . $renderformat);
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
    * Get the List of drawing object from document
	*/

    public function GetDrawingObjectList()
    {
       try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			$strURI = Product::$BaseProductUri . "/words/" . $this->FileName . "/drawingObjects";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			if($json->Code == 200)
				return $json->DrawingObjects->List;
			else
				return false;


		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
    }

	/*
    * Get the drawing object from document
	* @param string $objectURI
	* @param string $outputPath
	*/

    public function GetDrawingObject($objectURI="",$outputPath="")
    {
       try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			if ($outputPath == "")
				throw new Exception("Output path not specified");

			if ($objectURI == "")
				throw new Exception("Object URI not specified");

			$url_arr = explode("/",$objectURI);
			$objectIndex = end($url_arr);

			$strURI = $objectURI;

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			if($json->Code == 200)
			{
				if($json->DrawingObject->ImageDataLink != "")
				{
					$strURI = $strURI . "/imageData";
					$outputPath = $outputPath . "\\DrawingObject_" . $objectIndex . ".jpeg";
				}
				else if($json->DrawingObject->OLEDataLink != "")
				{
					$strURI = $strURI . "/oleData";
					$outputPath = $outputPath . "\\DrawingObject_" . $objectIndex . ".xlsx";
				}
				else
				{
					$strURI = $strURI . "?format=jpeg";
					$outputPath = $outputPath . "\\DrawingObject_" . $objectIndex . ".jpeg";
				}

				$signedURI = Utils::Sign($strURI);

				$responseStream = Utils::processCommand($signedURI, "GET", "", "");

				$v_output = Utils::ValidateOutput($responseStream);

				if ($v_output === "")
				{
					Utils::saveFile($responseStream, $outputPath);
					return true;
				}
				else
					return $v_output;
			}
			else
			{
				return false;
			}

		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
    }


	/*
    * Get the List of drawing object from document
	* @param string outputPath
	*/

    public function GetDrawingObjects($outputPath="")
    {
       try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			if ($outputPath == "")
				throw new Exception("Output path not specified");

			$strURI = Product::$BaseProductUri . "/words/" . $this->FileName . "/drawingObjects";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			if($json->Code == 200)
			{
				foreach($json->DrawingObjects->List as $object)
				{
					$this->GetDrawingObject($object->link->Href,$outputPath);
				}
			}
			else
				return false;


		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
    }
}
