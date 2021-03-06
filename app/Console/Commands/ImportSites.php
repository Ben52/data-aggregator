<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;

use App\Models\StaticArchive\Site;

class ImportSites extends AbstractImportCommand
{

    protected $signature = 'import:sites
                            {--y|yes : Answer "yes" to all prompts}';

    protected $description = "Import all historic microsites";


    public function handle()
    {

        if( !$this->reset() )
        {
            return false;
        }

        $contents = $this->fetch( env('STATIC_ARCHIVE_JSON') );

        Storage::disk('local')->put('archive.json', $contents);

        $contents = Storage::get('archive.json');

        $results = json_decode( $contents );

        $this->importSites( $results->data );

    }

    private function importSites( $results )
    {

        $this->info("Importing static sites");

        foreach( $results as $datum )
        {

            $transformer = app('Resources')->getInboundTransformerForModel( Site::class, 'StaticArchive' );

            $this->save( $datum, Site::class, $transformer );

        }

    }

    protected function reset()
    {

        return $this->resetData(
            [
                Site::class,
            ],
            [
                'artwork_site',
                'agent_site',
                'exhibition_site',
                'sites',
            ]
        );

    }

}
