<?php namespace Frukt\Searcher\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Bests extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Frukt.Searcher', 'main-menu-item', 'side-menu-item5');
    }
}
