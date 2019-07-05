@extends('layouts.app')

@section('title')
    Welcome Page
@endsection

@section('content')

    <div class="container">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Published by</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr class="clickable-row  data-href='url://'">
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->description }}</td>
                    <td>{{ $post->email }}</td>
                    <td><a href="/post/{{ $post->id }}">view</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
