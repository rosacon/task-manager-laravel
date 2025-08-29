<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class IndexTaskTest extends TestCase
{

    public function test_tasks_index_shows_tasks_and_view_variables()
    {
        $user = User::factory()->create();
        Task::factory()->count(3)->for($user)->create(['title' => 'Tarea ejemplo']);

        // Ej: si la vista difiere para usuarios no autenticados, pruebe ambos escenarios:
        /*$this->get(route('tasks.index'))
            ->assertStatus(200)
            ->assertViewIs('tasks.index')
            ->assertViewHas('tasks');*/

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
            ->assertSee('Tarea de A')   // debería ver sus tareas
            ->assertDontSee('Tarea de B'); // no debería ver las de otros
    }
}
