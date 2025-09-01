<?php

namespace Tests\Feature\Tasks;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Task;
use App\Models\User;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_task()
    {
        $user = User::factory()->create();
        $task = Task::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('tasks.show', $task))
            ->assertStatus(200)
            ->assertViewIs('tasks.show')
            ->assertViewHas('task', $task)
            ->assertSeeText($task->title);
    }

    public function test_guest_cannot_view_task()
    {
        $task = Task::factory()->create();

        $this->get(route('tasks.show', $task))
            ->assertRedirect(route('login'));
    }


    public function test_user_cannot_view_others_task()
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $task = Task::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->get(route('tasks.show', $task))
            ->assertStatus(403);
    }
}
