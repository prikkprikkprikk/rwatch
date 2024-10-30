<?php

use Dwatch\Config\Config;

beforeEach(function ()
{
    $this->configDir = '/tmp/dwatch';
    $this->configFile = 'config.json';
    $this->fullConfigFilePath = $this->configDir . '/' . $this->configFile;
});

afterEach(function ()
{
    if (file_exists($this->fullConfigFilePath)) {
        unlink($this->fullConfigFilePath);
    }

    if (file_exists($this->configDir)) {
        rmdir($this->configDir);
    }
});

it('creates the config dir and file if they do not exist', function ()
{
    $config = new Config($this->configDir, $this->configFile);

    expect(file_exists($this->configDir))->toBeTrue()
        ->and(file_exists($this->fullConfigFilePath))->toBeTrue()
        ->and($config->shouldPromptUser())->toBeTrue();
});

it('creates the config file if it does not exist', function ()
{
    mkdir($this->configDir);

    $config = new Config($this->configDir, $this->configFile);

    expect(file_exists($this->fullConfigFilePath))->toBeTrue()
        ->and($config->shouldPromptUser())->toBeTrue();
});
