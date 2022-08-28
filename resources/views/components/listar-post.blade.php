<div>
  @if($posts->count())
    <div class="grid grid-cols-3 gap-6">
        @foreach ($posts as $post)
            <div>
                <a href="{{ route('posts.show', ['post' => $post, 'user' => $post->user]) }}">
                    <img class="rounded" src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen Post {{ $post->titulo }}">
                </a>
            </div>
        @endforeach
    </div>

    <div class="my-10"> <!--Necesario para la paginación-->
        {{ $posts->links('pagination::tailwind') }}
    </div>
  @else
    <p class="text-center">Aún no hay publicaciones, sigue a alguien para que te aparezcan sus publicaciones</p>
  @endif
</div>