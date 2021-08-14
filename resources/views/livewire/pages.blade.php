<div class="p-6">

    <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
        <x-jet-button wire:click="createShowModal">
            {{ __('Create') }}
        </x-jet-button>
    </div>

    {{-- The data table --}}
    <div class="flex flex-col  px-4 py-3 text-right sm:px-6">
        <div class=" -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block w-full">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Title
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Link
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Content
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    &nbsp;
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @if ($data->count()) @foreach ($data as $item)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $item->title }}
                                </td>
                                <td class=" px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $item->slug }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                    {{ $item->content }}
                                </td>
                                <td class=" px-6 py-4 whitespace-nowrap text-gray-500 text-right">
                                    <x-jet-button wire:click="updateShowModal({{ $item->id }})">
                                        {{ __('Update') }}
                                    </x-jet-button>
                                    <x-jet-danger-button wire:click="deleteShowModal({{ $item->id }})">
                                        {{ __('Delete') }}
                                    </x-jet-danger-button>
                                </td>
                            </tr>
                            @endforeach @else
                            <tr>
                                <td class=" px-6 py-4 whitespace-nowrap text-gray-500 text-right" colspan="4">
                                    No Results Found
                                </td>
                            </tr>
                            @endif

                            <!-- More items... -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="pt-8">
            {{ $data->links() }}
        </div>
    </div>

    {{-- Modal Form --}}
    <x-jet-dialog-modal wire:model="modalFormVisible">
        <x-slot name="title">
            {{ __('Save Page') }}
        </x-slot>

        <x-slot name="content">
            <div class="my-4">
                <x-jet-label for="title" value="{{ __('Title') }}" />
                <x-jet-input id="title" class="block mt-1 w-full" type="text" wire:model.debounce.800ms="title" />
                @error('title')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <input wire:model="title" />

            <div class="my-4">
                <x-jet-label for="slug" value="{{ __('Slug') }}" />
                <div class="mt-1 flex rounded-md shadow-sm">
                    <span
                        class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                        http://laravel12.localhost/
                    </span>
                    <input type="text" id="slug" wire:model.debounce.800ms="slug"
                        class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md" />
                </div>
                @error('slug')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <input wire:model="slug" />

            <div class="my-4">
                <x-jet-label for="content" value="{{ __('Content') }}" />
                <textarea wire:model.debounce.800ms="content" id="content" rows="3"
                    class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm flex-1 block w-full rounded-r-md "></textarea>
                @error('content')
                <span class="error">{{ $message }}</span>
                @enderror
            </div>
            <input wire:model="content" />
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            @if($modelId)
            <x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
                {{ __('Update') }}
            </x-jet-button>
            @else
            <x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
                {{ __('Create') }}
            </x-jet-button>
            @endif

        </x-slot>

    </x-jet-dialog-modal>
</div>