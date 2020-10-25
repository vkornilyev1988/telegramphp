<?php

namespace Nekrida\Core;


class Storage
{
    const STORAGE_PREFIX = 'st@';

    public static function delete($file) {
        if (strpos($file,self::STORAGE_PREFIX) === 0)
            return unlink(static::url($file));
        else
            return true;
    }

    /**
     * Prints the file with the headers for downloading
     * @param $url
     * @param $name
     * @param $mimeType
     * @param Request $request
     * @return bool
     */
    public static function download($url,$name,$mimeType,Request $request) {
        if (strpos($url,self::STORAGE_PREFIX) === 0) {
            $url = str_replace('/storage',self::STORAGE_PREFIX,$url);
            $request->setHeader('Content-Description', 'File Transfer');
            $request->setHeader('Content-Type', $mimeType);
            $request->setHeader('Content-Disposition', 'attachment; filename="'.$name.'"');

            return !!readfile($url);
        } else {
            $request->redirectByUrl($url);
            return true;
        }
    }

    /**
     * Prints the file with the headers for opening
     * @param $url
     * @param $mimeType
     * @param Request $request
     * @return bool
     */
    public static function get($url,$mimeType, Request $request) {
        if (strpos($url,self::STORAGE_PREFIX) === 0) {
            $url = static::url($url);
            $request->setHeader('Content-Description', 'File Transfer');
            $request->setHeader('Content-Type', $mimeType);

            return !!readfile($url);
        } else {
            $request->redirectByUrl($url);
            return true;
        }
    }

    //For multi-server Storage this function will send the file to the external server and get url from it

    /**
     * @param $file $_FILE typed file
     * @param array $options
     * @param $storage string Name of the storage (configured at 'config/storage.php')
     * @return bool|string returns file path or false on error
     */
    public static function upload($file,$options = [],$storage = 'main') {
    	var_dump($file);
        if ($file['error'] != 0) return false;

        $uploadFile = preg_replace_callback('/\{(\w+)\}/', function($matches) use ($storage, $file, $options) {
            //from options
            if (isset($options[$matches[1]]))
                return $options[$matches[1]];
            //from config
            elseif (Config::get("storage/local/{$storage}/rules/{$matches[1]}/random"))
                return self::getRandomNameByConfig($matches[1], $storage);
            //from file
            elseif( $matches[1] == 'name')
                return $file['name'];
            else
                return '';

        },Config::get("storage/local/{$storage}/fileName"));

        $uploadDir = preg_replace_callback('/\{(\w+)\}/', function($matches) use ($storage,$options) {
            //from options
            if (isset($options[$matches[1]]))
                return $options[$matches[1]];
            //from config
            elseif (Config::get("storage/local/{$storage}/rules/{$matches[1]}/random"))
                return self::getRandomNameByConfig($matches[1],$storage);
            else
                return '';
        },Config::get("storage/local/{$storage}/directory"));
        $root = str_replace('{root}',Config::dir(),Config::get("storage/local/{$storage}/root"));

        var_dump($root,$uploadDir,$uploadFile);

        if (!file_exists($root.$uploadDir))
            mkdir($root.'/'.$uploadDir,0777,true);
        if (!move_uploaded_file($file['tmp_name'],$root.'/'.$uploadDir.'/'.$uploadFile))
            return false;

        return self::STORAGE_PREFIX.$storage.'://'.$uploadDir.'/'.$uploadFile;
        // st@main://1968/3269-Rogaty.docx
    }

    /**
     * Returns real link to the object from the virtual link set in the configurations.
     * @param string $link
     * @return string
     */
    public static function url($link) {
        if (strpos($link,self::STORAGE_PREFIX) === 0) {
            $headEnd = strpos($link,'://');
            $prefixLength = strlen(self::STORAGE_PREFIX);
            $storage = substr($link,$prefixLength,$headEnd - $prefixLength);
            $root = str_replace('{root}','',Config::get("storage/local/{$storage}/root"));
            
            $url = str_replace(self::STORAGE_PREFIX.$storage.':/',$root, $link);
            return $url;
        } else
            return $link;
    }

    public static function link($link) {
        if (strpos($link,self::STORAGE_PREFIX) === 0) {
            $headEnd = strpos($link,'://');
            $prefixLength = strlen(self::STORAGE_PREFIX);
            $storage = substr($link,$prefixLength,$headEnd - $prefixLength);
            $root = str_replace('{root}','',Config::get("storage/local/{$storage}")['root']);

            $url = str_replace(self::STORAGE_PREFIX.$storage.':/',$root, $link);
            return $url;
        } else
            return $link;
    }

    /**
     * Returns random name based on the config settings from config/storage.php
     * @param $name
     * @param $storage
     * @return string
     */
    protected static function getRandomNameByConfig($name,$storage) {
        $nameSize = Config::get("storage/local/{$storage}/rules/{$name}/size");
        $symbols = Config::get("storage/local/{$storage}/rules/{$name}/symbols");
        $str = '';
        for ($i = 0; $i < $nameSize; $i++)
            $str .= $symbols[rand(0,count($symbols) -1)];
        return $str;
    }
}