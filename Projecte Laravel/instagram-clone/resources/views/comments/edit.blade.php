<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Comentari') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 text-gray-900">
            <form method="POST" action="{{ route('comments.update', $comment) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="content" :value="__('El teu comentari')" />
                    <textarea id="content" name="content" rows="4"
                              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150">{{ old('content', $comment->content) }}</textarea>
                    <x-input-error :messages="$errors->get('content')" class="mt-2" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                        {{ __('Actualitzar comentari') }}
                    </x-primary-button>
                    <a href="{{ route('images.show', $comment->image_id) }}" 
                       class="text-sm text-gray-600 hover:text-gray-900 underline">
                        {{ __('Cancel·lar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
