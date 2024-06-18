<?php

namespace App\Utils\I18n;

interface I18n
{
    public function getLang(string $key): string;
}
