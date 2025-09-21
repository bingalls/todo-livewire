<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;

class TasksList extends Component
{
    use WithPagination;

    public ?Task $task = null;

    public string $taskname = '';
    public string $project = '';

    public Collection $tasks;

    public bool $showModal = false;

    public array $active;

    public int $editedTaskId = 0;

    public int $currentPage = 1;

    public int $perPage = 10;

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function updatedName(): void
    {
        $this->project = Str::project($this->taskname);
    }

    public function save(): void
    {
        $this->validate();

        if (is_null($this->task)) {
            $position = Task::max('position') + 1;
            Task::create(array_merge($this->only('taskname', 'project'), ['position' => $position]));
        } else {
            $this->task->update($this->only('taskname', 'project'));
        }

        $this->resetValidation();
        $this->reset('showModal', 'editedTaskId');
    }

    public function cancelTaskEdit(): void
    {
        $this->resetValidation();
        $this->reset('editedTaskId');
    }

    public function toggleIsActive(int $taskId): void
    {
        Task::where('id', $taskId)->update([
            'is_active' => $this->active[$taskId],
        ]);
    }

    public function updateOrder(array $list): void
    {
        foreach ($list as $item) {
            $cat = $this->tasks->firstWhere('id', $item['value']);
            $order = $item['order'] + (($this->currentPage - 1) * $this->perPage);

            if ($cat['position'] != $order) {
                Task::where('id', $item['value'])->update(['position' => $order]);
            }
        }
    }

    public function editTask(int $taskId): void
    {
        $this->editedTaskId = $taskId;

        $this->task = Task::find($taskId);
        $this->taskname = $this->task->taskname;
        $this->project = $this->task->project;
    }

    public function deleteConfirm(string $method, $id = null): void
    {
        $this->dispatch('swal:confirm', [
            'type'   => 'warning',
            'title'  => 'Are you sure?',
            'text'   => '',
            'id'     => $id,
            'method' => $method,
        ]);
    }

    #[On('delete')]
    public function delete(int $id): void
    {
        Task::findOrFail($id)->delete();
    }

    public function render(): View
    {
        $tasks = Task::orderBy('position')->paginate($this->perPage);
        $links = $tasks->links();
        $this->currentPage = $tasks->currentPage();
        $this->tasks = collect($tasks->items());

        $this->active = $this->tasks->mapWithKeys(
            fn (Task $item) => [$item['id'] => (bool) $item['is_active']]
        )->toArray();

        return view('livewire.tasks-list', [
            'links' => $links,
        ]);
    }

    protected function rules(): array
    {
        return [
            'taskname' => ['required', 'string', 'min:3'],
            'project' => ['nullable', 'string'],
        ];
    }
}
