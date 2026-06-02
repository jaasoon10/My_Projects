<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Imatge') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden p-6 text-gray-900">
            
            {{-- Imagen actual --}}
            <div class="mb-8">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">{{ __('Imatge actual') }}</p>
                <div class="relative inline-block">
                    <img src="{{ file_exists(public_path('storage/' . $image->image_path)) 
                        ? asset('storage/' . $image->image_path) 
                        : asset('storage/images/placeholder-image.png') }}"
                         class="w-full h-auto max-h-64 rounded-lg shadow-inner border border-gray-200 object-cover">
                </div>
            </div>

            <form method="POST" action="{{ route('images.update', $image) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="image_path" :value="__('Nova imatge (opcional)')" />
                    <input id="image_path" name="image_path" type="file" accept="image/*"
                           class="block mt-1 w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100" />
                    <x-input-error :messages="$errors->get('image_path')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Descripció')" />
                    <textarea id="description" name="description" rows="4"
                              class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm transition duration-150"
                              placeholder="Actualitza el peu de foto...">{{ old('description', $image->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between pt-4">
                    <div class="flex items-center gap-4">
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Guardar canvis') }}
                        </x-primary-button>
                        <a href="{{ route('images.show', $image) }}" class="text-sm text-gray-600 hover:text-gray-900 underline">{{ __('Cancel·lar') }}</a>
                    </div>
                </div>
            </form>

            {{-- Eliminar --}}
            <div class="mt-8 border-t border-gray-100 pt-6">
                <div class="bg-red-50 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <h4 class="text-sm font-bold text-red-800">{{ __('Zona de perill') }}</h4>
                        <p class="text-xs text-red-600">{{ __('Aquesta acció no es pot desfer.') }}</p>
                    </div>
                    <form method="POST" action="{{ route('images.destroy', $image) }}"
                          onsubmit="return confirm('Segur que vols eliminar aquesta imatge definitivament?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold py-2 px-4 rounded-full transition-colors shadow-sm">
                            {{ __('Eliminar imatge') }}
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
