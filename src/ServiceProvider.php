<?php

namespace OrckidLab\LaravelPreset;


use Illuminate\Console\Command;
use Illuminate\Foundation\Console\PresetCommand;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        PresetCommand::macro('orckid-lab', function(Command $command){
            Preset::install($command);
        });
    }
}
