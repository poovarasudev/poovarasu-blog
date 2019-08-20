@extends('layouts.app')

@section('title')
    Create Page
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Create your POST
                            </h2>
                        </div>
                        <div class="body">
                            <form action="/post" id="postForm" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                {{ $error }}<br>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <label for="title">Title</label>
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="text" name="title" id="title" class="form-control" placeholder="Title" minlength="5" required value="{{ old('title') }}">
                                    </div>
                                </div>
                                <label for="description">Description</label>
                                <div class="form-group">
                                    <textarea name="description" data-minlength="15" id="ckeditor">{{ old('description') }}</textarea>
                                </div>
                                @can('create-tag')
                                    <div class="form-group demo-tagsinput-area">
                                        <label for="tag">Tags</label>
                                        <div class="form-line">
                                            <input type="text" name="tagInput" class="form-control tags-input" data-role="tagsinput" id="tag" multiple size="100">
                                        </div>
                                    </div>
                                @endcan
                                <label for="image_name">Upload images(optional)</label>
                                <div class="form-group image-upload">
                                    <input name="image_name[]" type="file" multiple class="form-control"/>
                                </div>
                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">CREATE</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script type="text/javascript">

        //CKEditor
        CKEDITOR.replace('ckeditor');
        CKEDITOR.config.height = 200;
    </script>
@endsection
