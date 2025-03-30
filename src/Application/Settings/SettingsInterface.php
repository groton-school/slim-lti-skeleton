<?php

declare(strict_types=1);

namespace App\Application\Settings;

use GrotonSchool\Slim\LTI;

interface SettingsInterface extends LTI\SettingsInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key = '');
}
