$(document).ready(function() {
    window.openEditModal = function(postId) {
        $.ajax({
            url: '/posts/' + postId + '/edit',
            type: 'GET',
            success: function(data) {
                console.log(data);

                if (data && data.locales && data.post && data.post.translations) {
                    $('#update-post-form').attr('action', '/posts/' + postId);

                    data.locales.forEach(function(locale) {
                        if (data.post.translations[locale]) {
                            $('#title_' + locale).val(data.post.translations[locale].title);
                            $('#content_' + locale).val(data.post.translations[locale].content);
                        } 
                    });

                    $('#thumbnail_display').val(data.post.thumbnail || 'No file chosen');
                    $('#thumbnail_img').attr('src', data.post.thumbnail ? '/storage/' + data.post.thumbnail : 'default-thumbnail.png');

                    var tagIds = data.post.tags.map(tag => tag.id);
                    $('#tags').val(tagIds);

                    $('#updatePostModal').modal('show');
                } else {
                    console.error('Invalid data structure:', data);
                    alert('Error loading post data');
                }
            },
            error: function(xhr) {
                console.error('AJAX request failed:', xhr);
                alert('Error loading post data');
            }
        });
    }

    $('#update-post-form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success) {
                    var postRow = $('#post_id_' + response.post.id);
                    postRow.find('td').eq(0).html('<img src="' + response.post.thumbnail_url + '" alt="Thumbnail" width="50">');
                    postRow.find('td').eq(1).text(response.post.title);
                    postRow.find('td').eq(2).text(response.post.content);
                    postRow.find('td').eq(3).text(response.post.tags.map(tag => tag.title).join(', '));

                    $('#updatePostModal').modal('hide');
                    alert(response.message);
                } else {
                    console.log('Error:', response.message);
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorHtml = '<div class="alert alert-danger"><ul>';
                $.each(errors, function(key, value) {
                    errorHtml += '<li>' + value + '</li>';
                });
                errorHtml += '</ul></div>';
                $('#updatePostModal .alert-danger').html(errorHtml).show();
            }
        });
    });
});
