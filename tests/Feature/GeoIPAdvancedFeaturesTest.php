<?php

namespace Tests\Feature;

use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class GeoIPAdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_geo_firewall_page_renders_successfully(): void
    {
        $response = $this->get('/geo-firewall');
        $response->assertStatus(200);
        $response->assertSee('GeoIP Firewall');
        $response->assertSee('Access Control Studio');
    }

    public function test_geo_firewall_toggle_updates_cache_status(): void
    {
        $response = $this->post('/geo-firewall/toggle');
        $response->assertRedirect('/geo-firewall');

        $this::assertTrue(Cache::get('geo_firewall_enabled'));

        // Toggle back
        $this->post('/geo-firewall/toggle');
        $this::assertFalse(Cache::get('geo_firewall_enabled'));
    }

    public function test_geo_firewall_mode_updates_mode(): void
    {
        $response = $this->post('/geo-firewall/mode', [
            'mode' => 'whitelist',
        ]);

        $response->assertRedirect('/geo-firewall');
        $this::assertEquals('whitelist', Cache::get('geo_firewall_mode'));
    }

    public function test_geo_firewall_rule_addition_and_removal(): void
    {
        // Add country rule
        $response1 = $this->post('/geo-firewall/rule', [
            'action' => 'add_country',
            'value' => 'Germany',
        ]);
        $response1->assertRedirect('/geo-firewall');
        $this::assertContains('Germany', Cache::get('geo_firewall_countries', []));

        // Remove country rule
        $response2 = $this->post('/geo-firewall/rule', [
            'action' => 'remove_country',
            'value' => 'Germany',
        ]);
        $response2->assertRedirect('/geo-firewall');
        $this::assertNotContains('Germany', Cache::get('geo_firewall_countries', []));
    }

    public function test_blacklisted_country_triggers_403_access_denied(): void
    {
        Cache::forever('geo_firewall_enabled', true);
        Cache::forever('geo_firewall_mode', 'blacklist');
        Cache::forever('geo_firewall_countries', ['India']);

        // Accessing dashboard with test IP from India (49.36.0.1)
        $response = $this->get('/geo-dashboard?ip=49.36.0.1');
        $response->assertStatus(403);
        $response->assertSee('403 Geo-Access Denied');
        $response->assertSee('India');
    }

    public function test_world_heatmap_visualizer_renders_with_visitor_coordinates(): void
    {
        Visitor::create([
            'ip_address' => '49.36.0.1',
            'country' => 'India',
            'city' => 'Mumbai',
            'latitude' => 19.0760,
            'longitude' => 72.8777,
        ]);

        $response = $this->get('/geo-heatmap');
        $response->assertStatus(200);
        $response->assertSee('Real-Time Live Traffic World Heatmap Visualizer');
        $response->assertSee('19.076');
        $response->assertSee('72.8777');
    }

    public function test_regional_localization_studio_detects_currency_and_timezone(): void
    {
        $response = $this->get('/geo-localization?ip=49.36.0.1');
        $response->assertStatus(200);
        $response->assertSee('Geo-Smart Currency');
        $response->assertSee('Regional Localization Studio');
        $response->assertSee('India');
        $response->assertSee('INR');
        $response->assertSee('₹');
        $response->assertSee('Asia/Kolkata');
        $response->assertSee('+91');
    }
}
