@extends('layouts.app')

@section('title')
    Home Page
@endsection

@section('content')
    <section class="content">
        <h3>DASHBOARD</h3>
        <div class="row clearfix">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10"></div>
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
                <div class="card">
                    <div class="header">
                        <h2>Tags</h2>
                    </div>
                    <div class="body">
                            @foreach($tags as $tag)
                                @if($tag->posts_count >= 15)
                                    <button class="btn bg-green waves-effect m-b-5" type="button">{{ $tag->tag_name }} <span class="badge">{{ $tag->posts_count }}</span></button><br>
                                @elseif($tag->posts_count >= 10)
                                    <button class="btn bg-blue waves-effect m-b-5" type="button">{{ $tag->tag_name }} <span class="badge">{{ $tag->posts_count }}</span></button><br>
                                @elseif($tag->posts_count >= 5)
                                    <button class="btn bg-cyan waves-effect m-b-5" type="button">{{ $tag->tag_name }} <span class="badge">{{ $tag->posts_count }}</span></button><br>
                                @else
                                    @break
                                @endif
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
