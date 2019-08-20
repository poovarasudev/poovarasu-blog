<h2>Comments</h2><br>
@can('create-comment')
    <div class="row">
        <div class="col-lg-1 align-right">
            <img src="{{ asset('/asset/images/user.png') }}" width="60" height="60" alt="User">
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <textarea type="text" name="comment" id="comment" data-id="{{ $post->id }}" class="add-comment" maxlength="100"
                          placeholder="Comment" rows="5" cols="100" oninput="duringInput()"></textarea>
                <div class="alert alert-danger" id="comment-error-alert" style="display: none"></div>
            </div>
        </div>
        <div class="col-lg-2">
            <button type="button" class="btn btn-primary m-r-5" id="comment-create-btn" style="display: none" onclick="create({{ $post->id }})" >Create</button>
            <button type="button" class="btn btn-primary" id="comment-cancel-btn" style="display: none" onclick="clearInput()" >Cancel</button>
        </div>
    </div>
@endcan
<div id="comments-display">
    @include('comments.show')
</div>
