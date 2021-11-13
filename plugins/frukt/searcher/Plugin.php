<?php namespace Frukt\Searcher;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'Frukt\Searcher\Components\SearchLine' => 'searchLine'
        ];
    }

    public function registerSettings()
    {
    }

    public function register()
    {
        $this->registerConsoleCommand('load:popularity', 'Frukt\Searcher\Console\LoadPopularity');
        $this->registerConsoleCommand('load:history', 'Frukt\Searcher\Console\LoadHistory');
    }
}
