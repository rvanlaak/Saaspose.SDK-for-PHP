<?php

namespace Saaspose\Cells;

/*
* This class contains features to work with charts
*/
class Workbook
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

	/*
    * Get Document's properties
	*/
	public function getProperties()
	{
		try{
			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/documentProperties";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$json = json_decode($responseStream);


			if($json->Code == 200)
				return $json->DocumentProperties->DocumentPropertyList;
			else
				return false;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Get Resource Properties information like document source format, isEncrypted, isSigned and document properties
	@param string $propertyName
	*/
	public function GetProperty($propertyName){
		try{
			//check whether files are set or not
			if ($this->fileName == "")
				throw new Exception("Base file not specified");

			if ($propertyName == "")
				throw new Exception("Property Name not specified");

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/documentProperties/" . $propertyName;

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

	/*
    * Set document property
	@param string $propertyName
	@param string $propertyValue
	*/
	public function SetProperty($propertyName="",$propertyValue=""){
		try{
			//check whether files are set or not
			if ($this->fileName == "")
				throw new Exception("Base file not specified");

			if ($propertyName == "")
				throw new Exception("Property Name not specified");

			if ($propertyValue == "")
				throw new Exception("Property Value not specified");

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/documentProperties/" . $propertyName;

			$put_data_arr['Value'] = $propertyValue;

			$put_data = json_encode($put_data_arr);

			//sign URI
			$signedURI = Utils::sign($strURI);

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
    * Remove All Document's properties
	*/
	public function RemoveAllProperties(){
		try{
			//check whether files are set or not
			if ($this->fileName == "")
				throw new Exception("Base file not specified");


			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/documentProperties";

			//sign URI
			$signedURI = Utils::sign($strURI);

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
	public function RemoveProperty($propertyName){
		try{
			//check whether files are set or not
			if ($this->fileName == "")
				throw new Exception("Base file not specified");

			if ($propertyName == "")
				throw new Exception("Property Name not specified");

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName . "/documentProperties/" . $propertyName;

			//sign URI
			$signedURI = Utils::sign($strURI);

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
    * Create Empty Workbook
	*/
	public function CreateEmptyWorkbook(){
		try{

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName ;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT");

			$json = json_decode($responseStream);

			return $json;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Create Empty Workbook
	* @param string $templatefileName
	*/
	public function CreateWorkbookFromTemplate($templatefileName){
		try{

			if ($templatefileName == "")
				throw new Exception("Template file not specified");

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName ."?templatefile=".$templatefileName;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT");

			$json = json_decode($responseStream);

			return $json;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Create Empty Workbook
	* @param string $templatefileName
	* @param string $dataFile
	*/
	public function CreateWorkbookFromSmartMarkerTemplate($templatefileName="",$dataFile=""){
		try{

			if ($templatefileName == "")
				throw new Exception("Template file not specified");

			if ($dataFile == "")
				throw new Exception("Data file not specified");

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName ."?templatefile=".$templatefileName."&dataFile=".$dataFile;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "PUT");

			$json = json_decode($responseStream);

			return $json;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Process Smartmaker Datafile
	* @param string $dataFile
	*/
	public function ProcessSmartMarker($dataFile=""){
		try{

			if ($templatefileName == "")
				throw new Exception("Template file not specified");

			if ($dataFile == "")
				throw new Exception("Data file not specified");

			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName ."/smartmarker?xmlFile=".$dataFile;

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "POST");

			$json = json_decode($responseStream);

			return $json;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Get Worksheets Count in Workbook
	*/
	public function GetWorksheetsCount(){
		try{

			if ($this->fileName == "")
				throw new Exception("Base file not specified");


			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName ."/worksheets";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET");


			return true;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Get Names Count in Workbook
	*/
	public function GetNamesCount(){
		try{

			if ($this->fileName == "")
				throw new Exception("Base file not specified");


			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName ."/names";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET");

			$json = json_decode($responseStream);
			return $json->Names.Count;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

	/*
    * Get Default Style
	*/
	public function getDefaultStyle(){
		try{

			if ($this->fileName == "")
				throw new Exception("Base file not specified");


			//build URI to merge Docs
			$strURI = Product::$BaseProductUri . "/cells/" . $this->fileName ."/defaultStyle";

			//sign URI
			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET");

			$json = json_decode($responseStream);

			return $json->Names.Count;

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}

}