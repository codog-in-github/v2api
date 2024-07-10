<?php
namespace App\Utils\Excel\Txt;

class Conversion
{
    protected string $value = "";

    protected function setValue($string): self {
        // echo $string, "\n"; // Consider removing this line
        $this->value = $string;
        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __construct(string $value)
    {
        $this->setValue($value);
    }

    public function replace(string $search, string $replacement): self
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

    public function or(string ...$values): self
    {
        // todo Implementation needed
        return $this->setValue('');
    }

    public function date(): self
    {
        // todo Implementation needed
        return $this->setValue('');
    }

    public function splice(int $start, int $end = null): self
    {
        if($end === null) {
            $end = strlen($this->value) - $start;
        }
        return $this->setValue(
            substr($this->value, $start, $end)
        );
    }

    public function toFixed(string $precision = '2'): self
    {
        return $this->setValue(
            number_format($this->value, (int) $precision)
        );
    }
}
