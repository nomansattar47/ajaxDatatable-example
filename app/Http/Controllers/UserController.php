<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function ajax_get_user_records(Request $request){
        $data = User::orderBy('updated_at', 'desc')->get();
        // $view = view('admins.users.table_listing', ['posts' => $data]);

        return response()->json([
            'status' => 'ok',
            'total_records' => count($data),
            'listing' => $view->render()
        ], 200);
    }

    // Fetch records
    public function getUsers(Request $request)
    {
        ## Read value
        
        // dd($request->get('search'),$request);

        $draw = $request->get('draw');
        $start = $request->get('start');
        $rowperpage = $request->get('length'); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');
        // echo 'hi';
        // echo 'hi: '.$request->get('order');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        // Total records
        $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')
                                ->where('name', 'like', '%' .$searchValue . '%')
                                ->orWhere('email', 'like', '%' .$searchValue . '%')
                                ->count();

        // Fetch records
        $records = User::orderBy($columnName,$columnSortOrder)
               ->where('users.name', 'like', '%' .$searchValue . '%')
               ->orWhere('users.email', 'like', '%' .$searchValue . '%')
              ->select('users.*')
              ->skip($start)
              ->take($rowperpage)
              ->get();
        // dd($records);
        $data_arr = array();
        // $route_path = "{{ route('users.edit',$id) }}";
        foreach($records as $record){
           $id = $record->id;
           $name = $record->name;
           $email = $record->email;
           $created_at = $record->created_at;
           
           $data_arr[] = array(
               "id" => $id,
               "name" => $name,
               "email" => $email,
               "created_at" => $created_at,
               "actions" => '<a href="users/'. $id .'/edit">
                                <i class="la la-edit"></i> Edit</a>
                            <a href="javascript:void(0);" data-user_id="{{$id}}" class="delete_record">
                                <i class="la la-trash"></i> Delete</a>'

           );
        }

        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordswithFilter,
           "aaData" => $data_arr
        );

        return response()->json($response); 
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
        $user = User::find($id);
        unset($user->password);
        unset($user->remember_token);
        dd($user);
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
}
