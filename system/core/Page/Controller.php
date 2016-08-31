<?php
namespace PmsOne\Page;

use PmsOne\I18n;
use PmsOne\Sql;
use PmsOne\View;
use PmsOne\Messages;
use Slim\Container;
use Slim\Router;

abstract class Controller
{
    /** @var Container $container */
    public $container;

    /** @var View $view */
    public $view;

    /** @var Sql $db */
    public $db;

    /** @var I18n $i18n */
    public $i18n;

    /** @var Messages $message */
    public $message;

    public $langSupport = false;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->db = $this->container->get('db');

        $this->view = new View();

        $this->i18n = I18n::getInstance();

        $this->messages = $this->container->get('messages');

        if ($this->langSupport) {
            $this->setLangSupport();
        }

        $this->init();
    }

    protected function setLangSupport()
    {
        $reflect = new \ReflectionClass($this);
        $dir = dirname(dirname($reflect->getFileName())) . '/lang/';

        $file = $dir . $this->getI18n()->getLangFileName();

        if (file_exists($file)) {
            $this->getI18n()->loadFile($file);
        }

        return $this;
    }

    /**
     * before the method loads
     * @return $this
     */
    public function init()
    {
        return $this;
    }

    /**
     * @return Sql
     */
    public function getDb()
    {
        return $this->db;
    }


    /**
     * @return View
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @return I18n
     */
    public function getI18n()
    {
        return $this->i18n;
    }

    /**
     * @return Messages
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @return Container
     */
    public function getAppContainer()
    {
        return $this->container;
    }

    /**
     * @return Router
     */
    public function getRouter()
    {
        return $this->getAppContainer()->get('router');
    }

}