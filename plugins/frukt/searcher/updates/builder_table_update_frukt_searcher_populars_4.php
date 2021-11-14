<?php namespace Frukt\Searcher\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateFruktSearcherPopulars4 extends Migration
{
    public function up()
    {
        Schema::table('frukt_searcher_populars', function($table)
        {
            $table->integer('buys')->default(0);
        });
    }
    
    public function down()
    {
        Schema::table('frukt_searcher_populars', function($table)
        {
            $table->dropColumn('buys');
        });
    }
}
