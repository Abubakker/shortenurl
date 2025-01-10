<?php

namespace Tests\Feature;

use App\Models\Url;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UrlShortenerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test shorten url creates short code.
     */
    public function test_shorten_url_creates_short_code()
    {
        $response = $this->postJson('/api/shorten', ['original_url' => 'https://example.com']);
        $response->assertStatus(201)->assertJsonStructure(['short_url']);
    }

    /**
     * Test redirect to original url.
     */
    public function test_redirect_to_original_url()
    {
        $url = Url::factory()->create(['original_url' => 'https://unique-example.com', 'short_code' => 'abc123']);
        $response = $this->get('/abc123');
        $response->assertViewIs($url->original_url);
    }

    /**
     * Test invalid url is rejected.
     */
    public function test_invalid_url_is_rejected()
    {
        $response = $this->postJson('/api/shorten', ['original_url' => 'invalid-url']);
        $response->assertStatus(422);
    }
}
