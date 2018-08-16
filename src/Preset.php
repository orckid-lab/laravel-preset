<?php

namespace OrckidLab\LaravelPreset;


use Illuminate\Foundation\Console\Presets\Preset as LaravelPreset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

/**
 * Class Preset
 * @package OrckidLab\LaravelPreset
 */
class Preset extends LaravelPreset
{
    public static function install()
    {
        self::updatePackages();
        self::updateScripts();
        self::setEslintConfig();
        self::updateWebpackConfiguration();
        self::removeSassFolder();
        self::setLessFolder();
        self::updateJavascript();
        self::updateGitIgnore();

        // configure webpack for HMR
        // Amend TestCase
        // Setup PHPUnit config
        // make auth
        // install spatie
        // install csv
        // install guzzle
        // install collective
        // php artisan preset orckid-lab

    }

    /**
     * Update the given package array.
     *
     * @param  array $packages
     * @return array
     */
    protected static function updatePackageArray(array $packages)
    {
        return [
                // commented browser sync to let npm-run-watch detect on its own which version is stable for watch/hot
                //                "browser-sync" => "^2.23.6",
                //                "browser-sync-webpack-plugin" => "^2.2.2",
                "dotenv" => "latest",
                "eslint" => "^4.18.1",
                "eslint-friendly-formatter" => "^3.0.0",
                "eslint-loader" => "^1.9.0",
                "eslint-plugin-html" => "^4.0.1",
                "eslint-plugin-import" => "^2.9.0",
                "eslint-plugin-json" => "^1.2.0",
                "core-js" => "^2.5.3",
                "jquery" => "^3.3.1",
                "less" => "^3.8.1",
                "less-loader" => "^4.0.6",
                "standard" => "^10.0.3",
                "keen-ui" => "^1.0.1",
                "vue-flickity" => "^1.0.9",
                "flickity-bg-lazyload" => "^1.0.0",
                "flickity-fullscreen" => "^1.1.0",
                "vue-scrollto" => "^2.9.0",
                "animate.css" => "^3.6.1",
                "vue-helpers" => "git@bitbucket.org:orckidlab/vue-helpers.git",
                "frontend-less" => "git@bitbucket.org:orckidlab/frontend-less.git"
            ] + Arr::except($packages, [
                'lodash',
                'popper.js',
            ]);
    }

    /**
     * Update package scripts
     */
    protected static function updateScripts()
    {
        if (!file_exists(base_path('package.json'))) {
            return;
        }

        $configurationKey = 'scripts';

        $packages = json_decode(file_get_contents(base_path('package.json')), true);

        $packages[$configurationKey] = static::updateScriptsArray(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : []
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            base_path('package.json'),
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    protected static function updateScriptsArray(array $scripts)
    {
        return [
                "hot" => "cross-env NODE_ENV=development node_modules/webpack-dev-server/bin/webpack-dev-server.js --inline --hot --https --config=node_modules/laravel-mix/setup/webpack.config.js",
                "lint" => "eslint resources/assets/js/** --fix",
            ] + $scripts;
    }

    public static function setEslintConfig()
    {
        copy(__DIR__ . '/stubs/.eslintrc.json', base_path('.eslintrc.json'));
    }

    public static function updateWebpackConfiguration()
    {
        copy(__DIR__ . '/stubs/webpack.mix.js', base_path('webpack.mix.js'));
    }

    public static function removeSassFolder()
    {
        File::deleteDirectory(resource_path('assets/sass'));
    }

    public static function setLessFolder()
    {
        File::copyDirectory(__DIR__ . '/stubs/less', resource_path('assets/less'));
    }

    public static function updateJavascript()
    {
        File::deleteDirectory(resource_path('assets/js'));

        File::copyDirectory(__DIR__ . '/stubs/js', resource_path('assets/js'));
    }

    public static function updateGitIgnore()
    {
        $path = base_path('.gitignore');

        $file = File::get($path);

        $excludes = array_filter(explode("\n", $file));

        $toExclude = [
            'public/js',
            'public/css',
            'public/mix-manifest.json',
            'composer.lock',
            'package-lock.json'
        ];

        foreach ($toExclude as $exclude) {
            if (!in_array($exclude, $excludes)) {
                $excludes[] = $exclude;
            }
        }

        File::put($path, join("\n", $excludes));
    }
}
