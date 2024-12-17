<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use App\Enums\TaskStatus;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_list_all_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    #[Test]
    public function it_can_show_a_single_task()
    {
        $task = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $task->id,
                'title' => $task->title,
                'status' => $task->status,
            ]);
    }

    #[Test]
    public function it_returns_404_if_task_not_found()
    {
        $response = $this->getJson('/api/tasks/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_create_a_task()
    {
        $data = [
            'title' => 'New Task',
            'status' => TaskStatus::Pending->value,
        ];

        $response = $this->postJson('/api/tasks', $data);

        $response->assertStatus(201)
            ->assertJsonFragment($data);

        $this->assertDatabaseHas('tasks', $data);
    }

    #[Test]
    public function it_validates_task_creation()
    {
        $response = $this->postJson('/api/tasks', [
            'title' => '',
            'status' => 'invalid_status',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'status']);
    }

    #[Test]
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create([
            'title' => 'Old Title',
            'status' => TaskStatus::Pending->value,
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'status' => TaskStatus::Completed->value,
        ];

        $response = $this->putJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
            ->assertJsonFragment($updateData);

        $this->assertDatabaseHas('tasks', $updateData);
    }

    #[Test]
    public function it_returns_404_when_updating_nonexistent_task()
    {
        $response = $this->putJson('/api/tasks/999', [
            'title' => 'New Title',
            'status' => 'pending',
        ]);

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function it_returns_404_when_deleting_nonexistent_task()
    {
        $response = $this->deleteJson('/api/tasks/999');

        $response->assertStatus(404);
    }

    #[Test]
    public function it_can_toggle_task_status()
    {
        $task = Task::factory()->create([
            'status' => TaskStatus::Pending->value,
        ]);

        // Toggle to completed
        $response = $this->patchJson("/api/tasks/{$task->id}/toggle-status");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => TaskStatus::Completed->value]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatus::Completed->value,
        ]);

        // Toggle back to pending
        $response = $this->patchJson("/api/tasks/{$task->id}/toggle-status");

        $response->assertStatus(200)
            ->assertJsonFragment(['status' => TaskStatus::Pending->value]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => TaskStatus::Pending->value,
        ]);
    }
}
