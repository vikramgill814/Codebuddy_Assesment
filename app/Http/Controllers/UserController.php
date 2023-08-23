<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {

        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $data = [];
        if ($request->ajax()) {
            // print_r($request->post());
            $start = (int)$request->get('start');
            $limit = (int)$request->get('length');
            $txt_search = $request->get('search')['value'] ?? '';

           
            $query = DB::table('users as a')
                    ->select('a.*')->where('role_id','!=',1);
            if ($txt_search != '') {
                $query->Where(function ($query) use ($txt_search) {
                    $query->orwhere('a.name', 'like', '%' . $txt_search . '%');
                    $query->orwhere('a.email', 'like', '%' . $txt_search . '%');
                });
            }
            $total = $query->count();
           // DB::enableQueryLog();
            $users = $query->orderBy('a.name', 'ASC')->limit($limit)
                ->offset($start)
                ->get()->toArray();
            //dd(DB::getQueryLog());
            foreach ($users as $user) {
                $row = [];
                $row[] = ++$start;
                $row[] = $user->name;
                $row[] = $user->email;
                $btns = '';
                $btns .= '<a class="btn btn-primary" href="' . route('users.show', $user->id) . '"><i class="c-white-500 fa fa-dashboard" title="User Dashboard"></i></a>';
                
                $row[] = $btns;
                $data[] = $row;
            }

            $output = array(
                "draw"            => $request->get('draw'),
                "recordsTotal"    => $total,
                "recordsFiltered" => $total,
                "data"            => $data,
            );
            echo json_encode($output);
            exit;
        }

        return view('users.list')->with($data);
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
}
