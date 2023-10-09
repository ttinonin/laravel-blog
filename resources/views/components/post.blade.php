<a href="/post/{{ $post->id }}" class="list-group-item list-group-item-action">
    <img class="avatar-tiny" src="{{ $post->user->avatar }}" />
    
    @if(!isset($hideAuthor))
        {{ $post->user->username }}: 
    @endif
    <strong>{{ $post->title }}</strong>  <span class="text-muted small">on {{ $post->created_at->format('n/j/Y') }}</span>
</a>