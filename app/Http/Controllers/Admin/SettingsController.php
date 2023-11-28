<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Navbar;

class SettingsController extends Controller
{
    public function index()
    {
        $navbar_get = Navbar::select('id', 'name', 'route', 'ordering', 'uri')->get();
        return view('admin.navbar_settings', [
            'title'        => 'Navbar Settings',
            'navbar_datas' => $navbar_get ?? ''
        ]);
    }

    public function navbar_add()
    {
        return view('admin.navbar_add', [
            'title' => 'Add Item Navbar',
        ]);
    }

    public function navbar_edit(int $id)
    {
        $navbar   = Navbar::findOrFail($id);
        $is_active = ['Inactive', 'Active'];

        return view('admin.navbar_edit', [
            'title'         => 'Edit Item Navbar',
            'navbar_item'   => $navbar,
            'set_is_active' => $is_active
        ]);
    }

    public function navbar_process(Request $request, $id)
    {

        if (empty($id))
        {
            $this->validate($request, [
                'name'  => 'required',
                'route' => 'required',
                'uri'   => 'required',
            ]);

            $ordering = Navbar::select('ordering')->OrderBy('ordering', 'DESC')->first();
            if (empty($ordering)) $ordering = ['ordering' => 0];

            $data[] = [
                'name'      => $request->name,
                'route'     => $request->route,
                'uri'       => $request->uri,
                'ordering'  => $ordering['ordering'] + 1,
                'is_active' => 1
            ];
            Navbar::insert($data);
            return redirect()->route('admin.settings')->with(['success' => 'Data Berhasil diproses']);
        }
        else
        {
            $this->validate($request, [
                'name'      => 'required',
                'route'     => 'required',
                'uri'       => 'required',
                'is_active' => 'required|in:0,1'
            ]);

            $navbar = Navbar::findOrFail($id);
            $navbar->update([
                'name'      => $request->name,
                'route'     => $request->route,
                'uri'       => $request->uri,
                'is_active' => $request->is_active
            ]);
            return redirect()->route('admin.settings')->with(['success' => 'Data Berhasil diedit']);
        }
    }

    public function navbar_delete(int $id)
    {
        $navbar = Navbar::findOrFail($id);
        $navbar->delete();
        return redirect()->route('admin.settings')->with(['success' => sprintf('%s Berhasil Dihapus.', $navbar->name)]);
    }
}
