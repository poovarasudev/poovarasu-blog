@extends('layouts.app')

@section('title')
    Edit Page
@endsection

@section('content')

    <div class="container">
        <form method="post" action="/post/{{ $post->id }}">
            {{ method_field('patch') }}
            {{ csrf_field() }}
            <div class="form-group">
                <label for="title">Title :</label>
                <input type="text" name="title" class="form-control" id="title" value="{{ $post->title }}">
            </div>
            <div class="form-group">
                <label for="description">Description :</label>
                <textarea class="form-control" name="description" rows="4" id="description">{{ $post->description }}</textarea>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-lg">Update post</button>
            </div>
        </form>
    </div>

@endsection
