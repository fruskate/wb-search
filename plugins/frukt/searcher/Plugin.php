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

    public function register()
    {
        $this->registerConsoleCommand('load:popularity', 'Frukt\Searcher\Console\LoadPopularity');
        $this->registerConsoleCommand('load:history', 'Frukt\Searcher\Console\LoadHistory');
    }

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Настройки',
                'description' => 'Управление настройками поиска.',
                'category'    => 'Search',
                'icon'        => 'icon-cog',
                'class'       => 'Frukt\Searcher\Models\Settings',
                'order'       => 500,
                'keywords'    => 'search',
                'permissions' => []
            ]
        ];
    }
}
