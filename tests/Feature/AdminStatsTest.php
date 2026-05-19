<?php

namespace Tests\Feature;

use App\Models\Concert;
use App\Models\ContactMessage;
use App\Models\SiteStat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminStatsTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_stats_endpoint_returns_expected_keys(): void
    {
        $response = $this->actingAs($this->adminUser(), 'sanctum')
            ->getJson('/api/admin/stats');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'total_visits',
            'unread_messages',
            'total_concerts',
            'upcoming_concerts',
            'top_concerts',
        ]);
    }

    public function test_settings_endpoint_increments_total_visits(): void
    {
        $this->getJson('/api/settings');
        $this->getJson('/api/settings');

        $this->assertEquals(2, SiteStat::fetch('total_visits'));
    }

    public function test_concerts_endpoint_no_longer_increments_visits(): void
    {
        $this->getJson('/api/concerts');

        $this->assertEquals(0, SiteStat::fetch('total_visits'));
    }

    public function test_stats_reflects_unread_messages(): void
    {
        ContactMessage::create([
            'name' => 'A', 'email' => 'a@t.com',
            'subject' => 'X', 'message' => 'Y', 'read' => false,
        ]);
        ContactMessage::create([
            'name' => 'B', 'email' => 'b@t.com',
            'subject' => 'X', 'message' => 'Y', 'read' => true,
        ]);

        $response = $this->actingAs($this->adminUser(), 'sanctum')
            ->getJson('/api/admin/stats');

        $response->assertJsonPath('unread_messages', 1);
    }
}
