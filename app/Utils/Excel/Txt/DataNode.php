<?php

namespace App\Utils\Excel\Txt;

use Exception;

class DataNode extends Node
{
    /**
     * @var string eg "{A4@sheetName|toFixed(2)|replace('0', '-')}"
     */
    protected const FUNCTION_REGEX = '/^(?<function>[a-zA-Z_]+)(\((?<arguments>[^\)]*)\))?$/';
    protected const NUMBER_REGEX = '/^[0-9](?:\.[0-9]+)?+$/';
    protected const STRING_REGEX = '/^(["\']).*\1/';
    protected const DATA_ADDR_REGEX = '/^#[a-zA-Z]+@?/';
    protected String $context = '';
    protected Conversion $conversion;
    public function __construct(String $context, ExcelData $data)
    {
        parent::__construct($context, $data);
        $this->parse();
    }

    protected function parse(): void
    {
        $contextArr = explode('|',$this->context);
        $this->conversion = new Conversion(
            $this->data->getValue(
                ...explode('@', $contextArr[0])
            )
        );
        for($i = 1; $i < count($contextArr); $i++) {
            $this->execFunction($contextArr[$i]);
        }
    }

    /**
     * @throws Exception
     */
    protected function execFunction(string $content): void
    {
        $matched = [];
        preg_match(self::FUNCTION_REGEX, $content, $matched);
        if(!$matched) {
            throw new Exception('Invalid function');
        }
        $this->conversion->{$matched['function']}(
            ...$this->parseArguments(
            $matched['arguments'] ?? ''
            )
        );
    }

    /**
     * @throws Exception
     */
    protected function parseArguments(string $context = ''): array
    {
        if($context === '') {
            return [];
        }
        $args = [];
        foreach (explode(',', $context) as $arg) {
            $arg = trim($arg);
            if(
                preg_match(self::NUMBER_REGEX, $arg) ||
                preg_match(self::STRING_REGEX, $arg)
            ) {
                $args[] = json_decode($arg);
            } elseif(preg_match(self::DATA_ADDR_REGEX, $arg)) {
                $args[] = $this->data->getValue(
                    ...explode('@', substr($arg, 1))
                );
            } else {
                throw new Exception('Invalid argument');
            }
        }
        return $args;
    }

    public function stringify(): string
    {
        return $this->conversion->getValue();
    }
}
