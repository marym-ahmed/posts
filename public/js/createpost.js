$(document).ready(function() {
    // Define createPost function
    function createPost() {
        $('#create-post-form').submit(function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        // Append new post to the list
                        $('#posts-crud').append(
                            '<tr id="post_id_' + response.post.id + '">' +
                            '<td><img src="' + response.post.thumbnail_url + '" alt="Thumbnail" width="50"></td>' +
                            '<td>' + response.post.title + '</td>' +
                            '<td>' + response.post.content + '</td>' +
                            '<td>' + response.post.tags.map(tag => tag.title).join(', ') + '</td>' +
                            '<td><a href="javascript:void(0)" id="show-post" data-id="' + response.post.id + '" class="btn btn-info show-post">Show</a></td>' +
                            '<td><a href="javascript:void(0)" id="edit-post" data-id="' + response.post.id + '" class="btn btn-info">Edit</a></td>' +
                            '<td><a href="javascript:void(0)" id="delete-post" data-id="' + response.post.id + '" class="btn btn-danger delete-post">Delete</a></td>' +
                            '</tr>'
                        );
                        // Hide the modal
                        $('#createPostModal').modal('hide');
                        // Display success message
                        alert(response.message); // Use a more sophisticated notification system if needed
                    } else {
                        console.log('Error:', response.message);
                    }
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    var errors = xhr.responseJSON.errors;
                    $('.alert-danger').remove();
                    var errorHtml = '<div class="alert alert-danger"><ul>';
                    $.each(errors, function(key, value) {
                        errorHtml += '<li>' + value + '</li>';
                    });
                    errorHtml += '</ul></div>';
                    $('#createPostModal .modal-body').prepend(errorHtml);
                }
            });
        });
    }

    // Call createPost function when the page is ready
    createPost();
});
