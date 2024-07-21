<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Post Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{ asset('js/addComment.js') }}"></script> <!-- تأكد من تحميل هذا الملف -->
</head>
<body>
    @isset($post)
        <!-- نموذج إضافة تعليق -->
        <form id="comment-form">
            <input type="hidden" id="post-id" name="post_id" value="{{ $post->id }}">
            <textarea id="comment-content" name="content" rows="4" placeholder="Add a comment" required></textarea>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- حاوية لعرض التعليقات -->
        <div id="comments-container">
            @foreach ($post->comments as $comment)
                <div class="comment">
                    <p>{{ $comment->content }}</p>
                </div>
            @endforeach
        </div>
    @else
        <p>No post found.</p>
    @endisset
</body>
</html>
