<?php namespace Frukt\Searcher\Console;

use Illuminate\Console\Command;

class LoadPopularity extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'load:popularity';

    /**
     * @var string The console command description.
     */
    protected $description = 'Импорт популярности';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->output->title('Импорт начался');

        $c =0;
        $fp = fopen(plugins_path('frukt/searcher/assets/datasets/query_popularity_tab.csv'),"r");
        if($fp){
            while(!feof($fp)){
                $content = fgets($fp);
                if($content)    $c++;
            }
        }
        fclose($fp);
        $bar = $this->output->createProgressBar($c);

        $row = 1;
        if (($handle = fopen(plugins_path('frukt/searcher/assets/datasets/query_popularity_tab.csv'), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
                //$num = count($data);
                //trace_log("$num полей в строке $row:");
                if ($row > 1) {
                    try {
                        \Frukt\Searcher\Models\Popular::where('name', trim($data[0]))->firstOrCreate([
                            'name' => trim($data[0]),
                            'popularity' => $data[1]
                        ]);
                    } catch (\Exception $exception) {
                        trace_log('Ошибка в данных');
                        trace_log($data);
                    }

                    /* for ($c=0; $c < $num; $c++) {
                        trace_log($data[$c]);
                    } */
                }

                $row++;
                $bar->advance();
            }
            fclose($handle);
        }
        $bar->finish();
        $this->output->success('Импорт завершён. Загружено '.$row.' строк.');
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
