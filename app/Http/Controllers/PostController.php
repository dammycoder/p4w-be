<?php

namespace App\Http\Controllers;
use \TCG\Voyager\Traits\Translatable;
use Illuminate\Support\Facades\Crypt;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //


    public function store(Request $request)
    {
        $validated = $request->validate([
            'author_id' => 'required|integer',
            'title' => 'required|string|max:255',
            'seo_title' => 'nullable|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'image' => 'nullable|string|max:255',
            'slug' => 'required|string|max:255|unique:your_table_name,slug',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'status' => 'required|in:PUBLISHED,DRAFT,PENDING',
            'featured' => 'required|boolean',
            'tags' => 'nullable|array',
        ]);

        if ($request->has('tags')) {
            $validated['tags'] = json_encode($request->tags);
        }

        $newEntry = Post::create($validated);

        return response()->json([
            'message' => 'Entry successfully created!',
            'data' => $newEntry
        ], 201);
    }


    public function get_all_blogs()
    {
        $base_url = env('APP_URL');

        $posts = Post::select(
            'id',
            'image',
            'tags',
            'title',
            'created_at',
            'updated_at',
            'excerpt',
            'body',
            'slug',
            'status',
            'author_id',
        )
            ->where('status', 'PUBLISHED')
            ->paginate(10);

        $posts->getCollection()->transform(function ($post) use ($base_url){
            $readTime = $this->calculate_read_time($post->body);
            return [
                'id' => Crypt::encrypt($post->id),
                'image' => $base_url . '/' . $post->image, 
                'content' => $post->body,
                'tags' => $post->tags ? json_decode($post->tags) : [],
                'title' => $post->title,
                'datePosted' => $post->created_at->toDateString(),
                'dateUpdated' => $post->updated_at->toDateString(),
                'summary' => $post->excerpt,
                'readTime' => $readTime,
                'slug' => $post->slug,
                'author' => $post->author->first_name . ' ' . $post->author->last_name,
            ];
        });

        return response()->json([
            'message' => 'Request Successful!',
            'blogs' => $posts,
        ], 200);
    }


    public function get_blog_by_id($id)
    {
        try {
            $id = Crypt::decrypt($id);
            $baseURL = env('APP_URL');
            $post = Post::findOrFail($id);
            $tags = explode(',', $post->tags);
            $related_posts = Post::whereIn('tags', $tags)->take(3)->get();
            if ($related_posts->isEmpty()) {
                $related_posts = Post::take(3)->get();
            }
            $related_posts = $related_posts->map(function ($relatedPost) use ($baseURL) {
                return [
                    'id' => Crypt::encrypt($relatedPost->id),
                    'image' => $baseURL . '/' . $relatedPost->image, 
                    'tags' => $relatedPost->tags ? json_decode($relatedPost->tags) : [],
                    'title' => $relatedPost->title,
                    'content' => $relatedPost->body,
                    'datePosted' => $relatedPost->created_at->toDateString(),
                    'dateUpdated' => $relatedPost->updated_at->toDateString(),
                    'summary' => $relatedPost->excerpt,
                    'readTime' => $this->calculate_read_time($relatedPost->body),
                    'slug' => $relatedPost->slug,
                    'author' => $relatedPost->author->first_name . ' ' . $relatedPost->author->last_name,
                ];
            });

            $blogData = [
                'id' => Crypt::encrypt($post->id),
                'image' => $baseURL . '/' . $post->image, 
                'tags' => $post->tags ? json_decode($post->tags) : [],
                'title' => $post->title,
                'content' => $post->body,
                'datePosted' => $post->created_at->toDateString(),
                'dateUpdated' => $post->updated_at->toDateString(),
                'summary' => $post->excerpt,
                'readTime' => $this->calculate_read_time($post->body),
                'slug' => $post->slug,
                'author' => $post->author->first_name . ' ' . $post->author->last_name,
            ];

            return response()->json([
                'message' => 'Request Successful!',
                'blog' => $blogData,
                'related_posts' => $related_posts,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 422);
        }
    }


    private function calculate_read_time($content)
    {
        $bodyText = strip_tags($content);
        $wordCount = str_word_count($bodyText);
        return ceil($wordCount / 200);
    }

}
