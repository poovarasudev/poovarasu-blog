<!-- For Comment Modal Edit -->
<div class="modal fade" id="defaultModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Edit your Comment</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="comment-modal-error-alert" style="display: none"></div>
                <div class="form-group">
                    <textarea type="text" rows="5" cols="100" maxlength="100" name="title" id="modal-comment"
                              required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="comment-modal-close" data-dismiss="modal">
                        Close
                    </button>
                    <button class="btn btn-primary" onclick="editComment()">Update Comment
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>