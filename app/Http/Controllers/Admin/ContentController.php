<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contents;
use App\Models\Category;
use App\Models\Tags;
use Illuminate\Support\Facades\DB;

class ContentController extends Controller
{
    public function index()
    {
        // $contents = Contents::select(
        //     'id',
        //     'tag_ids',
        //     'cat_ids',
        //     'title',
        //     'intro',
        //     'image',
        //     'image_thumb',
        //     'content',
        //     'timestamp',
        //     'is_active',
        //     'url',
        //     'created_at',
        //     'updated_at'
        // )->where('is_active', 1)->get()->paginate(10)->toArray();

        $contents = DB::table('contents')->select(
            'id',
            'tag_ids',
            'cat_ids',
            'title',
            'intro',
            'image',
            'image_thumb',
            'content',
            'timestamp',
            'is_active',
            'url',
            'created_at',
            'updated_at'
        )->OrderBy('id', 'DESC')->paginate(30);

        $tags     = Tags::select('id', 'name')->get()->toArray();
        $category = Category::select('id', 'name')->get()->toArray();

        $tags_new     = [];
        $contents_new = [];
        $category_new = [];
        foreach ($tags as $value) $tags_new[$value['id']] = $value['name'];
        foreach ($category as $value) $category_new[$value['id']] = $value['name'];

        return view('admin.dashboard', [
            'title'    => 'Contents',
            'contents' => $contents ?? '',
            'category' => $category_new ?? '',
            'tags'     => $tags ?? '',
        ]);
    }

    public function content_detail(int $id)
    {
        $content = Contents::select(
            'id',
            'tag_ids',
            'cat_ids',
            'title',
            'intro',
            'image',
            'image_thumb',
            'content',
            'timestamp',
            'is_active',
            'url',
            'created_at',
            'updated_at'
        )->where('id', $id)->get()->first();
        $content->content = str_replace('<strong>Baca juga: </strong>', '', $content->content);
        return view('admin.content_detail', [
            'title'   => 'Content Detail',
            'id'      => $id,
            'content' => $content ?? '',
        ]);
    }

    public function content_detail_scrap(Request $request)
    {
        $this->validate($request, [
            'id'  => 'required',
            'url' => 'required',
        ]);

        $ch = curl_init($request->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $newquery = curl_exec($ch);
        curl_close($ch);

        if (preg_match('/<div\sclass\=\"detail__media\smedia\-nav\">(.*)<div\sid\=\"slider\-/is', $newquery, $images_all))
        {
            if (preg_match_all('/https\:\/\/akcdn\.detik\.net\.id\/[^"]+/', $images_all[0], $image_src)) $image_src_get = $image_src[0];
            if (preg_match_all('/alt=\"([^"]+)\"/', $images_all[0], $image_src_text)) $image_src_text_get = $image_src_text[1];
            foreach ($image_src_get as $key => $value)
            {
                $images_all_data[] = [
                    'images' => $value,
                    'text'   => $image_src_text_get[$key]
                ];
            }
            $image_content          = $image_src[0][0];
            $images_all_data_encode = json_encode($images_all_data);
            $text_all               = $image_src_text[1][0];
        }
        else
        {
            if (empty($newquery)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');
            if (!preg_match('/src=\"(https\:\/\/akcdn\.detik\.net\.id\/[^"]+)\"\salt\=\"([^"]+)\"/', $newquery, $images)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');
            if (!preg_match('/itp_bodycontent\">(.*)S:skyscraper/is', $newquery, $content)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');
            if (!preg_match_all('/<strong>(.*?)<\/strong>/', $content[1], $strong)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');
            if (!preg_match_all('/<p>(.*?)<\/p>/', $content[1], $p)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');

            $text_strong   = $strong[0][0];
            $a             = implode(' ', $p[0]);
            $text_all      = $text_strong.$a;
            $image_content = $images[1];
        }

        $content = Contents::find($request->id);
        if (empty($content)) return back()->with('urlError', 'Konten tidak ditemukan');
        $content->image     = $image_content ?? NULL;
        $content->images    = $images_all_data_encode ?? NULL;
        $content->content   = $text_all ?? NULL;
        $content->is_active = 1;
        $content->update();
        return back()->with('success','Berhasil scrapping content detail');
    }

    public function content_edit($id)
    {
        $content          = Contents::findOrFail($id);
        $content->content = str_replace('<strong>Baca juga: </strong>', '', $content->content);
        $is_active        = ['Inactive', 'Active'];
        return view('admin.content_edit', [
            'title'         => 'Content Edit',
            'content'       => $content ?? '',
            'set_is_active' => $is_active
        ]);
    }

    public function content_edit_proccess(Request $request, $id)
    {
        $this->validate($request, [
            'is_active' => 'required|in:0,1'
        ]);

        $content = Contents::findOrFail($id);
        $content->update(['is_active' => $request->is_active]);
        return redirect()->route('admin.dashboard')->with(['success' => 'Data Berhasil diupdate']);
    }
}
