<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeLakeUidPrimaryKeyForTermsAndCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // terms
        DB::table('terms')->update([
            'lake_uid' => DB::raw('\'TM-\' || citi_id')
        ]);

        Schema::table('terms', function (Blueprint $table) {
            $table->dropColumn('citi_id');
            $table->primary('lake_uid');
        });

        // categories
        DB::table('categories')->update([
            'lake_uid' => DB::raw('\'PC-\' || citi_id')
        ]);

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('citi_id');
            $table->primary('lake_uid');
            // Prior to this migration, parent_id did not have an index
            $table->string('parent_id')->index()->change();
        });

        DB::table('categories')->update([
            'parent_id' => DB::raw('\'PC-\' || parent_id')
        ]);

        // artwork_category
        Schema::table('artwork_category', function(Blueprint $table) {
            $table->string('category_lake_uid')->nullable()->index();
        });

        DB::table('artwork_category')->update([
            'category_lake_uid' => DB::raw('\'PC-\' || category_citi_id')
        ]);

        Schema::table('artwork_category', function (Blueprint $table) {
            $table->dropColumn('category_citi_id');
        });

        // asset_category
        Schema::table('asset_category', function(Blueprint $table) {
            $table->string('category_lake_uid')->nullable()->index();
        });

        DB::table('asset_category')->update([
            'category_lake_uid' => DB::raw('\'PC-\' || category_citi_id')
        ]);

        Schema::table('asset_category', function (Blueprint $table) {
            $table->dropColumn('category_citi_id');
        });

        // category_place
        Schema::table('category_place', function(Blueprint $table) {
            $table->string('category_lake_uid')->nullable()->index();
        });

        DB::table('category_place')->update([
            'category_lake_uid' => DB::raw('\'PC-\' ||  category_citi_id')
        ]);

        Schema::table('category_place', function (Blueprint $table) {
            $table->dropColumn('category_citi_id');
        });

        // artwork_term
        Schema::table('artwork_term', function(Blueprint $table) {
            $table->string('term_lake_uid')->after('artwork_citi_id')->nullable()->index();
        });

        DB::table('artwork_term')->update([
            'term_lake_uid' => DB::raw('\'TM-\' || term_citi_id')
        ]);

        Schema::table('artwork_term', function (Blueprint $table) {
            $table->dropColumn('term_citi_id');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        // terms
        Schema::table('terms', function (Blueprint $table) {
            $table->dropPrimary();
            $table->integer('citi_id')->first();
        });

        DB::table('terms')->update([
            'citi_id' => DB::raw('substr(lake_uid FROM 4)')
        ]);

        Schema::table('terms', function (Blueprint $table) {
            $table->primary('citi_id');
        });

        // categories
        Schema::table('categories', function (Blueprint $table) {
            $table->dropPrimary();
            $table->integer('citi_id')->first();
        });

        DB::table('categories')->update([
            'citi_id' => DB::raw('substr(lake_uid FROM 4)')
        ]);

        DB::table('categories')->update([
            'parent_id' => DB::raw('substr(parent_id FROM 4)')
        ]);

        Schema::table('categories', function (Blueprint $table) {
            $table->primary('citi_id');
            // Revert back to index-less state
            $table->dropIndex(['parent_id']);
            $table->integer('parent_id')->change();
        });

        // artwork_category
        Schema::table('artwork_category', function (Blueprint $table) {
            $table->integer('category_citi_id');
        });

        DB::table('artwork_category')->update([
            'category_citi_id' => DB::raw('substr(category_lake_uid FROM 4)')
        ]);

        Schema::table('artwork_category', function (Blueprint $table) {
            $table->dropColumn('category_lake_uid');
        });

        // asset_category
        Schema::table('asset_category', function (Blueprint $table) {
            $table->integer('category_citi_id');
        });

        DB::table('asset_category')->update([
            'category_citi_id' => DB::raw('substr(category_lake_uid FROM 4)')
        ]);

        Schema::table('asset_category', function (Blueprint $table) {
            $table->dropColumn('category_lake_uid');
        });

        // category_place
        Schema::table('category_place', function (Blueprint $table) {
            $table->integer('category_citi_id');
        });

        DB::table('category_place')->update([
            'category_citi_id' => DB::raw('substr(category_lake_uid FROM 4)')
        ]);

        Schema::table('category_place', function (Blueprint $table) {
            $table->dropColumn('category_lake_uid');
        });

        // artwork_term
        Schema::table('artwork_term', function (Blueprint $table) {
            $table->integer('term_citi_id')->after('artwork_citi_id')->index();;
        });

        DB::table('artwork_term')->update([
            'term_citi_id' => DB::raw('substr(term_lake_uid FROM 4)')
        ]);

        Schema::table('artwork_term', function (Blueprint $table) {
            $table->dropColumn('term_lake_uid');
        });

    }
}
