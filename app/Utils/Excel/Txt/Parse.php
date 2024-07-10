<?php

namespace App\Utils\Excel\Txt;

use Exception;

class Parse
{
    /**
     * @var Node[]
     */
    protected array $nodes = [];

    protected ExcelData $data;
    protected string $template = '';

    /**
     * @throws Exception
     */
    public function __construct(string $template, array $data)
    {
        $this->data = new ExcelData(array_values($data), array_keys($data));
        $this->template = $template;
        $this->parseTemplate();
    }

    /**
     * @throws Exception
     */
    protected function parseTemplate(): void
    {
        [$head, $body] = $this->splitFirstLine();
        $activeSheetName = trim(explode('=', $head)[1]);
        $this->data->setActiveSheet($activeSheetName);
        $this->nodes = $this->createParseNode($body);
    }

    public function splitFirstLine(): array
    {
        $pos = strpos($this->template, "\n");
        if ($pos === false) {
            return [$this->template, ''];
        }
        return [
            substr($this->template, 0, $pos),
            substr($this->template, $pos + 1)
        ];
    }

    /**
     * @throws Exception
     */
    public function createParseNode(string $body): array
    {
        $nodes = [];
        $last = $body;
        while ($last) {
            $pos = strpos($last, '{');
            if ($pos === false) {
                $nodes[] = new TextNode($last, $this->data);
                break;
            }
            $endPos = strpos($last, '}');
            if ($endPos === false) {
                throw new Exception('Unclosed bracket');
            }
            $text = substr($last, 0, $pos);
            if ($text) {
                $nodes[] = new TextNode($text, $this->data);
            }
            $nodes[] = new DataNode(substr($last, $pos + 1, $endPos - $pos - 1), $this->data);
            $last = substr($last, $endPos + 1);
        }
        return $nodes;
    }

    public function output(): string
    {
        $out = '';
        foreach ($this->nodes as $node) {
            $out .= $node->stringify();
        }
        return $out;
    }
}
