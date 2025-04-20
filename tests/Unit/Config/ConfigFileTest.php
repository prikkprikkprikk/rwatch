<?php

declare(strict_types=1);

use RWatch\Config\ConfigFile;
use RWatch\Config\ConfigFilePath;

beforeEach(function() {
    $this->testFilename = createEmptyTestConfigFile();
});

afterEach(function() {
    deleteTestConfigFile($this->testFilename);
});

it('can read a ConfigFilePath', function () {
    $configFilePath = new ConfigFilePath($this->testFilename);
    $configFile = new ConfigFile($configFilePath);
    expect($configFile->getContents())->toBe(json_encode([]));
});
