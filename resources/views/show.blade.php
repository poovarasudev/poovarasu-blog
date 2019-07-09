@extends('layouts.app')

@section('title')
    Show Page
@endsection

@section('content')
    <div class="container">
        <div class="form-group">
            <label for="title">Title :</label>
            <input type="text" class="form-control" id="title" disabled value="{{ $post->title }}">
        </div>
        <div class="form-group">
            <label for="description">Description :</label>
            <textarea class="form-control" rows="4" id="description" disabled>{{ $post->description }}</textarea>
        </div>
        <div class="form-group">
            <label for="email">Published by :</label>
            <input type="text" class="form-control" id="email" disabled value="{{ $post->email }}">
        </div>

    </div>

@endsection