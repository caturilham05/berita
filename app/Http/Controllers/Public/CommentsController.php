<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comments;
use App\Models\Contents;

class CommentsController extends Controller
{
    public function index()
    {
        /**/
    }

    public function comment(Request $request, $content_id)
    {
        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required|unique:comments,email',
            'comment' => 'required'
        ], [
            'name.required'    => 'Nama anda tidak boleh kosong',
            'email.required'   => 'Email anda tidak boleh kosong',
            'email.unique'     => 'Email yang anda masukkan sudah terdaftar',
            'comment.required' => 'Komentar tidak boleh kosong',
        ]);

        $insert = [
            'content_id' => $content_id,
            'name'       => $request->name,
            'email'      => $request->email,
            'comment'    => $request->comment,
        ];

        $comments             = new Comments();
        $comments->content_id = $content_id;
        $comments->name       = $request->name;
        $comments->email      = $request->email;
        $comments->comment    = $request->comment;
        $comments->content()->associate($content_id);
        $contents = Contents::find($content_id);
        $contents->comment()->save($comments);

        $title = Contents::select('title')->where('id', $content_id)->value('title');
        return redirect()->route('public.content_detail', ['id' => $content_id, 'title' => $title])->with(['success' => 'Komentar berhasil ditambahkan']);
    }

    public function comment_reply(Request $request, $content_id, $comment_id)
    {
        $this->validate($request, [
            'name'    => 'required',
            'email'   => 'required',
            'comment' => 'required'
        ], [
            'name.required'    => 'Nama anda tidak boleh kosong',
            'email.required'   => 'Email anda tidak boleh kosong',
            'comment.required' => 'Komentar tidak boleh kosong',
        ]);

        $reply                 = new Comments();
        $reply->content_id     = $content_id;
        $reply->sub_comment_id = $comment_id;
        $reply->name           = $request->name;
        $reply->email          = $request->email;
        $reply->comment        = $request->comment;
        $reply->content()->associate($content_id);
        $contents = Contents::find($content_id);
        $contents->comment()->save($reply);
        $title = Contents::select('title')->where('id', $content_id)->value('title');
        return redirect()->route('public.content_detail', ['id' => $content_id, 'title' => $title])->with(['success' => 'Komentar berhasil ditambahkan']);
    }

    public function comment_like(Request $request, $id)
    {
        Comments::where('id', $id)->increment('like');
        return back();
    }

    public function comment_dislike(Request $request, $id)
    {
        Comments::where('id', $id)->increment('dislike');
        return back();
    }
}
