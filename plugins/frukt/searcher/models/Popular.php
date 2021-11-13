<?php namespace Frukt\Searcher\Models;

use Frukt\Searcher\Classes\LangCorrect;
use Model;

/**
 * Model
 */
class Popular extends Model
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
    public $table = 'frukt_searcher_populars';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $fillable = ['name', 'popularity', 'shows', 'clicks', 'ctr'];


    public function scopeSearchInName($query, array $searchQueries): void
    {
        foreach($searchQueries as $searchQuery) {
            $searchQuery = $this->convertLang($searchQuery);
            $query->where('name', 'like', '%'. $searchQuery .'%');
        }
        $query->where('name', '!=', $searchQueries[0]);
    }

    // Конвертим в верную раскладку
    public function convertLang($text)
    {
        $corrector = new LangCorrect();
        return $corrector->parse($text, 2);
    }
}
