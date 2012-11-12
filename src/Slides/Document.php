<?php

namespace Saaspose\Pdf;

/*
* Deals with PowerPoint document level aspects
*/
class SlideDocument
{
	public $FileName = "";

	public function SlideDocument($fileName)
	{
		//set default values
		$this->FileName = $fileName;
	}

	/*
    * Finds the slide count of the specified PowerPoint document
	*/

	public function GetSlideCount()
	{
		try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			//Build URI to get a list of slides
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "/slides";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return count($json->Slides->SlideList);
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Replaces all instances of old text with new text in a presentation or a particular slide
	* @param string $oldText
	* @param string $newText
	*/
	public function ReplaceText()
	{
		$parameters = func_get_args();

		//set parameter values
		if(count($parameters)==2)
		{
			$oldText = $parameters[0];
			$newText = $parameters[1];
		}
		else if(count($parameters)==3)
		{
			$oldText = $parameters[0];
			$newText = $parameters[1];
			$slideNumber = $parameters[2];
		}
		else
			throw new Exception("Invalid number of arguments");
		try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			//Build URI to replace text
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . ((isset($parameters[2]))? "/slides/" . $slideNumber: "") .
						"/replaceText?oldValue=" . $oldText . "&newValue=" . $newText . "&ignoreCase=true";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save doc on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($this->FileName);
				$outputPath = SaasposeApp::$OutPutLocation . $this->FileName;
				Utils::saveFile($outputStream, $outputPath);
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
    * Gets all the text items in a slide or presentation
	*/
	public function GetAllTextItems()
	{
		$parameters = func_get_args();

		//set parameter values
		if(count($parameters)>0)
		{
			$slideNumber = $parameters[0];
			$withEmpty = $parameters[1];
		}

		try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			//Build URI to get all text items
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName .
						((isset($parameters[0]))? "/slides/" . $slideNumber . "/textItems?withEmpty=" . $withEmpty: "/textItems");

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);

			return $json->TextItems->Items;
		}
		catch (Exception $e)
		{
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Deletes all slides from a presentation
	*/
	public function DeleteAllSlides()
	{
		try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			//Build URI to replace text
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "/slides";

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "DELETE", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "") {
				//Save doc on server
				$folder = new Folder();
				$outputStream = $folder->GetFile($this->FileName);
				$outputPath = SaasposeApp::$OutPutLocation . $this->FileName;
				Utils::saveFile($outputStream, $outputPath);
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
    * Get Document's properties
	*/
	public function GetDocumentProperties(){
		try{
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("Base file not specified");


			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "/documentProperties";

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);


			if($json->Code == 200)
				return $json->DocumentProperties->List;
			else
				return false;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Get Resource Properties information like document source format, IsEncrypted, IsSigned and document properties
	@param string $propertyName
	*/
	public function GetDocumentProperty($propertyName){
		try{
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("Base file not specified");

			if ($propertyName == "")
				throw new Exception("Property Name not specified");

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "/presentation/documentProperties/" . $propertyName;

			//sign URI
			$signedURI = Utils::Sign($strURI);

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

	/*
    * Remove All Document's properties
	*/
	public function RemoveAllProperties(){
		try{
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("Base file not specified");


			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "/documentProperties";

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "DELETE");

			$json = json_decode($responseStream);
			if(is_object($json))
			{
				if($json->Code == 200)
					return true;
				else
					return false;
			}

			return true;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Delete a document property
	@param string $propertyName
	*/
	public function DeleteDocumentProperty($propertyName){
		try{
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("Base file not specified");

			if ($propertyName == "")
				throw new Exception("Property Name not specified");

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "/documentProperties/" . $propertyName;

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "DELETE", "", "");

			$json = json_decode($responseStream);

			if($json->Code == 200)
				return true;
			else
				return false;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Set document property
	@param string $propertyName
	@param string $propertyValue
	*/
	public function SetProperty($propertyName="",$propertyValue=""){
		try{
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("Base file not specified");

			if ($propertyName == "")
				throw new Exception("Property Name not specified");

			if ($propertyValue == "")
				throw new Exception("Property Value not specified");

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "/documentProperties/" . $propertyName;

			$put_data_arr['Value'] = $propertyValue;

			$put_data = json_encode($put_data_arr);

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT", "json", $put_data);

			$json = json_decode($responseStream);

			if($json->Code == 200)
				return $json->DocumentProperty;
			else
				return false;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Add custom document properties
	@param array $propertiesList
	*/
	public function AddCustomProperty($propertiesList=""){
		try{
			//check whether files are set or not
			if ($this->FileName == "")
				throw new Exception("Base file not specified");

			if ($propertiesList == "")
				throw new Exception("Properties not specified");


			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "/documentProperties";

			$put_data = json_encode($propertiesList);

			//sign URI
			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT", "json", $put_data);

			$json = json_decode($responseStream);

			return $json;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    *saves the document into various formats
	* @param string $outputPath
	* @param string $saveFormat
	*/

    public function SaveAs($outputPath="",$saveFormat="")
    {
       try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			if ($outputPath == "")
				throw new Exception("Output path not specified");

			if ($saveFormat == "")
				throw new Exception("Save format not specified");



			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "?format=" . $saveFormat;

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{

				Utils::saveFile($responseStream, $outputPath . Utils::getFileName($this->FileName) . "." . $saveFormat);
				return true;
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
    *Saves a particular slide into various formats
	* @param number $slideNumber
	* @param string $outputPath
	* @param string $saveFormat
	*/

    public function SaveSlideAs($slideNumber="",$outputPath="",$saveFormat="")
    {
       try
		{
			//check whether file is set or not
			if ($this->FileName == "")
				throw new Exception("No file name specified");

			if ($outputPath == "")
				throw new Exception("Output path not specified");

			if ($saveFormat == "")
				throw new Exception("Save format not specified");

			if ($slideNumber == "")
				throw new Exception("Slide number not specified");



			$strURI = Product::$BaseProductUri . "/slides/" . $this->FileName . "/slides/$slideNumber?format=" . $saveFormat;

			$signedURI = Utils::Sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::ValidateOutput($responseStream);

			if ($v_output === "")
			{

				Utils::saveFile($responseStream, $outputPath . Utils::getFileName($this->FileName) . "." . $saveFormat);
				return true;
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