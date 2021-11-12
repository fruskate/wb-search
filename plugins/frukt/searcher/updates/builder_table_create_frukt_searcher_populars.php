<?php namespace Frukt\Searcher\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateFruktSearcherPopulars extends Migration
{
    public function up()
    {
        Schema::create('frukt_searcher_populars', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name')->nullable();
            $table->smallInteger('popularity')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('frukt_searcher_populars');
    }
}
