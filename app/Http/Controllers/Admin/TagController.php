<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        return view('admin.tag', [
            'title' => 'Tag (Keyword)',
        ]);
    }

    public function scrap(Request $request)
    {
        $this->validate($request, [
            'url' => 'required'
        ]);

        $ch = curl_init($request->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        $newquery = curl_exec($ch);
        curl_close($ch);

        if (empty($newquery)) return back()->with('urlError', 'URL yang anda inputkan tidak valid');
        if (!preg_match('/(<div\sclass\=\"navbar-sticky\">.*?)<div\sclass\=\"mgb\-16\">/is', $newquery, $cat)) return false;
        if (!preg_match_all('/href\=\"([^"]+)\">([^"]+)<\/a>/', $cat[1], $cat_name)) return false;
        $ret = [];
        foreach ($cat_name[2] as $key => $value)
        {
            Category::create(['name' => trim($value)]);
        }
        return redirect()->route('admin.category')->with(['success' => 'Data Berhasil Disimpan!']);
    }
}
