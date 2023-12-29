<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Contents;
use Illuminate\Support\Facades\DB;
use Mavinoo\Batch\BatchFacade as Batch;

class ScrapController extends Controller
{
    public function scrap(Request $request)
    {
        $this->validate($request, [
            'url'      => 'required',
            'category' => 'required|not_in:0'
        ]);

        $category_name = Category::findOrFail($request->category)->code;
        if (empty($category_name)) return back()->with('ErrorCat', 'Kategori masih kosong, silahkan inputkan kategori terlebih dahulu dimenu Kategori Scrap');
        $url_full = empty($request->page) ? $request->url.'/'.strtolower($category_name).'/indeks' : $request->url.'/'.strtolower($category_name).'/indeks/'.$request->page;
        $ch = curl_init($url_full);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $newquery = curl_exec($ch);
        curl_close($ch);

        if (empty($newquery)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');
        if (!preg_match_all('/src=\"(https\:\/\/akcdn\.detik\.net\.id\/[^"]+)\"\salt\=\"([^"]+)\"/', $newquery, $images)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');
        if (!preg_match_all('/media__title\">[^"]+\"([^"]+)\"/', $newquery, $href)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');
        if (!preg_match_all('/d\-time.*?=\"([0-9]+)\"\stitle\=\"([^"]+)/is', $newquery, $time)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');

        $datas          = [];
        $images_thumb   = $images[1];
        $title          = $images[2];
        $url            = $href[1];
        $timestamp      = $time[1];

        foreach ($images_thumb as $key => $value)
        {
            $content_exists = Contents::select('id')->where('url', @$url[$key])->get()->toArray();
            if (empty($content_exists))
            {
                $datas[] = [
                    'image_thumb' => $value,
                    'title'       => $title[$key] ?? '',
                    'timestamp'   => $timestamp[$key] ?? '',
                    'ondate'      => !empty($timestamp[$key]) ? date('Y-m-d H:i:s', $timestamp[$key]) : '0000-00-00 00:00:00',
                    'cat_ids'     => $request->category,
                    'url'         => $url[$key] ?? '',
                    'is_active'   => 0,
                ];
            }
        }
        Contents::insert($datas);
        return redirect()->route('admin.dashboard')->with(['success' => 'Data Berhasil diproses']);
    }

    public function scrap_sync(Request $request)
    {
        $limit = 10;
        $date  = $request->date;
        $page  = $request->page;
        $start = intval($page * $limit);
        if (empty($date)) return 'tanggal tidak boleh kosong';

        $contents = Contents::select('id','url')->where('ondate', date($date))->whereNull('content')->skip($start)->take($limit)->get()->toArray();
        if(empty($contents)) abort(404);
        foreach ($contents as $value)
        {
            $ch = curl_init($value['url']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            $newquery = curl_exec($ch);
            curl_close($ch);

            if (preg_match('/<div\sclass\=\"detail__media\smedia\-nav\">(.*)<div\sid\=\"slider\-/is', $newquery, $images_all))
            {
                if (preg_match_all('/https\:\/\/akcdn\.detik\.net\.id\/[^"]+/', $images_all[0], $image_src)) $image_src_get = $image_src[0];
                if (preg_match_all('/alt=\"([^"]+)\"/', $images_all[0], $image_src_text)) $image_src_text_get = $image_src_text[1];
                foreach ($image_src_get as $key => $image)
                {
                    $images_all_data[] = [
                        'images' => $image,
                        'text'   => $image_src_text_get[$key]
                    ];
                }
                $image_content          = $image_src[0][0];
                $images_all_data_encode = json_encode($images_all_data);
                $text_all               = $image_src_text[1][0];
            }
            else
            {
                if (!empty($newquery))
                {
                    if (preg_match('/src=\"(https\:\/\/akcdn\.detik\.net\.id\/[^"]+)\"\salt\=\"([^"]+)\"/', $newquery, $images)) $image_content = $images[1];
                    if (preg_match('/itp_bodycontent\">(.*)S:skyscraper/is', $newquery, $content))
                    {
                        if (preg_match_all('/<strong>(.*?)<\/strong>/', $content[1], $strong)) $text_strong = $strong[0][0];
                        if (preg_match_all('/<p>(.*?)<\/p>/', $content[1], $p)) $a = implode(' ', $p[0]);
                        $text_all = $text_strong.$a;
                    }
                }

            }
            $datas[] = [
                'id'        => $value['id'] ?? NULL,
                'image'     => $image_content ?? NULL,
                'images'    => $images_all_data_encode ?? NULL,
                'content'   => $text_all ?? NULL,
                'is_active' => 1
            ];
        }

        $index           = 'id';
        $contentInstance = new Contents;
        Batch::update($contentInstance, $datas, $index);
        return view('public.scrap_sync', [
            'title' => '',
            'url'  => url('/'),
            'date' => $date,
            'page' => $page
        ]);
    }
}
