<?php

namespace Tests\Feature;

use App\Models\ContactMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactPhoneTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    public function test_contact_stores_phone_when_provided(): void
    {
        $response = $this->postJson('/api/contact', [
            'name'    => 'Iker Test',
            'email'   => 'iker@test.com',
            'subject' => 'Hola',
            'message' => 'Mensaje de prueba',
            'phone'   => '+34 666 123 456',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contact_messages', ['phone' => '+34 666 123 456']);
    }

    public function test_contact_works_without_phone(): void
    {
        $response = $this->postJson('/api/contact', [
            'name'    => 'Sin Teléfono',
            'email'   => 'sin@test.com',
            'subject' => 'Hola',
            'message' => 'Mensaje de prueba',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contact_messages', ['email' => 'sin@test.com', 'phone' => null]);
    }

    public function test_contact_rejects_invalid_phone_format(): void
    {
        $response = $this->postJson('/api/contact', [
            'name'    => 'Iker Test',
            'email'   => 'iker@test.com',
            'subject' => 'Hola',
            'message' => 'Mensaje de prueba',
            'phone'   => 'abc!!!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);
    }

    public function test_contact_rejects_phone_too_short(): void
    {
        $response = $this->postJson('/api/contact', [
            'name'    => 'Iker Test',
            'email'   => 'iker@test.com',
            'subject' => 'Hola',
            'message' => 'Mensaje de prueba',
            'phone'   => '123',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['phone']);
    }
}
