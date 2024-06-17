<?php

namespace App\Utils\Excel\Txt;

class Conversion
{
    protected String $value = "";

    protected function setValue($string): self {
        echo $string, "\n";
        $this->value = $string;
        return $this;
    }

    public function getValue(): String
    {
        return $this->value;
    }

    public function __construct(String $value)
    {
        $this->setValue($value);
    }
    public function replace(String $search, String $replacement): self
    {
        return $this->setValue(
            str_replace($search, $replacement, $this->value)
        );
    }

    public function replacePreg($pattern, $replacement): self
    {
        return $this->setValue(
            preg_replace($pattern, $replacement, $this->value)
        );
    }

    public function or(String ...$values): self
    {
        return $this->setValue('');
    }

    public function date(): self
    {
        return $this->setValue('');
    }

    public function splice(int $start, int $end = null): self
    {
        if($end === null) {
            $end = strlen($this->value);
        }
        return $this->setValue(
            substr($this->value, $start, $end)
        );
    }

    public function toFixed(String $precision = '2'): self
    {
        return $this->setValue(
            number_format($this->value, (int) $precision)
        );
    }

}
