<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class EditTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_edit_form_of_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('tasks.edit', $task))
            ->assertStatus(200)
            ->assertViewIs('tasks.edit')
            ->assertViewHas('task', $task)
            ->assertSeeText('Editar tarea'); // O el texto que tenga el formulario
    }

    public function test_guest_cannot_access_edit_form()
    {
        $task = Task::factory()->create();

        $this->get(route('tasks.edit', $task))
            ->assertRedirect(route('login'));
    }

    public function test_user_cannot_edit_others_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->get(route('tasks.edit', $task))
            ->assertStatus(403);
    }
}
