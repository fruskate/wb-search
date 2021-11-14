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

    protected $fillable = ['name', 'popularity', 'shows', 'clicks', 'buys', 'ctr'];


    public function scopeSearchInName($query, array $searchQueries): void
    {
        $searchString = '*';
        $q = 1;
        foreach($searchQueries as $searchQuery) {
            $searchQuery = $this->convertLang($searchQuery);
            if ($q == 1) {
                $searchString .= $searchQuery;
            } else {
                $searchString .= '*+'.$searchQuery;
            }
            $q++;
        }
        $searchString .= '*';

        //array_walk($searchQueries, [$this, 'convertLang']);
        //$searchString = '*' . implode('*+', $searchQueries) .'*';

        $query->selectRaw("*, MATCH(name)AGAINST('".$searchString."')")
            ->whereRaw("MATCH(name)AGAINST('".$searchString."' IN BOOLEAN MODE)");


        /* foreach($searchQueries as $searchQuery) {
            $searchQuery = $this->convertLang($searchQuery);
            $query->where('name', 'like', '%'. $searchQuery .'%');
        }
        $query->where('name', '!=', implode(' ', $searchQueries)); */
    }

    // Конвертим в верную раскладку
    public function convertLang($text)
    {
        $corrector = new LangCorrect();
        return $corrector->parse($text, 2);
    }
}
