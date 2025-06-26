<?php

namespace App\Http\Controllers\Feed;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\SearchRequest;
use App\Models\Comment as ModelsComment;
use App\Models\Feed;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Notification;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function index()  {
        $feeds=Feed::with('user')->latest()->get();
        return response([
            'feeds' => $feeds
        ], 200);
    }

    public function store(PostRequest $postRequest)
    {
        $postRequest->validated();
        auth()->user()->feeds()->create([
            'content' => $postRequest->content
        ]);
        return response([
            'message' => 'success'
        ], 200);
    }
public function likePost($feed_id) {
    $feed=Feed::whereId($feed_id)->first();
    if(!$feed){
        return response(['message'=> '404 not found'],500);

    }
    $unliked_post=Like::where('user_id',auth()->id())->where('feed_id',$feed_id)->delete();
    if($unliked_post){
        return response([
            'message' => 'unLiked'
        ], 200);
    }
    $liked_post= Like::create([
            'user_id'=>auth()->id(),
            'feed_id'=>$feed_id
        ]);
        $likingUser = auth()->user();     if($liked_post){
        if ($feed->user_id != auth()->id()) { 
            Notification::create([
                'user_id' => $feed->user_id, 
                'type' => 'like',
                'related_id' => $feed_id,
                'username' => $likingUser->username,
            ]);
        }
       return response([
            'message' => 'Liked'
        ], 200); 
    }
   
}
public function getNotifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->latest()->get();
        return response()->json(['notifications' => $notifications], 200);
    }
public function comment(Request $request,$feed_id){
    $request->validate([
        'body'=>'required'
    ]);
    $feed = Feed::whereId($feed_id)->first();
    $comment=Comment::create([
'user_id'=>auth()->id(),
'feed_id'=>$feed_id,
'body'=>$request->body
    ]);
    if ($feed->user_id != auth()->id()) {
        $commentingUser = auth()->user(); 
        Notification::create([
            'user_id' => $feed->user_id, 
            'type' => 'comment',
            'related_id' => $feed_id,
            'username' => $commentingUser->username,
        ]);
    }
    return response([
        'message'=>'success'
    ],201);
}
public function getComments($feed_id){
    $comments=Comment::with('feed')-> with('user')->whereFeedId($feed_id)->latest()->get();
    return response([
        'comments'=>$comments
    ],200);
}
public function search(SearchRequest $request)
{
    $query = $request->query('query');
    $posts = Feed::where('content', 'LIKE', "%{$query}%")
                ->with('user') 
                ->get()
                ->filter()
                ->values();
    return response()->json([
        'message' => 'Search results',
        'data' => $posts
    ], 200);
}
}
