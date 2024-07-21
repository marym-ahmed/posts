@extends('layouts.app')

@section('content')
    <div class="container">
        <br>
        <div class="row">
            <div class="col-12">
                <form id="search-form">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <input type="text" name="query" id="query" placeholder="Search for posts">
                    <button type="submit">Search</button>
                </form>
                <br>

                <div id="posts-container"></div>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createPostModal">
                    Create Post
                </button>
                <table class="table table-bordered" id="laravel_crud">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <thead>
                        <tr>
                            <th>Thumbnail</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Tags</th>
                            <td colspan="3">Action</td>
                        </tr>
                    </thead>
                    <tbody id="posts-crud">
                        @foreach ($posts as $post)
                            <tr id="post_id_{{ $post->id }}">
                                <td><img src="{{ asset('storage/' . $post->thumbnail) }}" alt="Thumbnail" width="50"></td>
                                <td>{{ $post->title }}</td>
                                <td>{{ $post->content }}</td>
                                <td>{{ $post->tags->pluck('title')->join(', ') }}</td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-post-id="{{ $post->id }}"
                                        data-target="#showPostModal" onclick="showPost({{ $post->id }})">Show Post</button>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#commentModal">Add Comment</button>
                                </td>
                                @if (Auth::check() && Auth::user()->id == $post->user_id)
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-post-id="{{ $post->id }}" data-target="#updatePostModal"
                                            onclick="openEditModal({{ $post->id }})">Edit Post</button>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger" data-id="{{ $post->id }}">Delete</button>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('posts.partials.create_post_modal')
    @include('posts.partials.edit_post_modal')
    @include('posts.partials.show_post_modal')

    <script type="text/javascript" src="{{ asset('js/deletpost.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/createpost.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/updatepost.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/addComment.js') }}"></script>
@endsection
