<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contents;
use App\Models\Category;
use App\Models\Tags;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    public function index()
    {
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
        )->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->get()->toArray();
        if (empty($contents)) abort(404);

        $data = [
            'new'           => $contents[0],
            'new_feeds'     => array_slice($contents, 1, 2, true),
            'scroll_x'      => array_slice($contents, 30, 10, true),
            'recomendation' => array_slice($contents, 15, 8, true),
            // 'all'           => $contents
        ];

        return view('public.home', [
            'title'    => 'Home',
            'contents' => $data ?? '',
            'category' => $category_new ?? '',
            'tags'     => $tags ?? '',
        ]);
    }

    public function show_all(Request $request)
    {
        if (isset($request->ondate))
        {
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
            )->where('is_active', 1)->whereDate('ondate', '=', $request->ondate)->OrderBy('timestamp', 'DESC')->paginate(10);
        }
        else
        {
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
            )->where('is_active', 1)->OrderBy('timestamp', 'DESC')->paginate(10);
        }

        return view('public.show_all', [
            'title'    => 'Semua Konten Berita',
            'contents' => $contents ?? '',
            'ondate'   => $request->ondate
        ]);
    }

    // public function show_all_search_date(Request $request){
    //     $this->validate($request, [
    //         'timestamp' => 'required',
    //     ]);

    //     dd($request->timestamp);
    // }

    public function content_detail(Request $request)
    {
        if (empty($request->id) || empty($request->title)) abort(404);

        $content = Contents::select(
            'id',
            'tag_ids',
            'cat_ids',
            'title',
            'intro',
            'image',
            'image_thumb',
            'images',
            'content',
            'timestamp',
            'is_active',
            'url',
            'created_at',
            'updated_at'
        )->where('id', $request->id)->where('is_active', 1)->get()->first();
        if (empty($content)) abort(404);
        $content->content = preg_replace('/<a\s+href="https:\/\/www.detik.com[^"]*">([^<]*)<\/a>/', '$1', $content->content);

        if (!empty($content->images))
        {
            $content->images  = json_decode($content->images, true);
            $content->images  = array_slice($content->images, 0, 3);
        }

        $contents = DB::table('contents')->select('title')->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->skip(20)->take(7)->get()->toArray();

        $contents_new = [];
        if (!empty($contents))
        {
            $no = 0;
            foreach ($contents as $key => $value)
            {
                $no++;
                $contents_new[$no] = $value;
            }
        }

        return view('public.content_detail', [
            'title'   => 'Konten Detail',
            'populer' => 'Berita Terpopuler',
            'content' => $content ?? '',
            'all'     => $contents_new ?? ''
        ]);
    }

    public function search(Request $request)
    {
        $this->validate($request, ['keyword' => 'required'], ['keyword.required' => 'pencarian berita tidak boleh kosong']);

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
        )->where('title', 'like', "%".addslashes($request->keyword)."%")->where('is_active', 1)->OrderBy('timestamp', 'DESC')->paginate(10);
        return view('public.search', [
            'title'          => 'Hasil Pencarian',
            'keyword'        => $request->keyword,
            'contents'       => !empty($contents->total()) ? $contents : null,
            'contents_total' => $contents->total()
        ]);
    }
    /*football*/
    public function football()
    {
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
        )->where('cat_ids', 6)->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->get()->toArray();
        if (empty($contents)) abort(404);

        $category     = Category::select('id', 'name')->get()->toArray();
        $category_new = [];
        foreach ($category as $value) $category_new[$value['id']] = $value['name'];

        foreach ($contents as $key => $value)
        {
            $value->cat_name = $category_new[$value->cat_ids];
            $contents[$key]    = $value;
        }

        $data = [
            'new'           => $contents[0],
            'new_feeds'     => array_slice($contents, 1, 2, true),
            'scroll_x'      => array_slice($contents, -10, 10, true),
            'recomendation' => array_slice($contents, -25, 8, true),
            'cat_id'        => $contents[0]->cat_ids,
        ];

        return view('public.football.football', [
            'title'    => 'Football',
            'contents' => $data
        ]);        
    }

    public function football_show_all(Request $request)
    {
        if (isset($request->ondate))
        {
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
            )->where('cat_ids', 6)->where('is_active', 1)->whereDate('ondate', '=', $request->ondate)->OrderBy('timestamp', 'DESC')->paginate(10);
        }
        else
        {
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
            )->where('cat_ids', 6)->where('is_active', 1)->OrderBy('timestamp', 'DESC')->paginate(10);
        }
        return view('public.show_all', [
            'title'    => 'Semua Konten Sepak Bola',
            'contents' => $contents ?? '',
        ]);
    }
    /*football*/

    /*motogp*/
    public function motogp()
    {
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
        )->where('cat_ids', 2)->where('is_active',1)->where('content', '<>', '')->OrderBy('timestamp', 'DESC')->get()->toArray();
        if (empty($contents)) abort(404);

        $category     = Category::select('id', 'name')->get()->toArray();
        $category_new = [];
        foreach ($category as $value) $category_new[$value['id']] = $value['name'];

        foreach ($contents as $key => $value)
        {
            $value->cat_name = $category_new[$value->cat_ids];
            $contents[$key]    = $value;
        }

        $data = [
            'new'           => $contents[0],
            'new_feeds'     => array_slice($contents, 1, 2, true),
            'scroll_x'      => array_slice($contents, -10, 10, true),
            'recomendation' => array_slice($contents, -25, 8, true),
            'cat_id'        => $contents[0]->cat_ids,
        ];

        return view('public.motogp.motogp', [
            'title'    => 'Moto-GP',
            'contents' => $data
        ]);        
    }
    public function motogp_show_all(Request $request)
    {
        if (isset($request->ondate))
        {
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
            )->where('cat_ids', 2)->where('is_active', 1)->whereDate('ondate', '=', $request->ondate)->OrderBy('timestamp', 'DESC')->paginate(10);
        }
        else
        {
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
            )->where('cat_ids', 2)->where('is_active', 1)->OrderBy('timestamp', 'DESC')->paginate(10);
        }

        return view('public.show_all', [
            'title'    => 'Semua Konten Moto-GP',
            'contents' => $contents ?? '',
        ]);
    }
    /*motogp*/
}