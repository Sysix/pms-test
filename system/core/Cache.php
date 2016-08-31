<?php
/**
 * Created by PhpStorm.
 * User: Sysix
 * Date: 20.09.2015
 * Time: 21:56
 */

namespace PmsOne;


use PmsOne\Helper\Dir;

class Cache
{
    const EXTENSION = '.cache';

    public $name;
    public $liveTime = 0;

    public $data = [];

    public static $cacheActive;

    /** @var int $defaultLiveTime */
    public static $defaultLiveTime = 0;

    public function __construct($name, $time = null)
    {
        $this->name = $name;

        if ($time == null) {
            $time = self::$defaultLiveTime;
        }

        $this->setLiveTime($time);
    }


    /**
     * @param $time
     * @return $this
     * @throws \Exception
     */
    public function setLiveTime($time)
    {
        if (!is_int($time)) {
            throw new \Exception($time . ' is not a valid timestamp');
        }
        $this->liveTime = $time;

        return $this;
    }

    /**
     * @param mixed $data
     * @return $this
     */
    public function addData($data)
    {
        $this->data[] = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function getDataContent()
    {
        return implode('', $this->data);
    }

    /**
     * @return string
     */
    public function getFileContent()
    {
        return file_get_contents($this->getFile());
    }

    /**
     * @return bool
     */
    public function isCache()
    {
        if (!self::$cacheActive) {
            return false;
        }

        if(!file_exists($this->getFile())) {
            return false;
        }

        return time() - filemtime($this->getFile()) < $this->liveTime;
    }

    /**
     * @return string
     */
    public function getFile()
    {
        return $this->getPath() . $this->getFilename();
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return sha1($this->name) . static::EXTENSION;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return Dir::cache();
    }

    /**
     * @return string
     */
    public function getCache()
    {
        if(!$this->isCache()) {
            return $this->getDataContent();
        }

        return $this->getFileContent();
    }

    /**
     * @return $this
     */
    public function saveCache()
    {
        if (!static::$cacheActive) {
            return $this;
        }

        file_put_contents($this->getFile(), $this->getDataContent(), LOCK_EX);

        return $this;
    }

    /**
     * @return bool
     */
    public function deleteCache()
    {
        return unlink($this->getFile());
    }

    /**
     * @param $cache
     * @param $liveTime
     */
    public static function setUpCache($cache, $liveTime)
    {
        static::$cacheActive = $cache;
        static::$defaultLiveTime = $liveTime;
    }
}