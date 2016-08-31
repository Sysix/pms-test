<?php

namespace PmsOne;

use Slim\App;

class View extends \Twig_Environment
{
    /** @var \Twig_Loader_Filesystem $twigFileSystem */
    protected static $twigFileSystem;

    /** @var  \Slim\App $app */
    protected static $app;

    public $template;

    public $vars = [];

    public function __construct()
    {
        parent::__construct(self::$twigFileSystem, [
            'debug' => true
        ]);

        $this->addGlobal('navigation', Navigation::getNavigation('main-navigation'));

        $this->addExtension(new \Twig_Extension_Debug());

        $this->addFilter(new \Twig_SimpleFilter('formatAttribute', ['PmsOne\Helper\Formatter', 'formatAttributes'], [
            'is_safe' => ['html']
        ]));

        $this->addFunction('path_for', new \Twig_SimpleFunction('path_for', array($this, 'pathFor')));
    }

    public static function pathFor($name, $data = [], $queryParams = [])
    {
        return static::$app->getContainer()->get('router')->pathFor($name, $data, $queryParams);
    }

    /**
     * @param $template
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function addVar($key, $value)
    {
        $this->vars[$key] = $value;

        return $this;
    }

    /**
     * @param array $map
     * @return $this
     */
    public function addVars(array $map)
    {
        foreach ($map as $key => $value) {
            $this->addVar($key, $value);
        }

        return $this;
    }

    /**
     * @param $key
     * @return null
     */
    public function getVar($key)
    {
        if (isset($this->vars[$key])) {
            return $this->vars[$key];
        }

        return null;
    }

    /**
     * @return array
     */
    public function getVars()
    {
        return $this->vars;
    }

    /**
     * @param string $name
     * @param array $context
     * @return string
     */
    public function render($name = null, array $context = [])
    {
        return parent::render($this->getTemplate(), $this->getVars());
    }


    /**
     * @param App $app
     * @param $path
     */
    public static function setUpTwig(App $app, $path)
    {
        self::$app = $app;
        self::$twigFileSystem = new \Twig_Loader_Filesystem($path);
    }

    public static function addPath($path, $namespace = null)
    {
        if ($namespace) {
            self::$twigFileSystem->addPath($path, $namespace);
        } else {
            self::$twigFileSystem->addPath($path);
        }
    }
}