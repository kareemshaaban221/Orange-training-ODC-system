<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Services\AdminServices;

class AdminController extends Controller
{

    public function __construct()
    {
        AdminServices::cache();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.index')->with('admins', AdminServices::getAll());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admin = AdminServices::getAll($id);
        if($admin)  return view('admin.edit')->with('admin', $admin);
        else        return abort(404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        AdminServices::validator($request, 'update', $id);

        $admin = AdminServices::getAll($id);
        if($admin)  AdminServices::update($admin, $request);
        else        return back()->with('err', 'Admin Is Not Found!');

        return redirect(route('admins.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = AdminServices::getAll($id);
        if($admin) {
            AdminServices::delete($admin);
        } else {
            return back()->with('done', 'Admin Is Not Found!');
        }

        return back()->with('done', 'Admin Has Been Deleted!');
    }
}
