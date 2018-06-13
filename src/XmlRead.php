<?php

namespace FC;

/**
 * class to read & parse XML files
 */
abstract class XmlRead {
/* constants */
    const USER_ERROR = true;    // used by setErrorHandler()
    const SYSTEM_ERROR = false; // used by setErrorHandler()

/* methods */
    /**
     * use with parameters ***_ERROR to set error system (user or system)
     *
     * @param boolean $parErrorSystem USER_ERROR or SYSTEM_ERROR
     * @return void
     */
    public static function setErrorHandler(bool $parErrorSystem = self::USER_ERROR) { libxml_use_internal_errors($parErrorSystem); }

    /**
     * clear libxml error buffer (for past errors)
     *
     * @return void
     */
    public static function clearErrors() { libxml_clear_errors(); }

    /**
     * convert a XML string into a SimpleXMLElement object
     * return false on failure, SimpleXMLElement object on success
     *
     * @param string $parXmlString string with XML content ("<tag>value</tag>")
     * @return SimpleXMLElement
     */
    public static function loadFromString(string $parXmlString): SimpleXMLElement {
        self::clearErrors();

        if ($parXmlString == "") { $xml = false; }
        else { $xml = simplexml_load_string($parXmlString); }

        //print_r($xml);
        return $xml;
    }

    /**
     * convert a XML file into a SimpleXMLElement object
     * return false on failure, SimpleXMLElement object on success
     *
     * @param string $parXmlFile path to XML file
     * @return SimpleXMLElement
     */
    public static function loadFromFile(string $parXmlFile): SimpleXMLElement {
        self::clearErrors();
        
        if (!file_exists($parXmlFile)) { $xml = false; }
        else { $xml = simplexml_load_file($parXmlFile); }
        
        //print_r($xml);
        return $xml;
    }

    /**
     * retrieve and build message from error(s) happening during XML file handling
     * return errors text
     *
     * @return string
     */
    public static function getErrors(): string {
        $errors = libxml_get_errors();
        $errorTxt = '';

        foreach ($errors as $error) {
            switch ($error->level) {
                case LIBXML_ERR_WARNING:
                    $errorTxt .= "Warning $error->code: ";
                    break;
                case LIBXML_ERR_ERROR:
                    $errorTxt .= "Error $error->code: ";
                    break;
                case LIBXML_ERR_FATAL:
                    $errorTxt .= "Fatal Error $error->code: ";
                    break;
            }

            $errorTxt .= trim($error->message) .
                    PHP_EOL."  Line: $error->line" .
                    PHP_EOL."  Column: $error->column";

            if ($error->file) {
                $errorTxt .= PHP_EOL."  File: $error->file";
            }
        }

        self::clearErrors();
        return $errorTxt;
    }

}
	
