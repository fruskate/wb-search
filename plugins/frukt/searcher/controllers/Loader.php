<?php namespace Frukt\Searcher\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Loader Backend Controller
 */
class Loader extends Controller
{
    /**
     * __construct the controller
     */
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Frukt.Searcher', 'main-menu-item', 'side-menu-item');
    }

    public function index()
    {

    }

    public function onLoadPopular()
    {

        $row = 1;
        if (($handle = fopen(plugins_path('frukt/searcher/assets/datasets/search_history.csv'), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                trace_log("$num полей в строке $row:");
                if ($row == 3) {
                    dd($row);
                }

                    for ($c=0; $c < $num; $c++) {
                        trace_log($data[$c]);
                    }


                $row++;
            }
            fclose($handle);
        }
    }
}
