<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Models\Collections\Sound;

class SoundTest extends ApiTestCase
{

    protected $model = Sound::class;

    protected $route = 'sounds';

    /** @test */
    public function it_404s_if_not_found()
    {

        $this->it_404s(Sound::class, 'sounds', true);

    }

}
