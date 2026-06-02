<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova Imatge') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6 text-gray-900">
            <form method="POST" action="{{ route('images.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <x-input-label for="image_path" :value="__('Selecciona la imatge')" />
                    <input id="image_path" name="image_path" type="file" accept="image/*"
                           class="block mt-1 w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100" required />
                    <x-input-error :messages="$errors->get('image_path')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Descripció')" />
                    <textarea id="description" name="description" rows="4"
                              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150"
                              placeholder="Escriu un peu de foto...">{{ old('description') }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('home') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">{{ __('Cancel·lar') }}</a>
                    <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                        {{ __('Publicar imatge') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
