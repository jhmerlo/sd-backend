<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Comment;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentRequest $request)
    {
        $validatedData = $request->validated();
    
        // checks if the class of commentable_type exists and the dates are valid
        if (class_exists($validatedData['commentable_type'])) {
            $commentable = $validatedData['commentable_type']::findOrFail($validatedData['commentable_id']);
                $comment = new Comment;
                $comment->fill($validatedData);

                $comment->user_id = Auth::user()->institutional_id;

                $comment->save();

                return response()->json([
                    'message' => 'Comentário adicionado com sucesso.'
                ], 200);
        }

        return response()->json([
            'message' => "Bad Request."
        ], 400);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $comment = Comment::findOrFail($request->id);

        $comment->delete();

        return response()->json([
            'message' => "Comentário deletado com sucesso!"
        ], 200);
    }
}
