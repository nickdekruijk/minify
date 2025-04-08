<?php

namespace NickDeKruijk\Minify;

use Exception;
use JShrink\Minifier;
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;

class Minify
{
    private static function minifyRequired(array $files, string $output, array $paths)
    {
        if (!file_exists(public_path($output))) {
            return true;
        }
        if (in_array(app()->environment(), config('minify.skip_environment'))) {
            return false;
        }
        $filemtime = filemtime(public_path($output));
        foreach ((array) $files as $file) {
            if (!self::isUrl($file) && filemtime(self::findFile($file, $paths)) > $filemtime) {
                return true;
            }
        }
        return false;
    }

    private static function isUrl(string $file)
    {
        return substr($file, 0, 8) === "https://";
    }

    private static function findFile(string $file, array $paths)
    {
        if (self::isUrl($file) || file_exists($file)) {
            return $file;
        } else {
            foreach ($paths as $path) {
                $filename = rtrim($path, '/') . '/' . $file;
                if (file_exists($filename)) {
                    return $filename;
                }
            }
            throw new Exception($file . ' not found within importPaths');
        }
    }

    private static function findCssFile(string $file)
    {
        return self::findFile($file, config('minify.scssImportPaths'));
    }

    private static function findJsFile(string $file)
    {
        return self::findFile($file, config('minify.jsImportPaths'));
    }

    private static function outputFile(string $file, string $content)
    {
        $directory = pathinfo($file)['dirname'];
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($file, $content);
    }

    public static function stylesheet(array $files = ['../resources/sass/app.scss'], null|string $output = null)
    {
        $output = $output ?: config('minify.output.css');

        if (self::minifyRequired($files, $output, config('minify.scssImportPaths'))) {
            $compiler = new Compiler();
            $compiler->setImportPaths(config('minify.scssImportPaths'));

            if (config('minify.compressed')) {
                $compiler->setOutputStyle(OutputStyle::COMPRESSED);
            }

            $scss = '';
            foreach ($files as $file) {
                $scss .= file_get_contents(self::findCssFile($file));
            }
            $css = $compiler->compileString($scss)->getCss();

            self::outputFile(public_path($output), $css);
        }
        return '<link rel="stylesheet" type="text/css" href="' . asset($output) . '?' . filemtime(public_path($output)) . '">';
    }

    public static function javascript(array $files = ['../resources/js/app.js'], null|string $output = null)
    {
        $output = $output ?: config('minify.output.js');

        if (self::minifyRequired($files, $output, config('minify.jsImportPaths'))) {
            $js = '';
            foreach ((array) $files as $file) {
                $js .= file_get_contents(self::findJsFile($file)) . PHP_EOL;
            }
            $js = Minifier::minify($js, ['flaggedComments' => config('minify.jsFlaggedComments')]);
            self::outputFile(public_path($output), $js);
        }
        return '<script src="' . asset($output) . '?' . filemtime(public_path($output)) . '"></script>';
    }
}
