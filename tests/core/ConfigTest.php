<?php

class ConfigTest extends \PHPUnit\Framework\TestCase
{
    public function testLoadExistsFile()
    {
        $config = new \PmsOne\Config(__DIR__ . '/../assets/config.json');

        $this->assertEquals('json', $config->fileExtension);
        $this->assertEquals($config->default, $config->config);
    }

    /**
     * @expectedException Exception
     */
    public function testLoadNotExitsFile()
    {
        $config = new \PmsOne\Config('no file');
    }

    public function testConfigAdd()
    {
        $config = new \PmsOne\Config(__DIR__ . '/../assets/config.json');

        $config->add('name2', 'added');

        $this->assertEquals(['name' => 'success', 'name2' => 'added'], $config->config);

        $this->assertNotEquals($config->config, $config->default);
    }

    public function testConfigAddArray()
    {
        $config = new \PmsOne\Config(__DIR__ . '/../assets/config.json');

        $config->addArray([
            'name2' => 'newAdded',
            'name3' => 'newAdded2'
        ]);

        $this->assertEquals([
            'name' => 'success',
            'name2' => 'newAdded',
            'name3' => 'newAdded2'
        ], $config->config);

        $this->assertNotEquals($config->config, $config->default);
    }

    public function testConfigData()
    {
        $config = new \PmsOne\Config(__DIR__ . '/../assets/config.json');

        $this->assertEquals(['name' => 'success'], $config->config);
    }
}