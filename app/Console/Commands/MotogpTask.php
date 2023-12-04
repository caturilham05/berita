<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use App\Models\Contents;

class MotogpTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:motogp-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CURL data ke detik.com untuk mendapatkan berita motogp terbaru';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            'url'      => 'https://sport.detik.com',
            'category' => 2,
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
        $this->info('Custom task executed successfully!');
    }
}
