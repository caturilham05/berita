<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Contents;
use Illuminate\Support\Facades\DB;

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

    public function scrap_cron_football()
    {
        $data = [
            'url'      => 'https://sport.detik.com',
            'category' => 6,
        ];
        $category_name = Category::findOrFail($data['category'])->code;
        if (empty($category_name))
        {
            echo 'nama kategori tidak ditemukan';
            return;
        }

        $url_full = $data['url'].'/'.strtolower($category_name).'/indeks';
        $ch       = curl_init($url_full);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $newquery = curl_exec($ch);
        curl_close($ch);

        if (empty($newquery))
        {
            echo 'curl data url gagal';
            return;
        }

        if (!preg_match_all('/src=\"(https\:\/\/akcdn\.detik\.net\.id\/[^"]+)\"\salt\=\"([^"]+)\"/', $newquery, $images))
        {
            echo 'gambar tidak ditemukan';
            return;
        }

        if (!preg_match_all('/media__title\">[^"]+\"([^"]+)\"/', $newquery, $href))
        {
            echo 'href tidak ditemukan';
            return;
        }

        if (!preg_match_all('/d\-time.*?=\"([0-9]+)\"\stitle\=\"([^"]+)/is', $newquery, $time))
        {
            echo 'timestamp tidak ditemukan';
            return;
        }

        $datas          = [];
        $images_thumb   = $images[1];
        $title          = $images[2];
        $url            = $href[1];
        $timestamp      = $time[1];
        $content_exists = [];
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
                    'cat_ids'     => $data['category'],
                    'url'         => $url[$key] ?? '',
                    'is_active'   => 0,
                ];
            }
        }
        Contents::insert($datas);
        echo 'sukses curl data sepakbola';
    }
}
