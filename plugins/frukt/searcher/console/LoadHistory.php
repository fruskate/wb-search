<?php namespace Frukt\Searcher\Console;

use Frukt\Searcher\Classes\Import\CsvReader;
use Frukt\Searcher\Classes\Import\CsvReaderHelper;
use Frukt\Searcher\Classes\Import\TemporaryCollections;
use Frukt\Searcher\Models\Item;
use Illuminate\Console\Command;

/**
 *
 */
class LoadHistory extends Command
{
    use TemporaryCollections, CsvReaderHelper;

    /**
     * @var string The console command name.
     */
    protected $name = 'load:history';

    /**
     * @var string The console command description.
     */
    protected $description = 'Импорт истории запросов';

    /**
     * @var string
     */
    protected $path = 'datasets/search_history_tab.csv';

    /**
     *
     */
    public const CHUNK = 1000;

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->output->title('Импорт начался');
        $progressBar = $this->output->createProgressBar($this->getReader()->length());

        $i = 0;
        foreach ($this->getReader()->iterate() as $item) {
            $i++;
            $this->getItemCollection()->push($this->parseItem($item));

            if ($i === static::CHUNK) {
                $i = 0;
                $this->importChunk();
            }

            $this->rows++;
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->output->success("Импорт завершён. Загружено {$this->rows} строк.");
    }

    /**
     *
     */
    private function importChunk(): void
    {
        Item::query()->insert($this->getItemCollection()->all());
        $this->initCollection();
    }

    /**
     * @param array $item
     * @return array
     */
    private function parseItem(array $item): array
    {
        return [
            'wbuser_id' => $item['wbuser_id'],
            'uq' => $item['UQ'],
            'cnt' => $item['cnt'],
            'locale' => $item['locale'],
            'weekday' => $item['weekday'],
            'time' => $item['time']
        ];
    }

    /**
     * Get the console command arguments.
     * @return array
     */
    protected function getArguments()
    {
        return [];
    }

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        return [];
    }
}
