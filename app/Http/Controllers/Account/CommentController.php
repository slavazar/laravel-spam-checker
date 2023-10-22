<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

use App\Http\Requests\StoreUserCommentRequest;
use App\Http\Requests\UpdateUserCommentRequest;
use App\Models\User\Comment as UserComment;
use TeamTNT\TNTSearch\Classifier\TNTClassifier;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $comments = $user->comments()->get();
        
        return Inertia::render('Account/Comment/Index', [
            'user_comments' => $comments,
        ]);
    }
    
    protected function isSpam($text)
    {
        $classifier = new TNTClassifier();
        $classifier->load(storage_path('comments') . DIRECTORY_SEPARATOR . 'comments.cls');

        $guess = $classifier->predict($text);
        
        if ($guess && isset($guess['label'])) {
            if ($guess['label'] == 'spam') {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Account/Comment/Add', [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserCommentRequest $request)
    {
        $data = $request->validated();
        
        $data['user_id'] = Auth::user()->id;
        $data['is_spam'] = 0;
        
        if ($this->isSpam($data['text'])) {
            $data['is_spam'] = 1;
        }
        
        $comment = UserComment::create($data);
        
        if ($comment) {
            return Redirect::route('account.comments.edit', ['id' => $comment->id]);
        }

        return Redirect::route('account.comments.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserComment $userComment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $userComment = UserComment::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();
        
        if (!$userComment) {
            return Redirect::route('account.comments.index')
                ->with('error', 'Invalid comments.');;
        }
        
        return Inertia::render('Account/Comment/Edit', [
            'user_comment' => $userComment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserCommentRequest $request, $id)
    {
        $userComment = UserComment::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();
        
        if (!$userComment) {
            return Redirect::route('account.comments.index')
                ->with('error', 'Invalid comments.');;
        }
        
        $data = $request->validated();
        
        $userComment->text = $data['text'];
        $userComment->is_spam = 0;
        
        if ($this->isSpam($data['text'])) {
            $userComment->is_spam = 1;
        }
        
        $userComment->save();
        
        return Redirect::route('account.comments.edit', ['id' => $userComment->id])
                ->with('status', 'The comments was updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $userComment = UserComment::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->first();
        
        if (!$userComment) {
            return Redirect::route('account.comments.index')
                ->with('error', 'Invalid comments.');;
        }
        
        $userComment->delete();
        
        return Redirect::route('account.comments.index')
            ->with('status', 'The comment was deleted.');;

    }
}
