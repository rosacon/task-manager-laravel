<?php

namespace Tests\Feature\Tasks;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_access_create_view()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('tasks.create'))
            ->assertStatus(200)
            ->assertViewIs('tasks.create');
    }

    public function test_guest_is_redirected_from_create_to_login()
    {
        $this->get(route('tasks.create'))
            ->assertRedirect(route('login'));
    }
}
