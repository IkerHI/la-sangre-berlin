<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MessageReplyTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::factory()->create(['is_admin' => true]);
    }

    public function test_admin_can_reply_to_message(): void
    {
        Mail::fake();

        $msg = ContactMessage::create([
            'name'    => 'Fan',
            'email'   => 'fan@test.com',
            'subject' => 'Concierto',
            'message' => '¿Cuándo tocáis?',
            'read'    => false,
        ]);

        $response = $this->actingAs($this->adminUser(), 'sanctum')
            ->postJson("/api/admin/messages/{$msg->id}/reply", [
                'body' => 'Tocamos el 1 de junio en el Lido.',
            ]);

        $response->assertStatus(200);
        $response->assertJsonPath('read', true);

        Mail::assertSent(\App\Mail\MessageReply::class, function ($mail) use ($msg) {
            return $mail->hasTo($msg->email);
        });
    }

    public function test_reply_requires_body(): void
    {
        $msg = ContactMessage::create([
            'name'    => 'Fan',
            'email'   => 'fan@test.com',
            'subject' => 'Concierto',
            'message' => '¿Cuándo tocáis?',
            'read'    => false,
        ]);

        $response = $this->actingAs($this->adminUser(), 'sanctum')
            ->postJson("/api/admin/messages/{$msg->id}/reply", []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['body']);
    }

    public function test_reply_marks_message_as_read(): void
    {
        Mail::fake();

        $msg = ContactMessage::create([
            'name'    => 'Fan',
            'email'   => 'fan@test.com',
            'subject' => 'Concierto',
            'message' => '¿Cuándo tocáis?',
            'read'    => false,
        ]);

        $this->actingAs($this->adminUser(), 'sanctum')
            ->postJson("/api/admin/messages/{$msg->id}/reply", [
                'body' => 'Respuesta de prueba.',
            ]);

        $this->assertDatabaseHas('contact_messages', ['id' => $msg->id, 'read' => true]);
    }

    public function test_unauthenticated_cannot_reply(): void
    {
        $msg = ContactMessage::create([
            'name' => 'Fan', 'email' => 'fan@test.com',
            'subject' => 'X', 'message' => 'Y', 'read' => false,
        ]);

        $this->postJson("/api/admin/messages/{$msg->id}/reply", ['body' => 'Hola'])
            ->assertStatus(401);
    }

    public function test_non_admin_cannot_reply(): void
    {
        $user = User::factory()->create(['is_admin' => false]);
        $msg = ContactMessage::create([
            'name' => 'Fan', 'email' => 'fan@test.com',
            'subject' => 'X', 'message' => 'Y', 'read' => false,
        ]);

        $this->actingAs($user, 'sanctum')
            ->postJson("/api/admin/messages/{$msg->id}/reply", ['body' => 'Hola'])
            ->assertStatus(403);
    }
}
