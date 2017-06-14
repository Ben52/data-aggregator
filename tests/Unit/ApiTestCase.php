<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Faker\Factory as Faker;

abstract class ApiTestCase extends TestCase
{

    protected $faker;
    

    function __construct()
    {

        $this->faker = Faker::create();
        
    }

    public function setUp()
    {

        parent::setUp();
        \Artisan::call('migrate');
    }

    protected function assertArrayHasKeys($array = [], $keys = [])
    {

        foreach ($keys as $key)
        {

            $this->assertArrayHasKey($key, $array);

        }

    }

    public function it_fetches_all($class, $endpoint)
    {
        $this->times(5)->make($class);
        
        $response = $this->getJson('api/v1/' .$endpoint);
        $response->assertSuccessful();

        $resources = $response->json()['data'];
        $this->assertCount(5, $resources);

        foreach ($resources as $resource)
        {
            $this->assertArrayHasKeys($resource, ['id', 'title']);
        }
    }

    public function it_fetches_a_single($class, $endpoint)
    {

        $this->make($class);

        $response = $this->getJson('api/v1/' .$endpoint .'/' .$this->ids[0]);
        $response->assertSuccessful();

        $resource = $response->json()['data'];
        $this->assertArrayHasKeys($resource, ['id', 'title']);
    }

    public function it_fetches_multiple($class, $endpoint)
    {

        $this->times(5)->make($class);

        $response = $this->getJson('api/v1/' .$endpoint .'?ids=' .implode(',',array_slice($this->ids, 0, 3)));
        $response->assertSuccessful();

        $resources = $response->json()['data'];
        $this->assertCount(3, $resources);

        foreach ($resources as $resource)
        {
            $this->assertArrayHasKeys($resource, ['id', 'title']);
        }
    }

    public function it_400s_if_nonnumerid_nonuuid_is_passed($class, $endpoint)
    {

        $this->make($class);
        
        $response = $this->getJson('api/v1/' .$endpoint .'/fsdfdfs');

        $response->assertStatus(400);

    }

    public function it_403s_if_limit_is_too_high($class, $endpoint)
    {

        $this->make($class);
        
        $response = $this->getJson('api/v1/' .$endpoint .'?limit=2000');

        $response->assertStatus(403);

    }

    public function it_404s_if_not_found($class, $endpoint)
    {

        $this->make($class);
        
        $response = $this->getJson('api/v1/' .$endpoint .'/' .$this->faker->unique()->randomNumber(5));

        $response->assertStatus(404);

    }

    public function it_405s_if_a_request_is_posted($class, $endpoint)
    {

        $this->make($class);
        
        $response = $this->postJson('api/v1/' .$endpoint);

        $response->assertStatus(405);

    }

}
