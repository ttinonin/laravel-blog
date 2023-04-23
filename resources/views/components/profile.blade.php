<x-layout>
    <div class="container py-md-5 container--narrow">
        <h2>
          <img class="avatar-small" src="{{ auth()->user()->avatar }}" /> {{ $username }}
          @auth
          @if (!$isFollowing && auth()->user()->username != $username)
          <form class="ml-2 d-inline" action="/create-follow/{{ $username }}" method="POST">
            @csrf
            <button class="btn btn-primary btn-sm">Follow <i class="fas fa-user-plus"></i></button>
          </form>
          @endif

          @if (auth()->user()->username == $username)
            <a href="/manage-avatar" class="btn btn-secondary btn-sm">Manage Avatar</a>                
          @endif

          @if ($isFollowing)
          <form class="ml-2 d-inline" action="/remove-follow/{{ $username }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button>
          </form>
          @endif
          @endauth
        </h2>

        <div class="profile-slot-content">
            {{ $slot }}
        </div>
  
        <div class="profile-nav nav nav-tabs pt-2 mb-4">
          <a href="#" class="profile-nav-link nav-item nav-link active">Posts: {{ $postCount }}</a>
          <a href="#" class="profile-nav-link nav-item nav-link">Followers: 3</a>
          <a href="#" class="profile-nav-link nav-item nav-link">Following: 2</a>
        </div>
    </div>
</x-layout>