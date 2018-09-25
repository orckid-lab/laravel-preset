<?php

namespace OrckidLab\LaravelPreset;


use Chumper\Zipper\Zipper;
use Illuminate\Console\Command;
use Illuminate\Foundation\Console\Presets\Preset as LaravelPreset;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use OrckidLab\LaravelPreset\Helpers\ComposerFile;

/**
 * Class Preset
 * @package OrckidLab\LaravelPreset
 */
class Preset extends LaravelPreset
{
    /**
     * @var Command
     */
    protected $command;

    /**
     * @var ComposerFile
     */
    protected $composer;

    /**
     * Preset constructor.
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;

        $this->composer = ComposerFile::init($command);
    }

    /**
     * @param Command $command
     */
    public static function install(Command $command)
    {
        $command->comment('Preparing Orckid lab scaffolding...');

        return (new static($command))->handle();
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

    /**
     * @param array $scripts
     * @return array
     */
    protected static function updateScriptsArray(array $scripts)
    {
        return [
                "hot" => "cross-env NODE_ENV=development node_modules/webpack-dev-server/bin/webpack-dev-server.js --inline --hot --https --config=node_modules/laravel-mix/setup/webpack.config.js",
                "lint" => "eslint resources/assets/js/** --fix",
            ] + $scripts;
    }

    /**
     * Configure Eslint.
     */
    public static function setEslintConfig()
    {
        copy(__DIR__ . '/../stubs/front/.eslintrc.json', base_path('.eslintrc.json'));
    }

    /**
     * Update webpack configuration file.
     */
    public static function updateWebpackConfiguration()
    {
        copy(__DIR__ . '/../stubs/front/webpack.mix.js', base_path('webpack.mix.js'));
    }

    /**
     * Remove default Sass folder.
     */
    public static function removeSassFolder()
    {
        File::deleteDirectory(resource_path('assets/sass'));
    }

    /**
     * Update Less folder.
     */
    public static function setLessFolder()
    {
        File::copyDirectory(__DIR__ . '/../stubs/front/less', resource_path('assets/less'));
    }

    /**
     * Update JavaScript file.
     */
    public static function updateJavascript()
    {
        File::deleteDirectory(resource_path('assets/js'));

        File::copyDirectory(__DIR__ . '/../stubs/front/js', resource_path('assets/js'));
    }

    /**
     * Update .gitignore file.
     */
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

    /**
     * Handle configuring Orckid preset.
     */
    protected function handle()
    {
        $scaffold = $this->command->confirm('Do you want to proceed with the scaffolding?');

        if ($scaffold) {
            $this->updatePackages();
            $this->updateScripts();
            $this->setEslintConfig();
            $this->updateWebpackConfiguration();
            $this->removeSassFolder();
            $this->setLessFolder();
            $this->updateJavascript();
            $this->updateGitIgnore();
            $this->scaffoldComplete();
        }

//        $this->installNova();


        // configure webpack for HMR
        // Amend TestCase
        // Setup PHPUnit config
        // make auth
        // install csv
        // install guzzle
        // install collective
        // php artisan preset orckid-lab
    }

    /**
     * Notify scaffold completed.
     */
    protected function scaffoldComplete()
    {
        $this->command->info('Orckid Lab scaffolding installed successfully.');

        $this->command->comment('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
    }

    /**
     * Install Nova license.
     *
     * @throws \Exception
     */
    protected function installNova()
    {
        $novaPath = base_path('nova');

        $this->command->warn('To install nova, you need to ensure the zip file is ready at ' . $novaPath);

        $nova = $this->command->confirm('Do you want to install nova?');

        if (!$nova) {
            return;
        }

        $this->extractNova($novaPath);

        $this->addNovaDependency();

        $this->addRolesAndPermissionsDependency();

        $this->setDatabaseStubs();

        $this->command->warn('Copying Application and Nova stubs...');

        $appStubs = [
            'User',
            'Role',
            'Permission',
        ];

        foreach ($appStubs as $stub) {
            File::delete(base_path("app/$stub.php"));

            File::delete(base_path("app/Nova/$stub.php"));

            File::copy(__DIR__ . "/../stubs/back-end/app/$stub.stub", base_path("app/$stub.php"));

            File::copy(__DIR__ . "/../stubs/back-end/app/Nova/$stub.stub", base_path("app/Nova/$stub.php"));
        }

        $this->command->info('Copy completed.');

        $this->command->info('Run composer update && php artisan migrate --seed');
    }

    /**
     * Extract Nova license.
     *
     * @param $novaPath
     * @throws \Exception
     */
    protected function extractNova($novaPath)
    {
        $zipper = new Zipper;

        $licensePath = File::files($novaPath)[0];

        $this->command->warn('Extracting...');

        $content = $zipper->make($licensePath)->listFiles();

        $root = preg_replace('/^(laravel.*?)\/.*/', '$1', $content[0]);

        $zipper->folder($root)->extractTo('nova');

        $this->command->info('Extract completed');
    }

    /**
     * Add laravel/nova to project dependencies.
     */
    protected function addNovaDependency()
    {
        $this->composer->addDependencies([
            'laravel/nova' => '*',
        ]);

        $this->composer->add([
            'repositories' => [
                'type' => 'path',
                'url' => './nova',
            ],
        ]);

        $this->composer->update();

        $this->command->warn('Updating dependencies...');

        exec('composer update');

        $this->command->warn('Running nova:install.');

        $this->command->info(exec('php artisan nova:install'));

        $this->command->info('Nova installation complete.');
    }

    /**
     * Update database stubs.
     */
    protected function setDatabaseStubs(): void
    {
        $this->command->warn('Copying database stubs...');

        $paths = [
            'database/factories',
            'database/migrations',
            'database/seeds',
        ];

        foreach ($paths as $path) {
            File::deleteDirectory(base_path($path));

            File::copyDirectory(__DIR__ . '/../stubs/back-end/' . $path, base_path($path));

            $files = File::files(base_path($path));

            foreach ($files as $file) {
                File::move($file, str_replace('.stub', '.php', $file));
            }

            $this->command->info("$path updated.");
        }
    }

    /**
     * Add Roles and Permissions dependency to project.
     */
    protected function addRolesAndPermissionsDependency(): void
    {
        $this->command->warn('Adding role and permission module to dependencies...');

        $this->composer->addDependencies([
            'spatie/laravel-permission' => '^2.16',
        ]);

        $this->composer->update();
    }
}
