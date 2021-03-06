<?php namespace Frukt\Searcher\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'frukt_searcher_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
