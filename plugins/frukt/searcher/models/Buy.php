<?php namespace Frukt\Searcher\Models;

use Model;

/**
 * Model
 */
class Buy extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'frukt_searcher_buys';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
