<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inici') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl mx-auto space-y-6">

            @foreach ($images as $image)
                <div class="bg-white rounded shadow p-4">

                    {{-- Nombre de usuario y fecha --}}
                    <div class="mb-2">
                        <span class="font-semibold">{{ $image->user->name }} {{ $image->user->surname }}</span>
                        <span class="text-gray-400 text-sm ml-2">{{ $image->created_at->diffForHumans() }}</span>
                    </div>

                    {{-- Imagen --}}
                    <a href="{{ route('images.show', $image) }}">
                        <img src="{{ file_exists(public_path('storage/' . $image->image_path)) 
                            ? asset('storage/' . $image->image_path) 
                            : asset('storage/images/placeholder-image.png') }}"
                             alt="{{ $image->description }}"
                             class="w-full object-cover rounded shadow-inner hover:opacity-90 transition-opacity">
                    </a>

                    {{-- Descripción --}}
                    <p class="mt-2 text-gray-700 leading-relaxed">{{ $image->description }}</p>

                    {{-- Like en home --}}
                    <div class="mt-4 flex items-center gap-3 text-sm text-gray-500 border-t border-gray-50 pt-2">
                        @auth
                            <button
                                class="like-btn text-xl focus:outline-none hover:scale-110 transition-transform"
                                data-url="{{ route('likes.toggle', $image) }}"
                                data-liked="{{ $image->likes->where('user_id', Auth::id())->count() ? 'true' : 'false' }}">
                                <span class="like-icon">
                                    {{ $image->likes->where('user_id', Auth::id())->count() ? '❤️' : '🤍' }}
                                </span>
                            </button>
                        @else
                            <span class="text-xl">🤍</span>
                        @endauth

                        <span class="like-count font-bold text-gray-900">{{ $image->likes->count() }}</span>
                        <a href="{{ route('images.show', $image) }}" class="flex items-center gap-1 hover:text-indigo-600 transition-colors ml-2">
                            <span>💬</span>
                            <span>{{ $image->comments->count() }}</span>
                        </a>
                    </div>
                </div>
            @endforeach

            {{-- Paginación --}}
            <div class="mt-4">
                {{ $images->links() }}
            </div>

        </div>
    </div>

    @auth
    <script>
        document.querySelectorAll('.like-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const url   = btn.dataset.url;
                const icon  = btn.querySelector('.like-icon');
                const count = btn.parentElement.querySelector('.like-count');

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

                    btn.style.transform = 'scale(1.3)';
                    setTimeout(() => btn.style.transform = 'scale(1)', 200);
                });
            });
        });
    </script>
    @endauth
</x-app-layout>
