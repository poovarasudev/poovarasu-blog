@extends('layouts.app')

@section('title')
    Show Page
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <div class="header">
                                <div class="row">
                                    <div class="col-lg-6" id="post-title"><h3 id="blog-heading">{{ $post->title }}</h3>
                                    </div>
                                    <div class="col-lg-6">
                                        @can('delete post')
                                        <button type="button" id="delete" style="margin-right: 5px"
                                                class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right"
                                                data-id="{{$post->id}}">
                                            <i class="material-icons">delete</i></button>
                                        @endcan
                                        @can('edit post')
                                        <button type="button" style="margin-right: 7px" id="edit-btn"
                                                data-toggle="modal" data-target="#largeModal"
                                                class="btn btn-default btn-circle waves-effect waves-circle waves-float pull-right">
                                            <i class="material-icons">edit</i></button>
                                        @endcan
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <p>posted on <b>{{ $post->created_at->format('d/m/y') }}</b></p>
                                    </div>
                                </div>
                            </div>
                            <!-- Basic Example -->
                            @php
                                $images = $post->images->pluck('image_name');
                                $tags = $post->tags->pluck('tag_name');
                            @endphp
                            <div class="body">
                                @if(!$images->isEmpty())
                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-6">
                                            <div id="carousel-example-generic" class="carousel slide"
                                                 data-ride="carousel">
                                                <!-- Wrapper for slides -->
                                                <div class="carousel-inner" role="listbox">
                                                    @for($iteration = 0;$iteration < count($images);$iteration++)
                                                        @if($iteration == 0)
                                                            <div class="item active">
                                                                <img src="{{ asset('storage/posts_images/'.$images[$iteration]) }}"/>
                                                            </div>
                                                        @else
                                                            <div class="item">
                                                                <img src="{{ asset('storage/posts_images/'.$images[$iteration]) }}"/>
                                                            </div>
                                                        @endif
                                                    @endfor
                                                </div>

                                                <!-- Controls -->
                                                <a class="left carousel-control" href="#carousel-example-generic"
                                                   role="button"
                                                   data-slide="prev">
                                                    <span class="glyphicon glyphicon-chevron-left"
                                                          aria-hidden="true"></span>
                                                    <span class="sr-only">Previous</span>
                                                </a>
                                                <a class="right carousel-control" href="#carousel-example-generic"
                                                   role="button"
                                                   data-slide="next">
                                                    <span class="glyphicon glyphicon-chevron-right"
                                                          aria-hidden="true"></span>
                                                    <span class="sr-only">Next</span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="col-sm-3"></div>
                                    </div>
                                    <hr><br>
                            @endif

                            <!-- #END# Basic Example -->
                                <div id="post-description" class="row-lg-6">
                                    <p id="blog-post-description" disabled>{!! $post->description !!}</p>
                                </div>
                                @can('view tag')
                                    @if(!$tags->isEmpty())
                                            <hr>
                                        <div class="row">
                                            <h3>Tags </h3>
                                            <div class="tagClass">
                                            @foreach($tags as $tag)
                                                <span id="showTag" class="badge badge-secondary">{{ $tag }}</span>
                                            @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endcan
                            </div>
                            @if(auth()->user()->can('create comment') || auth()->user()->can('view comment'))
                                <div class="footer">
                                    <hr>
                                    @include('comments.create')
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            @include('edit')

        </div>
    </section>
@endsection



@section('script')
    @include('comments.script')
    <script>

        // Delete
        $("#delete").click(function () {
            var id = $(this).data('id');
            swal({
                    title: "Are you sure to delete ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "No, cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function (isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            url: '/post/' + id,
                            method: 'delete',
                            data: {
                                '_token': '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                swal({
                                    title: "Success!",
                                    text: response.message,
                                    icon: "success",
                                    confirmButtonClass: "btn-success",
                                    confirmButtonText: "Ok!",
                                    closeOnConfirm: false,
                                }, function () {
                                    window.location = "/post";
                                });
                            },
                            error: function (response) {
                                swal({
                                    title: "Warning!",
                                    text: response.responseJSON.message,
                                    icon: "warning",
                                });
                            }
                        });
                    }
                });

        });

        // Edit page
        $('#update-btn').on('click', function () {
            var id = $(this).data('id');
            var title = $('#modal-title').val();
            var tagInput = $('#tag').val();
            var description = CKEDITOR.instances["ckeditor"].document.$.body.innerHTML;
            var formData = new FormData;
            formData.append('id', id);
            formData.append('title', title);
            formData.append('description', description);
            formData.append('tagInput', tagInput);
            formData.append('_method', 'patch');
            formData.append('_token', '{{ csrf_token() }}');
            $.ajax({
                type: "post",
                url: "/post/" + id,
                dataType: 'json',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#blog-heading').html(title);
                    $('#post-description').html(description);
                    var tagArray = tagInput.split(',');
                    tag = '';
                    jQuery.each(tagArray, function (key, value) {
                       tag += "<span id=\"showTag\" class=\"badge badge-secondary m-r-5\">" +value+ "</span>";
                    });
                    $('.tagClass').html(tag);
                    $('#modal-close').click();
                },
                error: function (response) {
                    console.log(response);
                    var error = '<ul>';
                    if (response.status == 422) {
                        jQuery.each(response.responseJSON.errors, function (key, value) {
                            jQuery.each(value, function (key, message) {
                                error += '<li>' + message + '</li>';
                            });
                        });
                    } else if (response.status == 404) {
                        errorType = 'error';
                        error += '<li>The requested data not found. Reload the page and try again!</li>';
                    } else {
                        errorType = 'error';
                        error += '<li>' + response.responseJSON.error + '</li>';
                    }
                    error += '</ul>';
                    $("#modal-error-alert").html(error);
                    $("#modal-error-alert").css('display', 'block');
                }
            });
        });

        //CKEditor
        CKEDITOR.replace('ckeditor');
        CKEDITOR.config.height = 200;
    </script>
@endsection
