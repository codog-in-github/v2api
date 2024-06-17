<?php

namespace App\Utils\Excel\Txt;

abstract class Node
{
    protected string $context = '';
    protected ExcelData $data;

    public function __construct(string $string, ExcelData $data)
    {
        $this->context = $string;
        $this->data = $data;
    }
    abstract public function stringify(): string;
}
