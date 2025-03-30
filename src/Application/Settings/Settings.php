<?php

declare(strict_types=1);

namespace App\Application\Settings;

class Settings implements SettingsInterface
{
    public const TOOL_NAME = 'TOOL_NAME';
    public const TOOL_URL = 'TOOL_URL';
    public const SCOPES = 'SCOPES';

    private array $settings;

    public function __construct(array $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @return mixed
     */
    public function get(string $key = '')
    {
        return (empty($key)) ? $this->settings : $this->settings[$key];
    }

    public function getToolName(): string
    {
        return $this->settings[self::TOOL_NAME];
    }

    public function getToolUrl(): string
    {
        return $this->settings[self::TOOL_URL];
    }

    /**
     * @return string[] 
     */
    public function getScopes(): array
    {
        return $this->settings[self::SCOPES];
    }
}
