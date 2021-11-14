<?php namespace Frukt\Searcher\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableUpdateFruktSearcherBrands extends Migration
{
    public function up()
    {
        Schema::table('frukt_searcher_brands', function($table)
        {
            $table->string('name2')->nullable();
            $table->string('name3')->nullable();
            $table->string('name4')->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('frukt_searcher_brands', function($table)
        {
            $table->dropColumn('name2');
            $table->dropColumn('name3');
            $table->dropColumn('name4');
        });
    }
}
