<?php

namespace PmsOne\AddOn;


use PmsOne\Config;
use PmsOne\Helper\Dir;
use PmsOne\Pattern\Singleton;
use Slim\App;

class AddOnManager
{
    use Singleton;

    /** @var  Config $addOns */
    public $addOns;

    /** @var AddOn[] $activeAddOns */
    public $activeAddOns = [];

    protected $loaded = false;

    protected function init()
    {
        $this->addOns = new Config(Dir::system('addOns.json'));
        
        $this->setActiveAddOns();
    }

    /**
     * @param App $app
     * @return $this
     * @throws InvalidAddOnException
     */
    public function loadAllAddOns(App $app)
    {
        if ($this->loaded) {
            return $this;
        }

        foreach ($this->getAllActiveAddOns() as $addOn) {
            $addOn->loadBootFile($app);
            $addOn->setViewPath();
        }

        $this->loaded = true;

        return $this;
    }


    /**
     * @return array
     */
    public function getAllAddOns()
    {
        return $this->addOns->getAll();
    }

    /**
     * @return AddOn[]
     */
    public function getAllActiveAddOns()
    {
        return $this->activeAddOns;
    }

    /**
     * @return $this
     */
    protected function setActiveAddOns()
    {
        foreach ($this->getAllAddOns() as $addOn => $data) {
            if ($this->isActive($addOn)) {
                $this->activeAddOns[] = new AddOn($addOn);
            }
        }

        return $this;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function getAddOn($name)
    {
        return $this->addOns->get($name);
    }

    /**
     * @param $name
     * @return bool
     */
    public function isInstalled($name)
    {
        return $this->getAddOn($name)['install'] == 1;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isActive($name)
    {
        return $this->isInstalled($name) && $this->getAddOn($name)['active'] == 1;
    }
}