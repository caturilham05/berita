<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.category', [
            'title' => 'Category',
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
        if (!preg_match_all('/href\=\"([^"]+)/', $cat[1], $cat_code)) return false;
        if (!preg_match_all('/href\=\"([^"]+)\">([^"]+)<\/a>/', $cat[1], $cat_name)) return false;
        $code = ['home'];
        foreach ($cat_code[1] as $key => $value)
        {
            if (preg_match('/sport/is', $value))
            {
                if (preg_match('/^(?:ht|f)tps?:\/\/[^"]+\/([^"]+)/', $value, $match))
                {
                    $code[] = $match[1];
                }
            }
        }

        $ret = [];
        foreach ($cat_name[2] as $key => $value)
        {
            if (isset($code[$key]))
            {
                $pre_insert = ['code' => $code[$key],'name' => trim($value), 'is_active' => 1];
                Category::create($pre_insert);
            }

        }
        return redirect()->route('admin.category')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
