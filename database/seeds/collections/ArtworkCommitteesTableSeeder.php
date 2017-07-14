<?php

use Illuminate\Database\Seeder;

class ArtworkCommitteesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $artworks = App\Collections\Artwork::all()->all();

        foreach ($artworks as $artwork) {

            $artwork->seedCommittees();

        }
        
    }
    
}