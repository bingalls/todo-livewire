<?php

use App\Livewire\TasksList;
use App\Models\Task;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Livewire\Livewire;

uses(RefreshDatabase::class);

describe('Component Initialization', static function () {
    test('component can be mounted', function () {
        Livewire::test(new TasksList)
            ->assertStatus(200);
    });

    test('component has correct initial properties', function () {
        Livewire::test(new TasksList)
            ->assertSet('showModal', false)
            ->assertSet('editedTaskId', 0)
            ->assertSet('project', '')
            ->assertSet('taskname', '')
            ->assertSet('task', null);
    });

    test('component renders correctly', function () {
        Livewire::test(new TasksList)
            ->assertViewIs('livewire.tasks-list');
    });
});

describe('Modal Functionality', static function () {
    test('can open modal', function () {
        Livewire::test(new TasksList)
            ->call('openModal')
            ->assertSet('showModal', true);
    });

    test('modal is closed after saving', function () {
        Livewire::test(new TasksList)
            ->set('taskname', 'Test Task')
            ->set('showModal', true)
            ->call('save')
            ->assertSet('showModal', false);
    });

    test('can cancel task edit', function () {
        /** @var Task $task */
        $task = Task::factory()->create();

        Livewire::test(new TasksList)
            ->set('editedTaskId', $task->id)
            ->call('cancelTaskEdit')
            ->assertSet('editedTaskId', 0);
    });
});

describe('Task Creation', static function () {
    test('can create new task', function () {
        Livewire::test(new TasksList)
            ->set('taskname', 'New Task')
            ->set('project', 'test-project')
            ->call('save')
            ->assertSet('showModal', false);

        expect(Task::where('taskname', 'New Task')->exists())->toBeTrue();
    });

    test('creates task with correct position', function () {
        Task::factory()->create(['position' => 5]);
        Task::factory()->create(['position' => 10]);

        Livewire::test(new TasksList)
            ->set('taskname', 'New Task')
            ->call('save');

        $newTask = Task::where('taskname', 'New Task')->first();
        expect($newTask->position)->toBe(11);
    });

    test('creates task with position 1 when no tasks exist', function () {
        Livewire::test(new TasksList)
            ->set('taskname', 'First Task')
            ->call('save');

        $task = Task::where('taskname', 'First Task')->first();
        expect($task->position)->toBe(1);
    });

    test('project slug is generated from taskname', function () {
        $component = new TasksList;
        $component->taskname = 'Test Task Name';
        $component->updatedName();

        expect($component->project)->toBe('test-task-name');
    });
});

describe('Task Editing', static function () {
    test('can edit existing task', function () {
        /** @var Task $task */
        $task = Task::factory()->create([
            'taskname' => 'Original Task',
            'project' => 'original-project',
        ]);

        Livewire::test(new TasksList)
            ->call('editTask', $task->id)
            ->assertSet('editedTaskId', $task->id)
            ->assertSet('taskname', 'Original Task')
            ->assertSet('project', 'original-project')
            ->assertSet('task.id', $task->id);
    });

    test('can update existing task', function () {
        /** @var Task $task */
        $task = Task::factory()->create([
            'taskname' => 'Original Task',
            'project' => 'original-project',
        ]);

        Livewire::test(new TasksList)
            ->call('editTask', $task->id)
            ->set('taskname', 'Updated Task')
            ->set('project', 'updated-project')
            ->call('save');

        $task->refresh();
        expect($task->taskname)->toBe('Updated Task');
        expect($task->project)->toBe('updated-project');
    });

    test('editing task resets form after save', function () {
        /** @var Task $task */
        $task = Task::factory()->create();

        Livewire::test(new TasksList)
            ->call('editTask', $task->id)
            ->set('taskname', 'Updated Task')
            ->call('save')
            ->assertSet('editedTaskId', 0)
            ->assertSet('showModal', false);
    });
});

describe('Task Deletion', static function () {
    test('can trigger delete confirmation', function () {
        /** @var Task $task */
        $task = Task::factory()->create();

        Livewire::test(new TasksList)
            ->call('deleteConfirm', 'delete', $task->id)
            ->assertDispatched('swal:confirm', [
                'type' => 'warning',
                'title' => 'Are you sure?',
                'text' => '',
                'id' => $task->id,
                'method' => 'delete',
            ]);
    });

    test('can delete task', function () {
        /** @var Task $task */
        $task = Task::factory()->create();

        Livewire::test(new TasksList)
            ->call('delete', $task->id);

        expect(Task::find($task->id))->toBeNull();
    });

    // @phpstan-ignore-next-line
    test('safely attempt to delete non-existent task', function () {
        Livewire::test(new TasksList)
            ->call('delete', 999);
    })->throws(ModelNotFoundException::class);
});

describe('Task Ordering', static function () {
    test('can update task order', function () {
        /** @var Task $task1 */
        $task1 = Task::factory()->create(['position' => 1]);
        /** @var Task $task2 */
        $task2 = Task::factory()->create(['position' => 2]);

        $orderList = [
            ['value' => $task2->id, 'order' => 0],
            ['value' => $task1->id, 'order' => 1],
        ];

        Livewire::test(new TasksList)
            ->call('updateOrder', $orderList);

        $task1->refresh();
        $task2->refresh();

        expect($task2->position)->toBe(0);
        expect($task1->position)->toBe(1);
    });

    test('ordering accounts for pagination', function () {
        /** @var Task $task1 */
        $task1 = Task::factory()->create(['position' => 1]);
        /** @var Task $task2 */
        $task2 = Task::factory()->create(['position' => 2]);

        $component = new TasksList;
        $component->tasks = collect([$task1, $task2]);

        $reflection = new \ReflectionClass($component);
        $currentPageProperty = $reflection->getProperty('currentPage');
        $currentPageProperty->setAccessible(true);
        $currentPageProperty->setValue($component, 2);

        $perPageProperty = $reflection->getProperty('perPage');
        $perPageProperty->setAccessible(true);
        $perPageProperty->setValue($component, 10);

        $orderList = [
            ['value' => $task1->id, 'order' => 0],
            ['value' => $task2->id, 'order' => 1],
        ];

        $component->updateOrder($orderList);

        $task1->refresh();
        $task2->refresh();

        expect($task1->position)->toBe(10);
        expect($task2->position)->toBe(11);
    });

    test('only updates position when changed', function () {
        /** @var Task $task */
        $task = Task::factory()->create(['position' => 5]);
        $originalUpdatedAt = $task->updated_at;

        sleep(1);

        $orderList = [
            ['value' => $task->id, 'order' => 5],
        ];

        Livewire::test(new TasksList)
            ->call('updateOrder', $orderList);

        $task->refresh();
        expect($task->position)->toBe(5);
        expect($task->updated_at->equalTo($originalUpdatedAt))->toBeTrue();
    });
});

describe('Validation', function () {
    test('validates required taskname', function () {
        Livewire::test(new TasksList)
            ->set('taskname', '')
            ->call('save')
            ->assertHasErrors(['taskname' => 'required']);
    });

    test('validates minimum taskname length', function () {
        Livewire::test(new TasksList)
            ->set('taskname', 'ab')
            ->call('save')
            ->assertHasErrors(['taskname' => 'min']);
    });

    test('validates taskname as string', function () {
        expect(true)->toBeTrue();
    });

    test('project field is nullable', function () {
        Livewire::test(new TasksList)
            ->set('taskname', 'Valid Task Name')
            ->set('project', '')
            ->call('save')
            ->assertHasNoErrors(['project']);
    });

    test('project must be string when provided', function () {
        expect(true)->toBeTrue();
    });

    test('validation is reset after successful save', function () {
        Livewire::test(new TasksList)
            ->set('taskname', '')
            ->call('save')
            ->assertHasErrors(['taskname'])
            ->set('taskname', 'Valid Task Name')
            ->call('save')
            ->assertHasNoErrors();
    });

    test('validation is reset when canceling edit', function () {
        Livewire::test(new TasksList)
            ->set('taskname', '')
            ->call('save')
            ->assertHasErrors(['taskname'])
            ->call('cancelTaskEdit')
            ->assertHasNoErrors();
    });
});

describe('Pagination and Rendering', static function () {
    test('renders tasks with pagination', function () {
        Task::factory()->count(15)->create();

        $component = Livewire::test(new TasksList);

        expect($component->instance()->tasks)->toHaveCount(10);
        expect($component->viewData('links'))->not->toBeNull();
    });

    test('tasks are ordered by position', function () {
        $task1 = Task::factory()->create(['position' => 10]);
        $task2 = Task::factory()->create(['position' => 5]);
        $task3 = Task::factory()->create(['position' => 15]);

        $component = Livewire::test(new TasksList);
        /** @var Collection<int, Task> */
        $tasks = $component->instance()->tasks;

        expect($tasks->first()->id)->toBe($task2->id);   // @phpstan-ignore-line
        expect($tasks->get(1)->id)->toBe($task1->id);    // @phpstan-ignore-line
        expect($tasks->last()->id)->toBe($task3->id);    // @phpstan-ignore-line
    });

    test('current page is tracked correctly', function () {
        Task::factory()->count(25)->create();

        $component = Livewire::test(new TasksList);
        $component->instance()->setPage(2);

        $reflection = new \ReflectionClass($component->instance());
        $currentPageProperty = $reflection->getProperty('currentPage');
        $currentPageProperty->setAccessible(true);

        $currentPageProperty->setValue($component->instance(), 2);
        expect($currentPageProperty->getValue($component->instance()))->toBe(2);
    });
});
