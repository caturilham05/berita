<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Contents;
use Mavinoo\Batch\BatchFacade as Batch;

class ContentDetailTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:content-detail-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CURL data ke detik.com untuk mendapatkan detail konten';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $contents = Contents::select('id','url')->where('ondate', date('Y-m-d'))->whereNull('content')->get()->toArray();
        if(empty($contents))
        {
            $this->info('content tidak ada yang disinkronkan');
            return;
        }

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
        $this->info(json_encode('sucess'));
    }
}