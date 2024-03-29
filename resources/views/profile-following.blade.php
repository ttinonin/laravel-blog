<x-profile :sharedData="$sharedData" doctitle="Who {{ $sharedData['username'] }} Follows">
    <div class="list-group">
      @foreach ($followings as $following)
      <a href="/profile/{{ $following->userBeingFollowed->username }}" class="list-group-item list-group-item-action">
          <img class="avatar-tiny" src="{{ $following->userBeingFollowed->avatar }}" />
          {{ $following->userBeingFollowed->username }}
      </a>
      @endforeach
    </div>
  </x-profile>