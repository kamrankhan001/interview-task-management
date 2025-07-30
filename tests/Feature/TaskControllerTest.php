<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\{Task, Project};
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $project;
    protected $tasks;

    protected function setUp(): void
    {
        parent::setUp();
        $this->project = Project::factory()->create();

        $this->tasks = Task::factory()->count(3)->sequence(
            ['priority' => 1],
            ['priority' => 2],
            ['priority' => 3]
        )->create(['project_id' => $this->project->id]);
    }

    #[Test]
    public function it_displays_tasks_index_page()
    {
        $response = $this->get(route('tasks.index'));

        $response->assertStatus(200)
            ->assertViewIs('tasks.index')
            ->assertViewHas('projects')
            ->assertViewHas('showTasks', false);
    }

    #[Test]
    public function it_displays_tasks_index_page_in_dark_mode()
    {
        session(['dark_mode' => true]);
        $response = $this->get(route('tasks.index'));
        $response->assertStatus(200)->assertSee('dark:bg-gray-800');
    }

    #[Test]
    public function it_returns_filtered_tasks_via_ajax()
    {

        $response = $this->getJson(
            route('tasks.index', ['project_id' => $this->project->id]),
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'tasks' => [
                    '*' => [
                        'id',
                        'name',
                        'project_id',
                        'priority',
                        'created_at',
                        'updated_at',
                        'project' => [
                            'id',
                            'name',
                            'created_at',
                            'updated_at'
                        ]
                    ]
                ]
            ])
            ->assertJsonPath('success', true)
            ->assertJsonCount(3, 'tasks');
    }

    #[Test]
    public function it_stores_a_new_task()
    {
        $taskData = [
            'name' => 'New Task',
            'project_id' => $this->project->id
        ];

        $response = $this->postJson(route('tasks.store'), $taskData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'task' => [
                    'name' => 'New Task',
                    'project_id' => $this->project->id,
                    'priority' => 4
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'name' => 'New Task',
            'project_id' => $this->project->id,
            'priority' => 4
        ]);
    }

    #[Test]
    public function it_validates_task_creation()
    {
        $response = $this->postJson(route('tasks.store'), [
            'name' => '',
            'project_id' => 999
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'project_id']);
    }

    #[Test]
    public function it_updates_a_task()
    {
        $task = $this->tasks->first();

        $response = $this->putJson(route('tasks.update', $task->id), [
            'name' => 'Updated Task Name'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'task' => [
                    'id' => $task->id,
                    'name' => 'Updated Task Name'
                ]
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'name' => 'Updated Task Name'
        ]);
    }

    #[Test]
    public function it_deletes_a_task_and_reorders_priorities()
    {
        $taskToDelete = $this->tasks->sortBy('priority')->first();

        $response = $this->deleteJson(route('tasks.destroy', $taskToDelete->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Task deleted successfully.'
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $taskToDelete->id]);

        $remainingTasks = Task::where('project_id', $this->project->id)
            ->orderBy('priority')
            ->get();

        $this->assertEquals(1, $remainingTasks[0]->priority);
        $this->assertEquals(2, $remainingTasks[1]->priority);
    }

    #[Test]
    public function it_reorders_tasks()
    {
        $tasks = $this->tasks->sortBy('priority')->values();
        $reorderData = [
            ['id' => $tasks[2]->id, 'position' => 1],
            ['id' => $tasks[0]->id, 'position' => 2],
            ['id' => $tasks[1]->id, 'position' => 3]
        ];

        $response = $this->postJson(route('tasks.reorder'), ['order' => $reorderData]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Tasks reordered successfully.'
            ]);

        $this->assertEquals(1, Task::find($tasks[2]->id)->priority);
        $this->assertEquals(2, Task::find($tasks[0]->id)->priority);
        $this->assertEquals(3, Task::find($tasks[1]->id)->priority);
    }

    #[Test]
    public function it_handles_errors_gracefully()
    {
        $response = $this->putJson(route('tasks.update', 999), [
            'name' => 'Invalid Task'
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'success' => false,
                'message' => 'An error occurred.'
            ]);
    }
}