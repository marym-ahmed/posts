<?php

namespace App\Http\Controllers;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostsController extends Controller
{

    protected $postRepository;

    public function __construct(PostRepositoryInterface $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function index()
    {

        $posts = $this->postRepository->all();
        $tags = Tag::all();
        return view('posts.index', compact('posts', 'tags'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('posts.create', compact('tags'));
    }

    public function edit($id)
    {
        try {
            $post = Post::with(['translations', 'tags'])->findOrFail($id);
            $locales = config('translatable.locales');
            $tags = Tag::all();

            $translations = [];
            foreach ($locales as $locale) {
                $translations[$locale] = [
                    'title' => $post->getTranslation('title', $locale),
                    'content' => $post->getTranslation('content', $locale),
                ];
            }

            return response()->json([
                'post' => $post,
                'locales' => $locales,
                'tags' => $tags,
                'translations' => $translations,
            ]);
        } catch (\Exception $e) {
            // تسجيل الخطأ
            Log::error($e->getMessage());

            // إرجاع رسالة خطأ
            return response()->json(['error' => 'Something went wrong.'], 500);
        }
    }

    public function show($id)
    {
        $post = Post::with(['translations', 'tags'])->findOrFail($id);
        $locales = config('translatable.locales');

        $translations = [];
        foreach ($locales as $locale) {
            $translations[$locale] = [
                'title' => $post->getTranslation('title', $locale),
                'content' => $post->getTranslation('content', $locale),
            ];
        }

        return response()->json([
            'post' => $post,
            'locales' => $locales,
            'translations' => $translations,
        ]);
    }

    public function destroy($id)
    {
        $post = $this->postRepository->delete($id);


        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully',
        ]);
    }

    public function search(Request $request)
    {
        try {
            $query = $request->input('query');

            $posts = Post::whereTranslationLike('title', "%{$query}%")
                ->orWhereTranslationLike('content', "%{$query}%")
                ->get();

            return response()->json($posts);
        } catch (\Exception $e) {
            // تسجيل الخطأ وإعادة استجابة خطأ
            Log::error('Error in search function: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile() . ' Line: ' . $e->getLine());
            Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'An error occurred while searching.'], 500);
        }
    }

    public function store(Request $request)
    {

        $newTags = [];
        foreach ($request->new_tags as $locale => $tags) {
            if (is_array($tags)) {

                $tags = implode(',', $tags);
            }
            if (is_string($tags)) {
                $tagsArray = array_map('trim', explode(',', $tags));
                $newTags[$locale] = array_filter($tagsArray);
            } else {

                $newTags[$locale] = [];
            }
        }

        $request->merge(['new_tags' => $newTags]);

        // التحقق من صحة البيانات
        $validatedData = $request->validate([
            'title.*' => 'required',
            'content.*' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'array',
            'new_tags.*' => 'nullable|array',
        ]);

        $posts = $this->postRepository->create($validatedData);
        return response()->json([
            'post' => $post,
            'success' => true,
            'message' => 'Post created successfully!',
        ]);


    }

    public function update(Request $request, Post $post)
    {
        $newTags = [];
        foreach ($request->new_tags as $locale => $tags) {
            if (is_array($tags)) {
                $tags = implode(',', $tags);
            }
            if (is_string($tags)) {
                $tagsArray = array_map('trim', explode(',', $tags));
                $newTags[$locale] = array_filter($tagsArray);
            } else {
                $newTags[$locale] = [];
            }
        }

        $request->merge(['new_tags' => $newTags]);

        $validatedData = $request->validate([
            'title.*' => 'required',
            'content.*' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'tags' => 'array',
            'new_tags.*' => 'nullable|array',
        ]);

        $posts = $this->postRepository->update($validatedData);

        return response()->json([
            'post' => $post,
            'success' => true,
            'message' => 'Post updated successfully!',
        ]);
    }


}
