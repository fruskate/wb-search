<?php namespace Frukt\Searcher\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateFruktSearcherCategories extends Migration
{
    public function up()
    {
        Schema::table('frukt_searcher_categories', function($table)
        {
            $table->integer('parent_id')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('frukt_searcher_categories', function($table)
        {
            $table->dropColumn('parent_id');
        });
    }
}
