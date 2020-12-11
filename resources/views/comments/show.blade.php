@can('view-comment')
    @php $comments = $post->comments->sortByDesc('created_at') @endphp
    @foreach($comments as $comment)
        <div class="row" id="comment-{{ $comment->id }}">
            <div class="col-sm-10">
                <div class="bs-example" data-example-id="media-alignment">
                    <div class="media">
                        <div class="media-left">
                            <img src="{{ asset('/asset/images/user.png') }}" width="60" height="60" alt="User" class="mr-3">
                        </div>
                        <div class="media-body">
                            <p class="media-heading">{{ $comment->created_at->diffForHumans() }}</p>
                            <div class="comment-content"><p><b>{{ $comment->comment }}</b></p></div>
                            @can('edit-comment')
                                <a class="m-r-10" id="comment-link" href="#" onclick="editContent('{{ $comment->id }}', '{{ $comment->comment }}')">edit</a>
                            @endcan
                            @can('delete-comment')
                                <a href="#" id="comment-link" onclick="deleteComment({{ $comment->id }})">delete</a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endcan
@include('comments.edit')
