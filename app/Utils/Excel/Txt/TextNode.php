<?php

namespace App\Utils\Excel\Txt;

class TextNode extends Node
{
    public function stringify(): string
    {
        return $this->context;
    }
}
