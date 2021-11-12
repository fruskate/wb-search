<?php namespace Frukt\Searcher\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateFruktSearcherQueries extends Migration
{
    public function up()
    {
        Schema::table('frukt_searcher_queries', function($table)
        {
            $table->text('uq')->nullable()->unsigned(false)->default(null)->change();
        });
    }
    
    public function down()
    {
        Schema::table('frukt_searcher_queries', function($table)
        {
            $table->string('uq', 255)->nullable()->unsigned(false)->default(null)->change();
        });
    }
}
