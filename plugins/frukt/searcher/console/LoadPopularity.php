<?php namespace Frukt\Searcher\Console;

use Frukt\Searcher\Classes\Import\CsvReader;
use Frukt\Searcher\Classes\Import\CsvReaderHelper;
use Frukt\Searcher\Classes\Import\TemporaryCollections;
use Frukt\Searcher\Models\Item;
use Frukt\Searcher\Models\Popular;
use Illuminate\Console\Command;

/**
 *
 */
class LoadPopularity extends Command
{
    use TemporaryCollections, CsvReaderHelper;

    /**
     * @var string The console command name.
     */
    protected $name = 'load:popularity';

    /**
     * @var string The console command description.
     */
    protected $description = 'Импорт популярности';

    /**
     * @var string
     */
    private string $path = 'datasets/query_popularity_tab.csv';

    /**
     * Chunk limit
     */
    protected const CHUNK = 1000;

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
        Popular::query()->insert($this->getItemCollection()->all());
        $this->initCollection();
    }

    /**
     * @param array $item
     * @return array
     */
    private function parseItem(array $item): array
    {
        return [
            'name' => trim($item['query']),
            'popularity' => $item['query_popularity'],
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
