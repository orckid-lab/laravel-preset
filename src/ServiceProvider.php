<?php

namespace OrckidLab\LaravelPreset;


use Illuminate\Console\Command;
use Illuminate\Foundation\Console\PresetCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        PresetCommand::macro('orckid-lab', function(Command $command){
            Preset::install();

            $command->info('Orckid Lab scaffolding installed successfully.');
            $command->comment('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
        });
    }
}
