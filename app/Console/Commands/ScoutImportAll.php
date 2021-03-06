<?php

namespace App\Console\Commands;

use Aic\Hub\Foundation\AbstractCommand as BaseCommand;

class ScoutImportAll extends BaseCommand
{

    protected $signature = 'scout:import-all';

    protected $description = 'Import all models into the search index';


    public function handle()
    {

        ini_set("memory_limit", "-1");

        $models = app('Search')->getSearchableModels();

        foreach( $models as $model ) {

            $this->call("scout:import", ['model' => $model]);

        }

    }

}
