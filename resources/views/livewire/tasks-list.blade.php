<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xs sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- ToDo: search by project --}}
                    {{-- <input wire:model.live.debounce="searchColumns.project" type="text" placeholder="search projects"
                        class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" /> --}}

                    <div class="overflow-hidden overflow-x-auto mb-4 min-w-full align-middle sm:rounded-md">
                        <table class="min-w-full border divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    {{-- 2 *header* (only) columns for long label is more responsive --}}
                                    <th colspan="2" class="px-6 py-3 w-10 text-right bg-gray-50">
                                        <span class="text-xs font-medium tracking-wider leading-4 text-gray-500 uppercase">Priority</span>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span class="text-xs font-medium tracking-wider leading-4 text-gray-500 uppercase">Task</span>
                                    </th>
                                    <th class="px-6 py-3 text-left bg-gray-50">
                                        <span class="text-xs font-medium tracking-wider leading-4 text-gray-500 uppercase">Project</span>
                                    </th>
                                    <th class="px-6 pt-2 text-center bg-gray-50 w-56">
                                        <x-primary-button wire:click="openModal" type="button" class="mb-4">Add</x-primary-button>
                                    </th>
                                </tr>
                            </thead>

                            <tbody wire:sortable="updateOrder" class="bg-white divide-y divide-gray-200 divide-solid">
                                 @foreach($tasks as $task)
                                    <tr class="bg-white" wire:sortable.item="{{ $task->id }}" wire:key="{{ $loop->index }}">
                                        <td class="px-6">
                                            {{-- up/down buttons --}}
                                            <button wire:sortable.handle class="cursor-move focus:ring-2 hover:bg-gray-300">&#x21c5;</button>
                                        </td>

                                        {{-- Inline Edit Start --}}
                                        <td class="@if($editedTaskId !== $task->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <x-text-input wire:model.live.debounce="taskname" id="taskname" class="py-2 pr-4 pl-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-hidden focus:border-blue-400" />
                                            @error('taskname')
                                                <span class="text-sm text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td class="@if($editedTaskId !== $task->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            <x-text-input wire:model="project" id="project" class="py-2 pr-4 pl-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-hidden focus:border-blue-400" />
                                            @error('project')
                                                <span class="text-sm text-red-500">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        {{-- Inline Edit End --}}

                                        {{-- Show Task Name/Project Start --}}
                                        <td class="@if($editedTaskId === $task->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $task->position }}
                                        </td>
                                        <td class="@if($editedTaskId === $task->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $task->taskname }}
                                        </td>
                                        <td class="@if($editedTaskId === $task->id) hidden @endif px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            {{ $task->project }}
                                        </td>
                                        {{-- Show Task Name/Project End --}}

                                        <td class="px-6 py-4 text-sm leading-5 text-gray-900 whitespace-no-wrap">
                                            @if($editedTaskId === $task->id)
                                                <x-primary-button wire:click="save">
                                                    Save
                                                </x-primary-button>
                                                <div class="h-1.5">&nbsp;</div>
                                                <x-primary-button wire:click="cancelTaskEdit">
                                                    Cancel
                                                </x-primary-button>
                                            @else
                                                <x-primary-button wire:click="editTask({{ $task->id }})">
                                                    Edit
                                                </x-primary-button>
                                                <x-danger-button wire:click="deleteConfirm('delete', {{ $task->id }})"
                                                >
                                                    Delete
                                                </x-danger-button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {!! $links !!}

                </div>
            </div>
        </div>
    </div>

    <div class="@if (!$showModal) hidden @endif flex items-center justify-center fixed left-0 bottom-0 w-full h-full bg-gray-800 bg-opacity-90">
        <div class="w-1/2 bg-white rounded-lg">
            <form wire:submit="save" class="w-full">
                <div class="flex flex-col items-start p-4">
                    <div class="flex items-center pb-4 mb-4 w-full border-b">
                        <div class="text-lg font-medium text-gray-900">Create Task</div>
                        <svg wire:click="$set('showModal', false)"
                             class="ml-auto w-6 h-6 text-gray-700 cursor-pointer fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18">
                            <path d="M14.53 4.53l-1.06-1.06L9 7.94 4.53 3.47 3.47 4.53 7.94 9l-4.47 4.47 1.06 1.06L9 10.06l4.47 4.47 1.06-1.06L10.06 9z" />
                        </svg>
                    </div>
                    <div class="mb-2 w-full">
                        <label class="block text-sm font-medium text-gray-700" for="taskname">
                            Task Name
                        </label>
                        <input wire:model.live.debounce="taskname" id="taskname"
                               class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-hidden focus:border-blue-400" />
                        @error('taskname')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-2 w-full">
                        <label class="block text-sm font-medium text-gray-700" for="project">
                            Project
                        </label>
                        <input wire:model="project" id="project"
                               class="py-2 pr-4 pl-2 mt-2 w-full text-sm rounded-lg border border-gray-400 sm:text-base focus:outline-hidden focus:border-blue-400" />
                        @error('project')
                            <span class="text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mt-4 ml-auto">
                        <button class="px-4 py-2 font-bold text-white bg-blue-500 rounded-sm hover:bg-blue-700" type="submit">
                            Create
                        </button>
                        <button wire:click="$set('showModal', false)" class="px-4 py-2 font-bold text-white bg-gray-500 rounded-sm" type="button" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>