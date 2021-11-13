<?php namespace Frukt\Searcher\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateFruktSearcherBuys extends Migration
{
    public function up()
    {
        Schema::create('frukt_searcher_buys', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('popular_id')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('frukt_searcher_buys');
    }
}
