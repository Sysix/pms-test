<?php
if (version_compare(PHP_VERSION, '5.4', '<')) {
    die('system need php version 5.4');
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

//TODO: safer sessions start
session_start();

include 'vendor/autoload.php';


use PmsOne\Helper\Dir;
use PmsOne\Config;
use PmsOne\Sql;
use PmsOne\I18n;
use PmsOne\AddOn\AddOnManager;
use PmsOne\View;
use PmsOne\Cache;

Dir::setUpPath(START_DIR_PATH);

// Create App
/** @property Config $config */
$app = new \Slim\App([
    'settings' => [
        'displayErrorDetails' => true
    ]
]);

$container = $app->getContainer();

// add config to container
$config = new Config(Dir::system('config.json'));
$container['config'] = function () use ($config) {
    return $config;
};

// setup sql
$sql = new Sql();
$sql->setDatabases($config->get('database'));
Sql\DataObject::setDb($sql);
$container['db'] = function () use ($sql, $config) {
    return $sql;
};

// setup cross messages for success or failure
$container['messages'] = function () {
    return new \PmsOne\Messages();
};

// set the right encoding
mb_internal_encoding($config->get('encoding'));
header('content-type: text/html; charset=' . $config->get('encoding'));

// setup cache
Cache::setUpCache($config->get('cache'), 60 * 60 * 24);

// set the right timezone
if ($timezone = $config->get('timezone')) {
    date_default_timezone_set($timezone);
}

// start setup
if ($config->get('setup')) {
    header('Location: ' . $config->get('url') . 'install/');
    exit();
}

//configuration twig template engine
View::setUpTwig($app, Dir::view());

// Navigation
$navigation = new \PmsOne\Navigation('main-navigation');
$navigation->addClass('col col-md-2 nav');
$navigation->addAttribute('id', 'nav');

$container['navigation'] = function () use ($navigation) {
    return $navigation;
};

// set language
$i18n = I18n::getInstance()->setLang($config->get('lang'));

// load AddOns
AddOnManager::getInstance()->loadAllAddOns($app);

