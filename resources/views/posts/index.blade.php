@extends('layouts.app')
@section('content')

<div class="card">
    <div class="card-header">
        Posts
    </div>
    @foreach ($posts as $post)
        <div class="card-body">
            <h5 class="card-title"><a class="card-link" href="{{ route('posts.show', $post->id ) }}"><b>{{ $post->title }}</a></h5>
            <div class="card-subtitle mb-2 text-muted">Page {{ $posts->currentPage() }} of {{ $posts->lastPage() }}</div>
            <p class="card-text">{{  str_limit($post->body, 100) }} {{-- Limit teaser to 100 characters --}}</p>
            <a href="{{ route('posts.show', $post->id ) }}" class="btn btn-primary">Read More</a>
        </div>
    @endforeach
</div>
<div class="text-center">
    {!! $posts->links() !!}
</div>

@endsection
