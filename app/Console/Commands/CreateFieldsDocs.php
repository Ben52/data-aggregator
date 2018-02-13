<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CreateFieldsDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docs:fields';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate documentation for all the fields on each endpoint';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $doc = '';

        $doc .= "# Collections\n\n";
        $doc .= \App\Models\Collections\Artwork::instance()->docFields();
        $doc .= \App\Models\Collections\Agent::instance()->docFields();
        $doc .= \App\Models\Collections\ObjectType::instance()->docFields();
        $doc .= \App\Models\Collections\Category::instance()->docFields();
        $doc .= \App\Models\Collections\AgentType::instance()->docFields();
        $doc .= \App\Models\Collections\Place::instance()->docFields();
        $doc .= \App\Models\Collections\Gallery::instance()->docFields();
        $doc .= \App\Models\Collections\Exhibition::instance()->docFields();
        $doc .= \App\Models\Collections\Image::instance()->docFields();
        $doc .= \App\Models\Collections\Video::instance()->docFields();
        $doc .= \App\Models\Collections\Link::instance()->docFields();
        $doc .= \App\Models\Collections\Sound::instance()->docFields();
        $doc .= \App\Models\Collections\Text::instance()->docFields();

        $doc .= "# Shop\n\n";
        $doc .= \App\Models\Shop\Category::instance()->docFields();
        $doc .= \App\Models\Shop\Product::instance()->docFields();

        $doc .= "# Events\n\n";
        $doc .= \App\Models\Membership\LegacyEvent::instance()->docFields();
        $doc .= \App\Models\Membership\TicketedEvent::instance()->docFields();

        $doc .= "# Mobile\n\n";
        $doc .= \App\Models\Mobile\Tour::instance()->docFields();
        $doc .= \App\Models\Mobile\TourStop::instance()->docFields();
        $doc .= \App\Models\Mobile\Sound::instance()->docFields();

        $doc .= "# Digital Scholarly Catalogs\n\n";
        $doc .= \App\Models\Dsc\Publication::instance()->docFields();
        $doc .= \App\Models\Dsc\Section::instance()->docFields();

        $doc .= "# Static Archive\n\n";
        $doc .= \App\Models\StaticArchive\Site::instance()->docFields();

        $doc .= "# Archive\n\n";
        $doc .= \App\Models\Archive\ArchiveImage::instance()->docFields();

        $doc .= "# Library\n\n";
        $doc .= \App\Models\Library\Material::instance()->docFields();
        $doc .= \App\Models\Library\Term::instance()->docFields();

        $doc .= "> Generated by `php artisan docs:fields` on " .Carbon::now() ."\n";

        Storage::disk('local')->put('FIELDS.md', $doc);

    }

}
