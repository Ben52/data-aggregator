<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFiscalYear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('artworks', function (Blueprint $table) {
            $table->integer('fiscal_year')->nullable()->after('provenance');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('artworks', function (Blueprint $table) {
            $table->dropColumn('fiscal_year');
        });

    }

}
