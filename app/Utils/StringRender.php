<?php
namespace App\Utils;

use App\Exceptions\ErrorException;
use Exception;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\PhpEngine;

class StringRender
{
    /**
     * @var BladeCompiler
     */
    protected $compiler;

    /**
     * StringCompilerEngine constructor.
     * @param BladeCompiler $compiler
     */
    public function __construct(BladeCompiler $compiler)
    {
        $this->compiler = $compiler;
    }

    public function renderString($__tpl, $__data = [])
    {
        $__compiled = $this->compiler->compileString($__tpl);
//        $obLevel = ob_get_level();
        ob_start();
        extract($__data, EXTR_SKIP);
        try {
            $replaced = str_replace('-&gt;', '->', $__compiled);
            $replaced = str_replace('<u>', '', $replaced);
            $replaced = str_replace('</u>', '', $replaced);
            $replaced = str_replace('\\', '', $replaced);
            eval('?> ' . $replaced);
        } catch (Exception $e) {
            throw new ErrorException($e->getMessage());
        }

        return ltrim(ob_get_clean());
    }

}
