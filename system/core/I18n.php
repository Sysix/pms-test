<?php
namespace PmsOne;

use PmsOne\Pattern\Singleton;

/**
 * Class I18n
 * @package PmsOne
 */

use PmsOne\Helper\Dir;

class I18n
{
    use Singleton;

    const FILE_CONSTRUCT = '%s.json';

    public $lang;

    public $langKeys = [];

    protected $loadedFiles = [];

    protected function init()
    {

    }

    /**
     * @param $lang
     * @return $this
     */
    public function setLang($lang)
    {
        $this->lang = $lang;

        $this->loadFile(Dir::lang($this->getFileName($lang)));

        return $this;
    }

    public function getLangFileName()
    {
        return $this->getFileName($this->getLang());
    }

    public function getFileName($lang)
    {
        return sprintf(self::FILE_CONSTRUCT, $lang);
    }

    /**
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function add($key, $value)
    {
        $this->langKeys[$key] = $value;

        return $this;
    }

    /**
     * @param $key
     * @param [$variables..]
     * @return mixed
     * @throws \Exception
     */
    public function get($key)
    {
        $value = null;

        if (isset($this->langKeys[$key])) {
            $value = $this->langKeys[$key];
        }


        // $variables[0] is the key
        $variables = func_get_args();
        array_shift($variables);

        if (!empty($variables)) {
            $value = $this->getFormatted($value, $variables);
        }

        if ($value) {
            return $value;
        }

        throw new \Exception('lang key ' . $key . ' does not exists');
    }

    /**
     * @param $value
     * @param array $variables
     * @return string
     */
    protected function getFormatted($value, array $variables)
    {
        return vsprintf($value, $variables);
    }

    /**
     * @param $file
     * @return array
     * @throws \Exception
     */
    public function loadFile($file)
    {
        if (!isset($this->loadedFiles[$file])) {
            $config = new Config($file);

            $this->langKeys = array_merge(
                $config->getAll(),
                $this->langKeys
            );

            return $this;
        }

        throw new \Exception('language file ' . $file . ' already loaded');
    }


}