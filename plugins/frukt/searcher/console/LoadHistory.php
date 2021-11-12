<?php namespace Frukt\Searcher\Console;

use Illuminate\Console\Command;

class LoadHistory extends Command
{
    /**
     * @var string The console command name.
     */
    protected $name = 'load:history';

    /**
     * @var string The console command description.
     */
    protected $description = 'Импорт истории запросов';

    /**
     * Execute the console command.
     * @return void
     */
    public function handle()
    {
        $this->output->title('Импорт начался');
        $row = 1;
        if (($handle = fopen(plugins_path('frukt/searcher/assets/datasets/search_history.csv'), "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                //$num = count($data);
                //trace_log("$num полей в строке $row:");
                if ($row > 1) {
                    \Frukt\Searcher\Models\Item::where('uq', $data[0])->create([
                        'wbuser_id' => $data[0],
                        'uq' => $data[1],
                        'cnt' => $data[2],
                        'locale' => $data[3],
                        'weekday' => $data[4],
                        'time' => $data[5]
                    ]);
                    /* for ($c=0; $c < $num; $c++) {
                        trace_log($data[$c]);
                    } */
                }

                $row++;
            }
            fclose($handle);
        }
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
