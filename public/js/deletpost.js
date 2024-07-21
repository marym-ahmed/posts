$(document).ready(function() {
    // عندما يتم النقر على زر الحذف
    $('.btn-danger').on('click', function(e) {
        e.preventDefault();

        // الحصول على ID المشاركة
        var postId = $(this).data('id');
        var url = '/posts/' + postId;

        if (confirm('Are you sure you want to delete this post?')) {
            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content') // إضافة رمز CSRF
                },
                success: function(response) {
                    if (response.success) {
                        // إزالة الصف من الجدول
                        $('#post_id_' + postId).remove();
                        alert(response.message);
                    } else {
                        alert('Error deleting post');
                    }
                },
                error: function(xhr) {
                    alert('Error deleting post');
                }
            });
        }
    });
});
