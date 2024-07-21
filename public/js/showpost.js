// showpost.js
$(document).ready(function() {
    window.showPost = function(postId) {
        $.ajax({
            url: '/posts/' + postId, // Ensure this URL is correct
            type: 'GET',
            success: function(data) {
                if (data && data.post) {
                    // Update the modal with post details
                    $('#post-title').text(data.post.title);
                    $('#post-content').text(data.post.content);
                    $('#post-tags').text(data.post.tags.join(', '));

                    // Show the modal
                    $('#showPostModal').modal('show');
                } else {
                    console.error('Invalid data structure:', data);
                    alert('Error loading post data');
                }
            },
            error: function(xhr) {
                console.error('Error loading post data:', xhr);
                alert('Error loading post data');
            }
        });
    };
});
