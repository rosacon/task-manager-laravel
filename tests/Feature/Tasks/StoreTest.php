<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskStoreTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_it_stores_a_task_and_redirects()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'title' => 'Mi primera tarea',
            'description' => 'Detalle de la tarea',
        ];

        $response = $this->post('/tasks', $data);

        $response->assertRedirect('/tasks');

        $this->assertDatabaseHas('tasks', [
            'title' => 'Mi primera tarea',
            'description' => 'Detalle de la tarea',
            'user_id' => $user->id,
        ]);
    }

    
    public function test_it_does_not_store_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'title' => '', // inválido (requerido)
            'description' => '',
        ];

        $response = $this->post('/tasks', $data);

        $response->assertSessionHasErrors(['title']); // Laravel validará 'title'

        $this->assertDatabaseCount('tasks', 0); // no guarda nada
    }

    
    public function test_guests_cannot_store_tasks()
    {
        $data = [
            'title' => 'Tarea sin usuario',
            'description' => 'Esto no debería guardarse',
        ];

        $response = $this->post('/tasks', $data);

        $response->assertRedirect('/login'); // middleware auth

        $this->assertDatabaseCount('tasks', 0);
    }
}
