<?php namespace Frukt\Searcher\Models;

use Frukt\Searcher\Classes\LangCorrect;
use Model;

/**
 * Model
 */
class Item extends Model
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
    public $table = 'frukt_searcher_queries';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];

    protected $fillable = ['wbuser_id', 'uq', 'cnt', 'locale', 'weekday', 'time'];

    public function scopeSearchInUQ($query, array $searchQueries): void
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

        $query->selectRaw("*, MATCH(uq)AGAINST('".$searchString."')")
            ->whereRaw("MATCH(uq)AGAINST('".$searchString."' IN BOOLEAN MODE)");

    }

    // Конвертим в верную раскладку
    public function convertLang($text)
    {
        $corrector = new LangCorrect();
        return $corrector->parse($text, 2);
    }
}
