<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $data = [
            'title' => 'Nuevo título',
            'description' => 'Descripción actualizada',
        ];

        $this->actingAs($user)
            ->put(route('tasks.update', $task), $data)
            ->assertRedirect(route('tasks.index'));

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Nuevo título',
            'description' => 'Descripción actualizada',
        ]);
    }

    public function test_guest_cannot_update_task()
    {
        $task = Task::factory()->create();

        $this->put(route('tasks.update', $task), [
            'title' => 'Hack intento',
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('tasks', ['title' => 'Hack intento']);
    }

    public function test_user_cannot_update_others_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->put(route('tasks.update', $task), [
                'title' => 'Intento inválido',
            ])->assertStatus(403);

        $this->assertDatabaseMissing('tasks', ['title' => 'Intento inválido']);
    }

    public function test_update_requires_title()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user)
            ->put(route('tasks.update', $task), [
                'title' => '',
            ])
            ->assertSessionHasErrors('title');
    }
}
