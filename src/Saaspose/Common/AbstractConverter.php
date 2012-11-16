<?php

namespace Saaspose\Common;

use Saaspose\Exception\SaasposeException as Exception;
use Saaspose\Common\Product;
use Saaspose\Common\SaasposeApp;
use Saaspose\Common\Utils;

class AbstractConverter
{
	/**
	 * convert a document to SaveFormat
	 */
	public static function baseConvert($fileType, $fileName, $saveFormat)
	{
		try {
			$strURI = sprintf("%s/%s/%s?format=%s",
					Product::$baseProductUri,
					$fileType,
					$fileName,
					$saveFormat
			);

			$signedURI = Utils::sign($strURI);

			$responseStream = Utils::processCommand($signedURI, "GET", "", "");

			$v_output = Utils::validateOutput($responseStream);

			if ($v_output === "") {
				if($saveFormat == "html") {
					$saveFormat = "zip";
				}

				Utils::saveFile($responseStream,
						SaasposeApp::$outputLocation . Utils::getFileName($fileName). "." . $saveFormat);
				return "";

			} else {
				return $v_output;
			}

		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}