<script>
    // Create Comment
    function create(postId){
        var new_comment = $('#comment').val();
        $.ajax({
            type: "post",
            url: "{{ url('/comment') }}",
            data: {comment: new_comment, post_id: postId, "_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('.add-comment').val('');
                console.log(response);
                $('#comments-display').html(response);
                $("#comment-cancel-btn").hide();
                $("#comment-create-btn").hide();
                $("#comment-error-alert").hide();
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
                $("#comment-error-alert").html(error);
                $("#comment-error-alert").css('display', 'block');
                $("#comment-cancel-btn").show();
                $("#comment-create-btn").show();
            }
        });
    }
    function duringInput() {
        $("#comment-cancel-btn").show();
        $("#comment-create-btn").show();
    }

    function clearInput() {
        document.getElementById('comment').value = '';
        $("#comment-cancel-btn").hide();
        $("#comment-create-btn").hide();
    }

    // Delete comment
    function deleteComment(id) {
        swal({
                title: "Are you sure to delete this commemt ?",
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
                        url: '/comment/' + id,
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
                                closeOnConfirm: true,
                            }, function () {
                                $( "#comment-"+id ).remove();
                            });
                        },
                        error: function (response) {
                            swal({
                                title: "Warning!",
                                text: response.responseJSON.message,
                                icon: "warning",
                            });
                        },
                    });
                }
            });

    }

    // Edit comment
    var edit_id;
    function editContent(id, comment) {
        edit_id = id;
        jQuery.noConflict();
        $('#defaultModal').modal('toggle');
        $('#defaultModal #modal-comment').val(comment);
    }

    function editComment() {
        var comment = $('#modal-comment').val();
        var post_id = {{ $post->id }}
        $.ajax({
            type: "patch",
            url: "/comment/"+edit_id,
            data: {comment: comment, post_id: post_id, "_token": "{{ csrf_token() }}"},
            success: function (response) {
                $('#comment-'+edit_id+' .comment-content p').text(comment);
                $('#comment-modal-close').click();
                $("#comment-modal-error-alert").hide();
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

</script>