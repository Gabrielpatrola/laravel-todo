<?php

namespace Tests\Unit;

use App\Enums\TaskStatus;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TaskModelTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_checks_if_status_is_valid()
    {
        $this->assertTrue(TaskStatus::isValidStatus('pending'));
        $this->assertTrue(TaskStatus::isValidStatus('completed'));
        $this->assertFalse(TaskStatus::isValidStatus('invalid_status'));
    }

    #[Test]
    public function it_creates_a_task_with_valid_data()
    {
        // Arrange
        $task = Task::create([
            'title' => 'Test Task',
            'status' => TaskStatus::Pending->value,
        ]);

        // Assert
        $this->assertInstanceOf(Task::class, $task);
        $this->assertEquals('Test Task', $task->title);
        $this->assertEquals('pending', $task->status);
    }
}
