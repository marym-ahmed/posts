<?php
namespace App\Repositories;

use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use App\Models\Tag;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class PostRepository implements PostRepositoryInterface
{
    public function all()
    {
        return Post::with(['user', 'tags'])->get();
    }
    public function create(array $data)
    {

        $post = new Post();
        $post->user_id = auth()->id();
        foreach ($request->title as $locale => $title) {
            $post->translateOrNew($locale)->title = $title;
            $post->translateOrNew($locale)->content = $request->content[$locale];
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $post->thumbnail = $thumbnailPath;
        }

        $post->save();

        $tagIds = $request->tags ?? [];
        foreach ($request->new_tags as $locale => $tagsArray) {
            foreach ($tagsArray as $newTag) {
                if (!empty($newTag)) {
                    $tag = Tag::firstOrCreateTag([
                        'title' => array_merge($request->new_tags, [$locale => $newTag]),
                    ]);

                    if (!in_array($tag->id, $tagIds)) {
                        $tagIds[] = $tag->id;
                    }
                }
            }
        }

        if ($tagIds) {
            $post->tags()->sync($tagIds);
        }
        $post->load('tags');
        $post->thumbnail_url = $post->thumbnail ? asset('storage/' . $post->thumbnail) : null;
        return $post;
    }


    public function update(array $data, $id)
    {

        foreach ($request->title as $locale => $title) {
            $post->translateOrNew($locale)->title = $title;
            $post->translateOrNew($locale)->content = $request->content[$locale];
        }

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $post->thumbnail = $thumbnailPath;
        }

        $post->update();

        $tagIds = $request->tags ?? [];
        foreach ($request->new_tags as $locale => $tagsArray) {
            foreach ($tagsArray as $newTag) {
                if (!empty($newTag)) {
                    $tag = Tag::firstOrCreateTag([
                        'title' => array_merge($request->new_tags, [$locale => $newTag]),
                    ]);

                    if (!in_array($tag->id, $tagIds)) {
                        $tagIds[] = $tag->id;
                    }
                }
            }
        }

        if ($tagIds) {
            $post->tags()->sync($tagIds);
        }
        return $post;
    }

    public function delete($id)
    {
        $post = Post::findOrFail($id);
        return $post->delete();
    }

    public function find($id)
    {
        return Post::with(['user', 'tags'])->findOrFail($id);
    }
}
