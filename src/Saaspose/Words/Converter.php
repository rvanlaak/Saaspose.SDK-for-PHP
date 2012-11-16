<?php

namespace Saaspose\Words;

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
	public static function convert($fileName, $saveFormat = 'xls')
	{
		return static::baseConvert('words', $fileName, $saveFormat);
	}
}
