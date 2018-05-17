<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         return view('user.index');
    }

    public function tableGet(){
        $userList  = User::select('*');
        return DataTables::of($userList)
                ->make(true);
    }

    public function delete(Request $request){
        $id        = $request->get('id');
        $model     = User::find($id);
        $re        = $model->delete();
        if($re){
            $returnData = ['errCode'=>200,'errMsg'=>'删除成功'];
        }else{
            $returnData = ['errCode'=>301,'errMsg'=>'数据库操作失败'];
        }
        return response()->json($returnData);     
    }

}
