<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detall de la imatge') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Usuario y fecha --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    @if($image->user->image)
                        <img src="{{ asset('storage/' . $image->user->image) }}" class="w-10 h-10 rounded-full object-cover border border-gray-100 shadow-sm">
                    @else
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                            {{ strtoupper(substr($image->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-gray-900 leading-none">@ {{ $image->user->nick ?? $image->user->name }}</p>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-tighter">{{ $image->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                {{-- Menú de 3 puntos (Alpine.js) --}}
                @if(Auth::id() === $image->user_id)
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="text-gray-400 hover:text-gray-600 focus:outline-none p-1 transition-colors">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
                        </svg>
                    </button>

                    <div x-show="open" 
                         @click.outside="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-xl shadow-xl z-50 overflow-hidden py-1">
                        
                        <a href="{{ route('images.edit', $image) }}" 
                           class="flex items-center gap-2 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <span>✏️</span> {{ __('Editar Imatge') }}
                        </a>

                        <form method="POST" action="{{ route('images.destroy', $image) }}"
                              onsubmit="return confirm('Segur que vols eliminar aquesta imatge definitivament?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center gap-2 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <span>🗑️</span> {{ __('Eliminar imatge') }}
                            </button>
                        </form>
                    </div>
                </div>
                @endif
            </div>

            {{-- Imagen --}}
            <div class="bg-gray-50">
                <img src="{{ file_exists(public_path('storage/' . $image->image_path))
                    ? asset('storage/' . $image->image_path)
                    : asset('storage/images/placeholder-image.png') }}"
                     class="w-full h-auto">
            </div>

            <div class="p-4">
                {{-- Likes reactivos --}}
                <div class="flex items-center gap-2 mb-3">
                    @auth
                        <button
                            id="like-btn"
                            data-url="{{ route('likes.toggle', $image) }}"
                            data-liked="{{ $image->likes->where('user_id', Auth::id())->count() ? 'true' : 'false' }}"
                            class="text-2xl focus:outline-none transition-transform hover:scale-110">
                            <span id="like-icon">
                                {{ $image->likes->where('user_id', Auth::id())->count() ? '❤️' : '🤍' }}
                            </span>
                        </button>
                    @else
                        <span class="text-2xl">🤍</span>
                    @endauth

                    <span id="like-count" class="text-gray-900 font-bold">
                        {{ $image->likes->count() }}
                    </span>
                    <span class="text-xs text-gray-500 uppercase font-bold tracking-tighter">{{ __('likes') }}</span>
                </div>

                <script>
                    @auth
                    document.getElementById('like-btn').addEventListener('click', function () {
                        const btn    = this;
                        const url    = btn.dataset.url;
                        const icon   = document.getElementById('like-icon');
                        const count  = document.getElementById('like-count');

                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            icon.textContent  = data.liked ? '❤️' : '🤍';
                            count.textContent = data.count;
                            btn.dataset.liked = data.liked ? 'true' : 'false';

                            // Animación de pulso
                            btn.style.transform = 'scale(1.4)';
                            setTimeout(() => btn.style.transform = 'scale(1)', 200);
                        });
                    });
                    @endauth
                </script>

                {{-- Descripción --}}
                <div class="text-sm text-gray-800 mb-4">
                    <span class="font-bold mr-2">{{ $image->user->nick ?? $image->user->name }}</span>
                    <span class="leading-relaxed">{{ $image->description }}</span>
                </div>

                {{-- Formulario añadir comentario --}}
                @auth
                <div class="mt-4 border-t border-gray-50 pt-4">
                    <form method="POST" action="{{ route('comments.store', $image) }}" class="space-y-3">
                        @csrf
                        <textarea name="content" rows="2" placeholder="Afegeix un comentari..."
                                  class="block w-full border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg text-sm transition duration-150">{{ old('content') }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-1" />
                        <div class="flex justify-end">
                            <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 text-[10px] py-1 px-3">
                                {{ __('Publicar') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
                @endauth

                {{-- Comentarios --}}
                <div class="mt-6 border-t border-gray-50 pt-4 space-y-4">
                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider">{{ __('Comentaris') }}</h3>

                    <div class="max-h-80 overflow-y-auto space-y-4 pr-2 custom-scrollbar">
                        @forelse($image->comments as $comment)
                            <div class="group flex flex-col gap-1">
                                <div class="flex justify-between items-start gap-2 text-sm">
                                    <div class="flex flex-wrap items-baseline gap-x-2">
                                        <span class="font-bold text-gray-900">{{ $comment->user->nick ?? $comment->user->name }}</span>
                                        <span class="text-gray-700 leading-snug">{{ $comment->content }}</span>
                                    </div>

                                    @if(Auth::id() === $comment->user_id)
                                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity shrink-0">
                                            <a href="{{ route('comments.edit', $comment) }}"
                                               class="text-indigo-500 hover:text-indigo-700 text-[10px] font-bold uppercase tracking-tighter">
                                                {{ __('Editar') }}
                                            </a>
                                            <form method="POST" action="{{ route('comments.destroy', $comment) }}"
                                                  onsubmit="return confirm('Segur que vols eliminar aquest comentari?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-400 hover:text-red-600 text-[10px] font-bold uppercase tracking-tighter">
                                                    {{ __('Borrar') }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-[10px] text-gray-400">{{ $comment->created_at->diffForHumans() }}</div>
                            </div>
                        @empty
                            <p class="text-gray-400 text-xs italic">{{ __('Encara no hi ha comentaris.') }}</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
