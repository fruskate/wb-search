<?php namespace Frukt\Searcher\Models;

use Model;

/**
 * Model
 */
class Best extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'frukt_searcher_bests';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
