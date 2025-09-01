<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_tasks_index_shows_tasks_and_view_variables()
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->for($user)->create(['title' => 'Tarea ejemplo']);

        // Si la vista muestra tareas del usuario autenticado:
        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertStatus(200)
            ->assertSee('Tarea ejemplo')
            ->assertSee('Lista de tareas'); // verifica título/encabezado
    }
    public function test_tasks_index_shows_only_authenticated_users_tasks()
    {
        // Creamos 2 usuarios distintos
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        // Creamos 2 tareas para cada usuario
        $taskA1 = Task::factory()->create(['user_id' => $userA->id, 'title' => 'Tarea de A']);
        $taskB1 = Task::factory()->create(['user_id' => $userB->id, 'title' => 'Tarea de B']);

        // Actuamos como userA
        $this->actingAs($userA)
            ->get(route('tasks.index'))
            ->assertStatus(200)
            ->assertSee('Tarea de A')
            ->assertDontSee('Tarea de B') // importante para que no aparezcan tareas de otro
            ->assertViewHas('tasks', function ($tasks) use ($userA) {
                return $tasks->every(fn($task) => $task->user_id === $userA->id);
            });
    }

    public function test_guest_users_are_redirected_to_login_when_accessing_tasks_index()
    {
        $this->get(route('tasks.index'))
            ->assertRedirect(route('login'));
    }

    public function test_it_shows_pagination_when_tasks_exceed_page_limit()
    {
        $user = User::factory()->create();

        // Crear 30 tareas (más de las que caben en una página por defecto = 15)
        Task::factory()->count(30)->for($user)->create();

        $this->actingAs($user)
            ->get(route('tasks.index'))
            ->assertStatus(200)
            ->assertSee('class="pagination"');
    }
}
