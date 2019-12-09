<?php

namespace NickDeKruijk\Minify;

use ScssPhp\ScssPhp\Compiler;
use JShrink\Minifier;
use Exception;

class Minify
{
    private static function minifyRequired($files, $output, $paths)
    {
        if (in_array(app()->environment(), config('minify.skip_environment'))) {
            return false;
        }
        if (!file_exists(public_path($output))) {
            return true;
        }
        $filemtime = filemtime(public_path($output));
        foreach((array)$files as $file) {
            if (!self::isUrl($file) && filemtime(self::findFile($file, $paths)) > $filemtime) {
                return true;
            }
        }
        return false;
    }

    private static function isUrl($file)
    {
        return substr($file, 0, 8) === "https://";
    }

    private static function findFile($file, $paths)
    {
        if (self::isUrl($file) || file_exists($file)) {
            return $file;
        } else {
            foreach($paths as $path) {
                $filename = rtrim($path, '/') . '/' . $file;
                if (file_exists($filename)) {
                    return $filename;
                }
            }
            throw new Exception($file . ' not found within importPaths');
        }
    }

    private static function findCssFile($file)
    {
        return self::findFile($file, config('minify.scssImportPaths'));
    }

    private static function findJsFile($file)
    {
        return self::findFile($file, config('minify.jsImportPaths'));
    }

    private static function outputFile($file, $content)
    {
        $directory = pathinfo($file)['dirname'];
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        file_put_contents($file, $content);
    }

    public static function stylesheet($files = ['../resources/sass/app.scss'], $output = null)
    {
        $output = $output ?: config('minify.output.css');

        if (self::minifyRequired($files, $output, config('minify.scssImportPaths'))) {
            $scss = new Compiler();
            $scss->setImportPaths(config('minify.scssImportPaths'));
            $formatter = config('minify.scssFormatter');
            $scss->setFormatter(new $formatter);

            $css = '';
            foreach((array)$files as $file) {
                $css .= file_get_contents(self::findCssFile($file));
            }
            $css = $scss->compile($css);

            self::outputFile(public_path($output), $css);
        }
        return '<link rel="stylesheet" type="text/css" href="' . asset($output) . '?' . filemtime(public_path($output)) . '">';
    }

    public static function javascript($files = ['../resources/js/app.js'], $output = null)
    {
        $output = $output ?: config('minify.output.js');

        if (self::minifyRequired($files, $output, config('minify.jsImportPaths'))) {
            $js = '';
            foreach((array)$files as $file) {
                $js .= file_get_contents(self::findJsFile($file));
            }
            $js = Minifier::minify($js, ['flaggedComments' => config('minify.jsFlaggedComments')]);
            self::outputFile(public_path($output), $js);
        }
        return '<script src="' . asset($output) . '?' . filemtime(public_path($output)) . '"></script>';
    }
}
