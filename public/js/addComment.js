$(document).ready(function() {
    $('#comment-form').submit(function(e) {
        e.preventDefault(); // منع إرسال النموذج بالطريقة التقليدية

        var postId = $('#post-id').val(); // تأكد من الحصول على الـ ID الصحيح
        var content = $('#comment-content').val();

        $.ajax({
            url: '/posts/' + postId + '/comments', // تأكد من أن هذا هو المسار الصحيح
            type: 'POST',
            data: {
                content: content,
                _token: $('meta[name="csrf-token"]').attr('content') // CSRF token
            },
            success: function(response) {
                if (response.success) {
                    // تحديث واجهة المستخدم لعرض التعليق الجديد
                    $('#comments-container').append(
                        '<div class="comment">' +
                        '<p>' + response.comment.content + '</p>' +
                        '</div>'
                    );
                    $('#comment-content').val(''); // مسح محتوى الحقل بعد الإرسال
                } else {
                    alert('Error adding comment');
                }
            },
            error: function() {
                alert('Error adding comment');
            }
        });
    });
});
