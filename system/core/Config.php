<?php

namespace PmsOne;

use Composer\Downloader\FilesystemException;
use JsonSchema\Exception\JsonDecodingException;

class Config
{
    public $file;
    public $fileExtension;

    public $default = [];
    public $config = [];

    public function __construct($file = null)
    {
        if ($file) {
            $this->loadFile($file);
        }
    }

    /**
     * @param $file
     * @return $this
     * @throws \Exception
     */
    public function loadFile($file)
    {
        if (!file_exists($file)) {
            throw new \Exception('file not found: ' . $file);
        }

        $fileInfo = new \SplFileInfo($file);

        $this->file = $file;
        $this->fileExtension = $fileInfo->getExtension();

        $content = file_get_contents($this->file);
        $params = $this->convertFileContent($content);

        $this->setDefaults($params);
        $this->config = array_merge($params, $this->config);

        return $this;
    }

    /**
     * @return bool
     */
    public function updateFile()
    {
        if(file_put_contents($this->file, $this->getUpdatedContent())) {
            return true;
        }

        return false;
    }

    /**
     * @param $name
     * @param $value
     * @return $this
     */
    public function add($name, $value)
    {
        $this->config[$name] = $value;

        return $this;
    }

    public function addArray(array $data)
    {
        foreach ($data as $name => $value) {
            $this->add($name, $value);
        }

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function is($name)
    {
        return isset($this->config[$name]);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        if ($this->is($name)) {
            return $this->config[$name];
        }

        return null;
    }

    public function getAll()
    {
        return $this->config;
    }


    /**
     * @param $content
     * @return array
     * @throws JsonDecodingException
     */
    protected function convertFileContent($content)
    {
        switch ($this->fileExtension) {
            case 'json':
                $content = json_decode($content, true);
                break;
            case 'xml':
                $content = simplexml_load_string($content);
                $content = json_decode(json_encode($content), true);
                break;
            case 'ini':
                $content = parse_ini_string($content);
                break;
        }

        if (json_last_error() || !$content) {
            throw new JsonDecodingException('config can not be converted:' . $this->file);
        }

        return (array)$content;
    }

    /**
     * @return string
     */
    protected function getUpdatedContent()
    {
        switch ($this->fileExtension) {
            case 'json':
                return json_encode($this->config, JSON_PRETTY_PRINT);
            case 'xml':
                return $this->_convertToXMLObject($this->config)->asXML();
            case 'ini':
                return $this->_convertToIni($this->config);
        }

        return '';
    }

    /**
     * @param array $data
     * @param array $parent
     * @return string
     * @link http://stackoverflow.com/a/17317168
     */
    protected function _convertToIni(array $data, array $parent = [])
    {
        $out = '';
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                //subsection case
                //merge all the sections into one array...
                $sec = array_merge((array)$parent, (array)$key);
                //add section information to the output
                $out .= '[' . join('.', $sec) . ']' . PHP_EOL;
                //recursively traverse deeper
                $out .= $this->_convertToIni($val, $sec);
            } else {
                //plain key->value case
                $out .= $key . '=' . $val . PHP_EOL;
            }
        }
        return $out;
    }

    /**
     * @param array $data
     * @param \SimpleXMLElement|null $xmlObject
     * @return \SimpleXMLElement
     */
    protected function _convertToXMLObject(array $data, $xmlObject = null)
    {
        if ($xmlObject === null) {
            $xmlObject = new \SimpleXMLElement('<?xml version="1.0"?><config></config>');
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $subNode = $xmlObject->addChild($key);
                $this->_convertToXMLObject($value, $subNode);
            } else {
                $xmlObject->addChild($key, htmlspecialchars($value));
            }
        }

        return $xmlObject;
    }

    /**
     * @param array $options
     * @return $this
     */
    protected function setDefaults(array $options)
    {
        $this->default = $options;

        return $this;
    }
}