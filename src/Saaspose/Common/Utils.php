<?php

namespace Saaspose\Common;

use Saaspose\Exception\SaasposeException as Exception;

if (!function_exists('curl_init')) {
    throw new Exception('Saaspose needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
    throw new Exception('Saaspose needs the JSON PHP extension.');
}

use Saaspose\Common\SaasposeApp;

/**
 * Provides access to the Saaspose Platform.
 * @author Imran Anwar <imran.anwar@Saaspose.com>
 */
class Utils
{
    
    public static $validateOutputMessages = array(
            "Unknown file format",
            "Incorect file format",
            "Unable to read beyond the end of the stream",
            "Index was out of range",
            "Cannot read that as a ZipFile",
            "Not a Microsoft PowerPoint 2007 presentation",
            "Index was outside the bounds of the array",
            "An attempt was made to move the position before the beginning of the stream",
            "Your pricing plan allows only",
            "is not found",
            "Attempted to divide by zero",
            "You have processed",
            "Your pricing plan allows only",
    );

    /**
     * Performs Saaspose Api Request.
     *
     * @param string $url Target Saaspose API URL.
     * @param string $method Method to access the API such as GET, POST, PUT and DELETE
     * @param string $headerType XML or JSON
     * @param string $src Post data.
     */
    public static function processCommand($url, $method="GET", $headerType="XML", $src="")
    {
        $method = strtoupper($method);
        $headerType = strtoupper($headerType);
        $session = curl_init();
        curl_setopt($session, CURLOPT_URL, $url);

        if ($method == "GET") {
            curl_setopt($session, CURLOPT_HTTPGET, 1);
        } else {
            curl_setopt($session, CURLOPT_POST, 1);
            curl_setopt($session, CURLOPT_POSTFIELDS, $src);
            curl_setopt($session, CURLOPT_CUSTOMREQUEST, $method);
        }

        curl_setopt($session, CURLOPT_HEADER, false);
        if ($headerType == "XML") {
            curl_setopt($session, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
        } else {
            curl_setopt($session, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        }

        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        if (preg_match('/^(https)/i', $url)) {
            curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        }
        
        $result = curl_exec($session);
        $header = curl_getinfo($session);
        
        curl_close($session);
        return $result;
    }

    /**
     * Performs Saaspose Api Request to Upload a file.
     *
     * @param string $url Target Saaspose API URL.
     * @param string $localfile Local file
     * @param string $headerType XML or JSON
     */
    public static function uploadFileBinary($url, $localfile, $headerType="XML")
    {
        $headerType = strtoupper($headerType);
        $fp = fopen($localfile, "r");

        $saasposeUser = SaasposeApp::$appSid;
        $saasposePass = SaasposeApp::$appKey;

        $session = curl_init();
        curl_setopt($session, CURLOPT_VERBOSE, 1);
        curl_setopt($session, CURLOPT_USERPWD, $saasposeUser . ':' . $saasposePass);
        curl_setopt($session, CURLOPT_URL, $url);
        curl_setopt($session, CURLOPT_PUT, 1);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($session, CURLOPT_HEADER, false);

        if ($headerType == "XML") {
            curl_setopt($session, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
        } else {
            curl_setopt($session, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        }
        curl_setopt($session, CURLOPT_INFILE, $fp);
        curl_setopt($session, CURLOPT_INFILESIZE, filesize($localfile));

        $result = curl_exec($session);
        curl_close($session);
        fclose($fp);
        return $result;
    }

    /**
     * Encode a string to URL-safe base64
     *
     * @param string $value Valure to endode.
     */
    private static function encodeBase64UrlSafe($value)
    {
        return str_replace(array('+', '/'), array('-', '_'), base64_encode($value));
    }

    /**
     * Decode a string from URL-safe base64
     * @param string $value Value to Decode.
     */
    private static function decodeBase64UrlSafe($value)
    {
        return base64_decode(str_replace(array('-', '_'), array('+', '/'), $value));
    }

	public static function sign($urlToSign)
	{
        // parse the url
        $url = parse_url($urlToSign);

        if (isset($url['query']) == "") {
            $urlPartToSign = $url['path'] . "?appSid=" . SaasposeApp::$appSid;
        } else {
            $urlPartToSign = $url['path'] . "?" . str_replace(" ","%20",$url["query"]) . "&appSid=" . SaasposeApp::$appSid;
        }

        // Decode the private key into its binary format
        $decodedKey = self::decodeBase64UrlSafe(SaasposeApp::$appKey);

        // Create a signature using the private key and the URL-encoded
        // string using HMAC SHA1. This signature will be binary.
        $signature = hash_hmac("sha1", $urlPartToSign, $decodedKey, true);

        $encodedSignature = self::encodeBase64UrlSafe($signature);

        // return $urlToSign . "?appSid=" . $this->appSid . "&signature=" . $encodedSignature;
        if (isset($url['query']) == "") {
            return $url["scheme"] . "://" . $url["host"] . str_replace(" ", "%20",$url["path"]) . "?appSid=" . SaasposeApp::$appSid . "&signature=" . $encodedSignature;
        } else {
            return $url["scheme"] . "://" . $url["host"] . str_replace(" ", "%20",$url["path"]) . "?" . str_replace(" ","%20",$url["query"]) . "&appSid=" . SaasposeApp::$appSid . "&signature=" . $encodedSignature;
        }
    }

    /**
     * Will get the value of a field in JSON Response
     * @param string $jsonRespose JSON Response string.
     * @param string $fieldName Field to be found.
     * @return getFieldValue($jsonRespose, $fieldName) - String Value of the given Field.
     */
    public static function getFieldValue($jsonRespose, $fieldName)
    {
        return json_decode($jsonResponse)->{$fieldName};
    }

    /**
     * This method parses XML for a count of a particular field.
     * @param string $jsonRespose JSON Response string.
     * @param string $fieldName Field to be found.
     * @return getFieldCount($jsonRespose, $fieldName) - String Value of the given Field.
     */
    public static function getFieldCount($jsonResponse, $fieldName)
    {
		$arr = json_decode($jsonResponse)->{$fieldName};
		return count($arr,COUNT_RECURSIVE);
    }

    /**
     * Copies the contents of input to output. Doesn't close either stream.
     * @param string $input input stream.
     * @return copyStream($input) - Outputs the converted input stream.
     */
    public static function copyStream($input)
    {
		return stream_get_contents($input);
    }

	/**
     * Saves the files
     * @param string $input input stream.
     * @param string $fileName fileName along with the full path.
     */
    public static function saveFile($input, $fileName)
    {
		$fh = fopen($fileName, 'w') or die("can't open file");
		fwrite($fh, $input);
		fclose($fh);
    }

	public static function getFileName($file)
	{
		$info = pathinfo($file);
		$file_name =  basename($file,'.'.$info['extension']);
		return $file_name;
	}

	public static function validateOutput($result)
	{
		$string = (string)$result;

		$invalid = 0;
		foreach (self::$validateOutputMessages as $key => $value) {
			$pos = strpos($string, $value);
			if ($pos === 1) {
				$invalid = 1;
			}
		}

		if ($invalid == 1) {
		   return $string;
		} else {
		   return "";
		}
	}

}