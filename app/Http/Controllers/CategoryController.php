<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {

        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = [];
        if ($request->ajax()) {
            // print_r($request->post());
            $start = (int)$request->get('start');
            $limit = (int)$request->get('length');
            $txt_search = $request->get('search')['value'] ?? '';

            $parents=DB::table('category as p')->where('p.parent_category',0)->where('p.is_deleted',0);

            $query = DB::table('category as a')
            ->leftjoinSub($parents, 'b', function ($join) {
                $join->on('b.id', '=', 'a.parent_category');
            })
                ->select('a.*',DB::raw('IFNULL(b.category_name,"") as parent_category_name'));
            if ($txt_search != '') {
                $query->Where(function ($query) use ($txt_search) {
                    $query->orwhere('a.category_name', 'like', '%' . $txt_search . '%');
                    $query->orwhere('b.category_name', 'like', '%' . $txt_search . '%');
                });
            }
            $total = $query->where('a.is_deleted',0)->count();
           // DB::enableQueryLog();
            $categories = $query->where('a.is_deleted',0)->orderBy('b.category_name', 'ASC')->orderBy('a.category_name', 'ASC')->limit($limit)
                ->offset($start)
                ->get()->toArray();
            //dd(DB::getQueryLog());
            foreach ($categories as $category) {
                $row = [];
                $row[] = ++$start;
                $row[] = $category->parent_category_name;
                $row[] = $category->category_name;
                $btns = '';
                $btns .= '<a class="btn btn-primary" href="' . route('categories.edit', $category->id) . '"><i class="c-white-500 fa fa-edit"></i></a>';
                $btns .= ' <a href="javascript:;" class="btn btn-sm cur-p btn-danger delete_btn noloader" onclick="fn_delete(\'' . $category->id . '\')" title="Delete User"><i class="c-white-500 fa fa-trash"></i></a>';



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

        return view('categories.list')->with($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $parentCategories = Category::where('parent_category',0)->get();
        return view('categories.add',compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [];
        $details = '';
        $view = 'categories.add';
        $user = Auth::user();
        /* Check post either add or update */
        if ($request->isMethod('post')) {
            try {
                 //prd($request->all());
                $validation_arr = [
                    'category_name' => ['required', 'string','min:1','max:50', 'unique:category,category_name'],
                ];

                $validator = Validator::make($request->all(), $validation_arr);
                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }
                $result = DB::transaction(function () use ($request) {

                  
                            $categories_details = new Category();
                            $categories_details->category_name=$request->category_name;
                            $categories_details->parent_category=$request->parent_category ?? 0;
                            $categories_details->created_at = date('Y-m-d H:i:s');
                            $categories_details->is_deleted = 0;

                      
                        $result = $categories_details->save();
                        return true;
            
                });
                if ($result) {
                    return redirect('categories')->with(['message' => 'Category saved successfully.']);
                }
            } catch (\Exception $e) {
               dd($e->getMessage());
                return redirect("categories/create")->withErrors(['error' => "Something Went Wrong...!"]);
            }
        }

        $data['details'] = $details;
        return view($view)->with($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $categories
     * @return \Illuminate\Http\Response
     */
    public function show(Category $categories)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $categories
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
       
        //prd($data);
        $parentCategories = Category::where('parent_category',0)->get();
        return view('categories.edit', compact('category','parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $categories
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        // echo "<pre>";
        //print_r($categories->id); die();
        $data = [];
        $details = '';
        $view = 'categories.edit';
        $user = Auth::user();

        if ($request->isMethod('PATCH')) {

            try {
                $validation_arr = [
                    'category_name' => ['required', 'string','min:1', 'max:50'],
                ];

                $validator = Validator::make($request->all(), $validation_arr);
                if ($validator->fails()) {
                    return redirect()->back()->with('details', json_decode(json_encode($request->post())))->withErrors($validator);
                }
                //die('asd');
                $result = DB::transaction(function () use ($request, $category) {
                    //dd($request->hasFile('image'));
                    $user = Auth::User();
                   

                                $categories_details = Category::find($category->id);
                                $categories_details->updated_at = date('Y-m-d H:i:s');
                                $categories_details->category_name=$request->category_name;
                                 $categories_details->parent_category=$request->parent_category ?? 0;
                                $categories_details->is_deleted=0;
                                $result = $categories_details->save();
                                if ($result) {
                                    return true;
                                }
                      
                });
                if ($result) {
                    return redirect('categories')->with(['message' => 'Category Updated successfully.']);
                }
            } catch (\Exception $e) {
                // DB::rollback();
                return redirect("categories")->withErrors(['error' => "Something Went Wrong...!"]);
            }
        }

        $data['details'] = $details;
        return view($view)->with($data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $categories
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        try {
           // dd($category->id);
            if ($category->id != '') {
                $categories_details = Category::find($category->id);
                $categories_details->is_deleted = 1;
                $categories_details->updated_at = date('Y-m-d H:i:s');
                $result=$categories_details->save();
                DB::commit();
                $data['message'] = 'Category Deleted Successfully';
                echo json_encode($data);
            } else {
                $data['message'] = 'Invalid Request';
                echo json_encode($data);
            }
        } catch (\Exception $e) {
           //dd($e->getMessage());
            echo json_encode(['message' => "Something Went Wrong...!"]);
        }
    }
}
