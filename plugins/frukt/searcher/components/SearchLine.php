<?php namespace Frukt\Searcher\Components;

use Cms\Classes\ComponentBase;
use Frukt\Searcher\Classes\LangCorrect;
use Frukt\Searcher\Controllers\History;
use Frukt\Searcher\Models\Item;
use Frukt\Searcher\Models\Popular;

/**
 * SearchLine Component
 */
class SearchLine extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Поисковая строка',
            'description' => 'Найдём то, что вам необходимо...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->addJs('assets/js/app.js');
    }

    public function onSearch()
    {
        // Определяем брендовый ли запрос
        try {
            if (mb_strlen(post('s')) > 0) {
                $query = trim(post('s'));

                $spacePosition = strpos($query, ' ');

                if ($spacePosition === false) {
                    // работаем с одним поисковым словом
                    $query = $this->convertLang($query);
                    $history = Popular::where('name', 'like', '%'.$query.'%')->orderBy('popularity', 'desc')->take('12')->get();
                } else {
                    $queries = $this->devideWords($query);

                    $q = 1;
                    foreach ($queries as $query) {
                        $query = $this->convertLang($query);
                        if ($q == 1) {
                            $history = Popular::where('name', 'like', '%'.$query.'%');
                        } else {
                            $history = $history->where('name', 'like', '%'.$query.'%');
                        }
                        $q++;
                    }
                    $history = $history->orderBy('popularity', 'desc')->take('12')->get();
                }



                return [
                    '#result' => $this->renderPartial($this."::make_prompt", [
                        'history' => $history
                    ]),
                ];
            } else {
                return [
                    '#result' => '',
                ];
            }
        } catch (\Exception $exception) {

        }
    }

    // Конвертим в верную раскладку
    public function convertLang($text)
    {
        $corrector = new LangCorrect();
        return $corrector->parse($text, 2);
    }

    // Разделяем на слова
    public function devideWords($text)
    {
        return explode(' ', $text);
    }
}
