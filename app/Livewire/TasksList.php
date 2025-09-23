<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class TasksList extends Component
{
    use WithPagination;

    private int $currentPage = 1;
    private int $perPage = 10;

    public ?Task $task = null;
    public Collection $tasks;
    public bool $showModal = false;
    public int $editedTaskId = 0;
    public string $project = '';
    public string $taskname = '';

    public function openModal(): void
    {
        $this->showModal = true;
    }

    public function updatedName(): void
    {
        $this->project = Str::slug($this->taskname);
    }

    public function save(): void
    {
        $this->validate();

        if (is_null($this->task)) {
            $position = (int)Task::max('position') + 1;
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

    public function updateOrder(array $list): void
    {
        foreach ($list as $item) {
            $cat = $this->tasks->firstWhere('id', $item['value']);
            $order = $item['order'] + (($this->currentPage - 1) * $this->perPage);

            if ($cat['position'] != $order) {
                $this->task->where('id', $item['value'])->update(['position' => (int)$order]);
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

    /** @noinspection PhpUnused */
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

        return view('livewire.tasks-list', [
            'links' => $links,
        ]);
    }

    #[ArrayShape(['taskname' => 'string', 'project' => 'string',])]
    protected function rules(): array
    {
        return [
            'taskname' => ['required', 'string', 'min:3',],
            'project' => ['nullable', 'string',],
        ];
    }
}
