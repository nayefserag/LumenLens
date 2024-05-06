<?php
namespace App\Http\Controllers;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;
class CommentController extends Controller
{
    public function store(Request $request, $post_id)
    {
        $comment = Comment::create([
            'content' => $request->input('content'),
            'author' => $request->input('author'),
            'post_id' => $post_id,
        ]);
    
        $found_post = Post::findOrFail($post_id);
        $updatedCommentIds = $found_post->comment_ids ?? [];
        $updatedCommentIds[] = $comment->id;
        $found_post->comment_ids = $updatedCommentIds;
        $found_post->save();
    
        return response()->json($comment, 201);
    }
    
    public function all()
    {
        $comments = Comment::all();
        if ($comments->count() < 1) {
            return response()->json('No comments found!');
        }
        return response()->json($comments);
    }

    public function getAllCommentsForPost($post_id)
{
    $post = Post::findOrFail($post_id);
    $commentIds = $post->comment_ids;

    $comments = Comment::whereIn('id', $commentIds)->get();


    return response()->json(['data' => $comments], 200);
}

    public function index($post_id,$comment_id)
    {
        $comment = Comment::findOrFail($comment_id);
        return response()->json($comment);
    }


    public function destroy($post_id, $comment_id)
    {
        $found_post = Post::findOrFail($post_id);

        $comment = Comment::findOrFail($comment_id);

        $comment->delete();

        $updatedCommentIds = array_diff($found_post->comment_ids ?? [], [$comment_id]);
        $found_post->comment_ids = $updatedCommentIds;
        $found_post->save();

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    public function update(Request $request, $post_id, $comment_id)
    {
        $comment = Comment::find($comment_id);
        // $comment->update($request->all()); -------> i will enable this if i want to update all fields wiche sent in request  but now i only update the content cause it's not make sense to update author
        if ($request->has('content')) {
            $comment->content = $request->input('content');
            $comment->save();
        }
        return response()->json(['message' => 'Comment updated successfully!', 'data' => $comment], 201);
    }
}
