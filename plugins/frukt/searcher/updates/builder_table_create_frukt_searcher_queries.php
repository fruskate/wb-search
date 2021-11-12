<?php namespace Frukt\Searcher\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateFruktSearcherQueries extends Migration
{
    public function up()
    {
        Schema::create('frukt_searcher_queries', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('wbuser_id')->nullable();
            $table->string('uq')->nullable();
            $table->integer('cnt')->nullable();
            $table->string('locale')->nullable();
            $table->integer('weekday')->nullable();
            $table->time('time')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('frukt_searcher_queries');
    }
}
