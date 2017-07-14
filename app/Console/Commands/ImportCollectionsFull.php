<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCollectionsFull extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:collections-full 
                            {endpoint? : That last portion of the URL path naming the resource to import, for example "artists"} 
                            {page? : The page to begin importing from}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =
                           "Import all collections data\n\n"
                           
                           ."If no options are passes all Collections data will be imported. Results are paged through 100 records \n"
                           ."at a time. If the Collections Data Service doesn't provide an endpoint fake data will be generated.";

    protected $faker;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if ($this->argument('endpoint'))
        {
            $page = $this->argument('page') ?: 1;
            $this->import($this->argument('endpoint'), $page);
        }
        else
        {

            // @TODO Replace with real endpoint when it becomes available
            if (\App\Collections\AgentType::all()->isEmpty())
            {

                Artisan::call("db:seed", ['class' => 'AgentTypesTableSeeder']);
                
            }

            // @TODO Replace with agent endpoint when it becomes available
            if (\App\Collections\Agent::all()->isEmpty())
            {

                $this->import('artists');
                Artisan::call("db:seed", ['class' => 'AgentsTableSeeder']);
                
            }

            $this->import('departments');

            // @TODO Replace with real endpoint when it becomes available
            if (\App\Collections\ObjectType::all()->isEmpty())
            {

                Artisan::call("db:seed", ['class' => 'ObjectTypesTableSeeder']);

            }

            // @TODO The categories endpoint in the Collections Data Service currently breaks on the last page of results.
            $this->import('categories');

            // @TODO Galleries are available, but break due to Redmine bug #1911 - Gallery Floor isn't always a number
            //$this->import('galleries');

            $this->import('artworks');
            $this->import('links');
            $this->import('videos');
            $this->import('texts');
            $this->import('sounds');

            // @TODO Replace with real endpoint when it becomes available
            $artworks = \App\Collections\Artwork::all()->all();

            foreach ($artworks as $artwork) {

                $artwork->seedImages();

            }

            // @TODO Replace with real endpoint when it becomes available
            if (\App\Collections\Exhibition::all()->isEmpty())
            {

                Artisan::call("db:seed", ['class' => 'ExhibitionsTableSeeder']);

            }

        }
        
    }

    private function import($endpoint, $current = 1)
    {

        $class = \App\Collections\CollectionsModel::classFor($endpoint);

        $resources = call_user_func($class .'::all');
        if ($resources->isEmpty())
        {
            $json = $this->query($endpoint, $current);
            $pages = $json->pagination->pages->total;

            while ($current <= $pages)
            {

                foreach ($json->data as $source)
                {

                    $resource = call_user_func($class .'::findOrCreate', $source->id);

                    $resource->fillFrom($source);
                    $resource->attachFrom($source);
                    $resource->save();

                }

                $current++;
                $json = $this->query($endpoint, $current);

            }

        }

    }

    private function query($type = 'artworks', $page = 1)
    {

        $ch = curl_init();

        curl_setopt ($ch, CURLOPT_URL, env('COLLECTIONS_DATA_SERVICE_URL', 'http://localhost') .'/' .$type .'?page=' .$page .'&per_page=100');
        curl_setopt ($ch, CURLOPT_HEADER, 0);

        ob_start();

        curl_exec ($ch);
        curl_close ($ch);
        $string = ob_get_contents();

        ob_end_clean();

        return json_decode($string);

    }

}