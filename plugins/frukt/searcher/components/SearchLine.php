<?php namespace Frukt\Searcher\Components;

use Cassandra\Set;
use Cms\Classes\ComponentBase;
use Frukt\Searcher\Classes\LangCorrect;
use Frukt\Searcher\Controllers\History;
use Frukt\Searcher\Models\Brand;
use Frukt\Searcher\Models\Item;
use Frukt\Searcher\Models\Popular;
use Frukt\Searcher\Models\Settings;

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
        $showType = Settings::get('show_type', 1);

        if ($this->page->id == 'index') {
            if ($showType == 1) {
                $this->addJs('assets/js/app.js');
            }
        } elseif ($this->page->id == 'search') {
            $this->page['query'] = $this->param('query');
        }
    }

    public function onSearch()
    {
        $showType = Settings::get('show_type', 1);
        // Определяем брендовый ли запрос
        try {
            if (mb_strlen(post('s')) > 0) {
                $query = trim(post('s'));

                $brand = $this->brander($query);
                $history = $this->searcher($query);

                if ($history->count() < 1) {
                    $history = $this->searcher(mb_substr($query, 0, -1));
                }
                trace_log($history);

                return [
                    '#result' => $this->renderPartial($this . "::make_prompt_type".$showType, [
                        'history' => $history,
                    ]),
                    '#brand' => $this->renderPartial($this ."::make_brand", [
                        'brand' => $brand
                    ])
                ];
            } else {
                return [
                    '#result' => '',
                    '#brand' => '',
                ];
            }
        } catch (\Exception $exception) {

        }
    }

    public function onMakeButtonSearch()
    {
        $query = trim(post('s'));
        $popular = Popular::where('name', $query)->first();

        if (!$popular) {
            Popular::create([
                'name' => $query,
                'popularity' => 0,
                'shows' => 1,
                'clicks' => 0,
                'buys' => 0,
                'ctr' => 0
            ]);
        } else {
            $popular->shows = $popular->shows + 1;
            $popular->save();
        }

        return \Redirect::to('/search/'.$query);
    }

    public function onBuy()
    {
        $query = $this->param('query');
        $popular = Popular::where('name', $query)->first();
        if ($popular) {
            $newBuys = $popular->buys + 1;
            $clicksCtr = ($popular->shows > 0)? round(($popular->clicks / $popular->shows) * 100, 2) : 0;
            $buysCtr = ($popular->shows > 0)? round(($newBuys / $popular->shows) * 100, 2) : 0;
            $popular->ctr = $clicksCtr + $buysCtr;
            $popular->buys = $newBuys;
            $popular->save();
        }

        return [
            '#wellDone' => '<div class="alert alert-info">Отлично. С покупкой!<br><a href="/">Вернуться к поиску</a></div>'
        ];
    }

    public function onMakeChoice()
    {
        $query = post('choice_name');

        $populars = \Db::table('frukt_searcher_populars')->whereIn('id', post('strings'))->get();

        foreach ($populars as $item) {
            $newShows = $item->shows + 1;

            \Db::table('frukt_searcher_populars')->where('id', $item->id)->update([
                'ctr' => round(($item->clicks / $newShows) * 100, 2) + round(($item->buys / $newShows) * 100, 2),
                'shows' => $newShows,
            ]);
        }

        $popular = \Db::table('frukt_searcher_populars')->where('name', $query)->first();

        //Popular::where('name', $query)->first();
        if ($popular) {
            $newClicks = $popular->clicks + 1;
            $clickCtr = ($popular->shows > 0)? round(($newClicks / $popular->shows) * 100, 2) : 0;
            $buyCtr = ($popular->shows > 0)? round(($popular->buys / $popular->shows) * 100, 2) : 0;

            //trace_log($popular->id, $newClicks, $clickCtr, $buyCtr, $clickCtr + $buyCtr);

            \Db::table('frukt_searcher_populars')->where('id', $popular->id)->update([
                'ctr' => $clickCtr + $buyCtr,
                'clicks' => $newClicks,
            ]);
        }



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
        $searchQuery = $spacePosition ? $this->devideWords($query) : [$query];
        $searchType = Settings::get('search_type', 1);

        if (in_array($searchType, [1,2])) {
            $populars = Popular::searchInName($searchQuery);
        } elseif ($searchType == 3) {
            $populars = Item::searchInUQ($searchQuery);
        }




        if ($searchType == 1) {
            $populars = $populars->orderBy('popularity', 'desc');
        } elseif ($searchType == 2) {
            $populars = $populars->orderBy('ctr', 'desc');
        } elseif ($searchType == 3) {
            // автоматом сортирует
        }
        return $populars
            ->limit(12)
            ->get();
    }

    private function brander($query)
    {
        trace_log('Попали');
        $spacePosition = strpos($query, ' ');

        $searchQuery = $spacePosition ? $this->devideWords($query) : [$query];

        trace_log($spacePosition, $searchQuery);

        $brand = Brand::where('name', $searchQuery[0])
            ->orWhere('name2', $searchQuery[0])
            ->orWhere('name3', $searchQuery[0])
            ->orWhere('name4', $searchQuery[0]);

        if (count($searchQuery) > 1) {
            $q = 1;
            foreach ($searchQuery as $item) {
                trace_log($item);
                if ($q > 1) {
                    $brand = $brand->orWhere('name', $item)
                        ->orWhere('name2', $item)
                        ->orWhere('name3', $item)
                        ->orWhere('name4', $item);
                }
                $q++;
            }
        }

        $brand = $brand->first();

        if ($brand) {
            trace_log($brand->name);
            return $brand->name;
        } else {
            return false;
        }
    }
}
