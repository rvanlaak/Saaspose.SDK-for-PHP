<?php

namespace Saaspose\Words;

use Saaspose\Storage\Folder;
use Saaspose\Common\Product;
use Saaspose\Common\AbstractConverter;
use Saaspose\Common\SaasposeApp;
use Saaspose\Common\Utils;
use Saaspose\Exception\SaasposeException as Exception;

/**
* converts pages or document into different formats
*/
class Converter extends AbstractConverter
{
	/**
    * converts a document to given saveformat
	*/
	public function convert($localFileName, $saveFormat = 'Doc')
	{
		// First upload local file
		$folder = new Folder();

		// Returns curl request
		if ($folder->uploadFile($localFileName) != false) {
			$fileName = basename($localFileName);
			$res = $this->baseConvert('words', $fileName, $saveFormat);
			return $res;
		} else {
			return false;
		}
	}
}
