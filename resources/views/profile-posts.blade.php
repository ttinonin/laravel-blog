<x-profile :postCount="$postCount" :username="$username" :isFollowing="$isFollowing">
  <div class="list-group">
    @foreach ($posts as $post)
    <a href="/post/{{ $post->id }}" class="list-group-item list-group-item-action">
        <img class="avatar-tiny" src="{{ auth()->user()->avatar }}" />
        <strong>{{ $post->title }}</strong> on {{ $post->created_at->format('n/j/Y') }}
    </a>
    @endforeach
  </div>
</x-profile>