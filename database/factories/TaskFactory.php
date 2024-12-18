<?php

namespace Database\Factories;

use App\Models\Task;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'status' => $this->faker->randomElement([TaskStatus::Pending->value, TaskStatus::Completed->value]),
        ];
    }
}
