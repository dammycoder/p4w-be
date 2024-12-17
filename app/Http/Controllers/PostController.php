<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Crypt;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    //

    public function get_all_blogs()
    {
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
            'author_id'
        )
            ->where('status', 'PUBLISHED')
            ->paginate(10);
    
        $posts->getCollection()->transform(function ($post) {
            $readTime = $this->calculateReadTime($post->body);
            return [
                'id' => Crypt::encryptString($post->id),
                'image' => $post->image,
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


    public function get_blogs_by_id($id){
        $id = Crypt::decrypt($id);
        $post = Post::findOrFail($id);
        $related_posts = Post::whereIn('tags', $post->tags)->get()->take(3);
        return response()->json([
            'message' => 'Request Successful!',
            'posts' =>$post,
            'related_posts' =>$related_posts 
        ], 200);
    }
    


    private function calculateReadTime($content)
{
    $bodyText = strip_tags($content);  
    $wordCount = str_word_count($bodyText);
    return ceil($wordCount / 200);  
}

}
