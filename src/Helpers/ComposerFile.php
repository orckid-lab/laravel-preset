<?php

namespace OrckidLab\LaravelPreset\Helpers;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

/**
 * Class ComposerFile
 * @package OrckidLab\LaravelPreset\Helpers\Composer
 */
class ComposerFile
{
    /**
     * @var Command
     */
    protected $command;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var object
     */
    protected $content;

    /**
     * ComposerFile constructor.
     * @param Command $command
     */
    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    /**
     * Instantiate the class.
     *
     * @param Command $command
     * @return ComposerFile
     */
    public static function init(Command $command)
    {
        return (new static($command))->open();
    }

    /**
     * Open the composer.json file.
     *
     * @return $this
     */
    public function open()
    {
        $this->command->warn('Updating composer.json...');

        $this->path = base_path('composer.json');

        $this->content = (array)json_decode(file_get_contents($this->path));

        return $this;
    }

    /**
     * Update the file.
     */
    public function update()
    {
        File::put($this->path, json_encode((object)$this->content, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        $this->command->info('composer.json updated.');
    }

    public function addDependencies(array $array)
    {
        $dependencies = (array)$this->content['require'];

        $this->content['require'] = array_merge($dependencies, $array);

        return $this;
    }

    public function add(array $array)
    {
        $this->content = array_merge($this->content, [
            'repositories' => [
                [
                    'type' => 'path',
                    'url' => './nova',
                ],
            ],
        ]);

        return $this;
    }
}
