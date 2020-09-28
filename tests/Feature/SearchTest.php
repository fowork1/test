<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SearchTest extends TestCase
{
    /**
     * check can find by name fragment
     */
    public function testSearchName()
    {
        $response = $this->postJson('/api/v1/search', ['name' => 'victoria']);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'The Victoria', 'bedrooms' => 4]);
    }

    /**
     * check can find by min price
     */
    public function testSearchMinPrice()
    {
        $response = $this->postJson('/api/v1/search', ['price_from' => 400000]);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'The Como', 'name' => 'The Lucretia'])
            ->assertJsonMissing(['name' => 'The Aspen']);
    }

    /**
     * check can find by max price
     */
    public function testSearchMaxPrice()
    {
        $response = $this->postJson('/api/v1/search', ['price_to' => 390000]);

        $response->assertOk()
            ->assertJsonFragment(['name' => 'The Victoria', 'name' => 'The Skyscape'])
            ->assertJsonMissing(['name' => 'The Geneva']);
    }

    /**
     * check search return empty json
     */
    public function testSearchEmptyResult()
    {
        $response = $this->postJson('/api/v1/search', ['name' => 'Victoria', 'price_to' => 350000]);

        $response->assertJson([]);
    }

}
