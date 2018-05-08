<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*if(!Auth::guard('admin')->check()){
            return redirect('/admin')->with('invalid_credentials',__('messages.adminMessages.invalid'));
        }*/

        if (auth()->guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])){
            return redirect('/admin/dashboard');
        }else{
            dd('your username and password are wrong.');
        }
        
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function dashboard(Request $request)
    {
        if(!Auth::guard('admin')->check())
            return redirect('admin');
        else
            return view('Admin.index');
        
    }

    public function logout(Request $request){
        $this->guard('admin')->logout();
        return redirect('/admin');
    }

    
    protected function guard($guard){
        return Auth::guard($guard);
    }
}
