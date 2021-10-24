<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CampaignSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CampaignTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Testing homepage.
     *
     * @return void
     */
    public function test_home_page()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_campaign_can_be_created()
    {
        $this->seed(CampaignSeeder::class);

        $this->assertDatabaseCount('campaigns', 10);
    }

    public function test_campaign_update_api_with_image_upload()
    {
        Storage::fake('images');
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->json('POST', '/campaign/save', [
                'name' => 'Random name',
                'date_from' => now()->addMinutes(5)->toDateString(),
                'date_to' => now()->addDays(5)->toDateString(),
                'total_budget' => mt_rand(100, 1000),
                'daily_budget' => mt_rand(10, 100),
                'image' => UploadedFile::fake()->image('random_image.jpg'),
            ]);

        // test if record updated with json response
        $response->assertStatus(201)
            ->assertJsonPath('data.name', 'Random name');

        // checking image uploaded
        Storage::disk('images')->assertExists('random_image.jpg');
    }

    public function test_campaign_listing_api()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->json('GET', '/campaign/listing');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }
}
