<?php
declare(strict_types=1);

namespace Dwatch\Config;

class Config {

    private const DEFAULT_CONFIG_DIR = '~/.config/dwatch';
    private const DEFAULT_CONFIG_FILE = 'dwatch.php';

    private array $config = [
        'server' => '',
        'username' => '',
    ];

    public function __construct(
        protected string $configDir = self::DEFAULT_CONFIG_DIR,
        protected string $configFile = self::DEFAULT_CONFIG_FILE,
    ){
        $this->ensureConfigDirAndFileExist();
    }

    protected function ensureConfigDirAndFileExist(): void
    {
        if (!file_exists($this->configDir)) {
            mkdir($this->configDir, 0755, true);
        }
        if (!file_exists($this->fullConfigFilePath())) {
            // Save empty config values to new file
            $this->saveConfig();
        }
    }

    public function shouldPromptUser(): bool
    {
        return $this->config['server'] === '' || $this->config['username'] === '';
    }

    public function saveConfig(): void {
        file_put_contents(
            $this->fullConfigFilePath(),
            json_encode($this->config, JSON_PRETTY_PRINT)
        );
    }

    protected function fullConfigFilePath(): string
    {
        return $this->configDir . '/' . $this->configFile;
    }

    public function setServer(mixed $server): void {
        $this->config['server'] = $server;
    }

    public function setUsername(mixed $username): void {
        $this->config['username'] = $username;
    }

    public function getServer() {
        return $this->config['server'];
    }

    public function getUsername() {
        return $this->config['username'];
    }
}