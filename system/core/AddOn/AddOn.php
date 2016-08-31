<?php

namespace PmsOne\AddOn;

use PmsOne\Config;
use PmsOne\Helper\Dir;
use PmsOne\View;
use Slim\App;

class AddOn
{
    public $name;

    /** @var Config $config */
    public $config;

    /** @var AddOnManager $manager */
    public $manager;

    const CONFIG_FILE = 'config.json';

    const BOOT_FILE = 'boot.php';

    const INSTALL_FILE = 'install.php';
    const UNINSTALL_FILE = 'uninstall.php';
    const UPDATE_FILE = 'update.php';

    public function __construct($name)
    {
        $this->name = $name;

        if (!file_exists($this->getPath())) {
            throw new InvalidAddOnException('addon ' . $name . ' not exists');
        }

        $this->manager = AddOnManager::getInstance();

        $this->loadConfig();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return Dir::addOn($this->name . '/');
    }

    /**
     * @return AddOnManager
     */
    public function getManager()
    {
        return $this->manager;
    }

    /**
     * @return bool
     */
    public function isInstalled()
    {
        return $this->getManager()->isInstalled($this->name);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->getManager()->isActive($this->name);
    }

    /**
     * @return $this
     */
    public function install()
    {
        $this->includeFileIfExists($this->getPath() . self::INSTALL_FILE);

        $addOn = $this->getManager()->getAddOn($this->name);

        $addOn['install'] = true;

        return $this;
    }

    public function unInstall()
    {
        $this->includeFileIfExists($this->getPath() . self::UNINSTALL_FILE);

        $addOn = $this->getManager()->getAddOn($this->name);

        $addOn['install'] = false;

        return $this;
    }

    public function reInstall()
    {
        //TODO: right mechanism concept
        return $this->update();
    }

    public function activate()
    {
        $addOn = $this->getManager()->getAddOn($this->name);

        $addOn['active'] = true;

        $this->loadBootFile();

        return $this;
    }

    public function deActive()
    {
        $addOn = $this->getManager()->getAddOn($this->name);

        $addOn['active'] = false;

        return $this;
    }

    public function update()
    {
        $this->includeFileIfExists($this->getPath() . self::UPDATE_FILE);

        return $this;
    }

    /**
     * @param $file
     * @return bool
     */
    protected function includeFileIfExists($file)
    {
        if (file_exists($file)) {
            include_once $file;

            return true;
        }

        return false;
    }

    /**
     * @param $app
     * @return $this
     * @throws InvalidAddOnException
     */
    public function loadBootFile($app)
    {
        $file = $this->getPath() . self::BOOT_FILE;

        if (!file_exists($file)) {
            throw new InvalidAddOnException('boot file ' . $file . ' not exists');
        }

        include_once $file;

        return $this;
    }

    /**
     * @return $this
     */
    protected function loadConfig()
    {
        $this->config = new Config($this->getPath() . self::CONFIG_FILE);

        return $this;
    }

    public function setViewPath()
    {
        if (file_exists($this->getPath() . 'views')) {
            View::addPath($this->getPath() . 'views', strtolower($this->name));
        }

        return $this;
    }


    /**
     * @param null $key
     * @return Config|mixed
     */
    public function getConfig($key = null)
    {
        if ($key === null) {
            return $this->config;
        }

        return $this->config->get($key);
    }
}