@extends('layouts.app')

@section('title')
    Show Page
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="showpage col-sm-8">
                <div class="row">
                    <div class="titles"><b>Title :<br></b></div>
                    <p class="contents"><br>{{ $post->title }}<br><br></p>
                </div>
                <div class="row">
                    <div class="titles"><b>Description : </b></div>
                    <p class="contents"><br>{{ $post->description }}<br><br></p>
                </div>
                <div class="row">
                    <div class="titles"><b>Published by : </b></div>
                    <p class="contents"><br>{{ $post->email }}</p>
                </div>
            </div>
        </div>

    </div>

@endsection