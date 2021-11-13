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
            'name'        => 'Поисковая строка',
            'description' => 'Найдём то, что вам необходимо...'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {


        if ($this->page->id == 'index') {
            $this->addJs('assets/js/app.js');
        } elseif ($this->page->id == 'search') {
            $this->page['query'] = $this->param('query');
        }
    }

    public function onSearch()
    {
        // Определяем брендовый ли запрос
        try {
            if (mb_strlen(post('s')) > 0) {
                $query = trim(post('s'));

                $history = $this->searcher($query);

                if ($history->count() < 1) {
                    $history = $this->searcher(mb_substr($query, 0, -1));
                }

                return [
                    '#result' => $this->renderPartial($this . "::make_prompt", [
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

    public function onBuy()
    {
        return [
            '#wellDone' => '<div class="alert alert-info">Отлично. С покупкой!<br><a href="/">Вернуться к поиску</a></div>'
        ];
    }

    public function onMakeChoice()
    {
        $query = post('choice_name');

        $populars = Popular::whereIn('id', post('strings'))->get();

        foreach ($populars as $item) {
            $newShows = $item->shows + 1;
            $item->shows = $newShows;
            $item->ctr = round(($item->clicks / $newShows) * 100, 2);
            $item->save();
        }

        $popular = Popular::where('name', $query)->firstOrCreate(['name' => $query, 'popularity' => 1]);
        $newClicks = $popular->clicks + 1;
        $popular->ctr = round(($newClicks / $popular->shows) * 100, 2);
        $popular->clicks = $newClicks;
        $popular->save();


        return \Redirect::to('/search/'.$query);
    }


    // Разделяем на слова
    public function devideWords($text)
    {
        return explode(' ', $text);
    }

    public function searcher($query)
    {
        $spacePosition = strpos($query, ' ');

        $searchQuery = $spacePosition === true ? $this->devideWords($query) : [$query];
        return Popular::searchInName($searchQuery)
            ->orderBy('popularity', 'desc')
            ->take(12)
            ->get();
    }
}