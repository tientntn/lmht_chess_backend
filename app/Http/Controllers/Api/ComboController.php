<?php

namespace App\Http\Controllers\Api;

use App\Models\Combo;
use Illuminate\Http\Request;
use App\Libraries\ImageLib;
use App\Http\Controllers\Controller;
use App\Models\Category;

use Input, Validator;

class ComboController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = [];
        $per_page = Input::has('per_page') ? 0+Input::get('per_page') : 15;
        $search = Input::get('search');
        $searchCategory = Input::get('category_id');
        $heroes = Combo::where(function($query) use($search) {
            if($search) {
                return $query->where('title','like','%'.$search.'%');
            }
        })->where('_id','!=','')->paginate($per_page);
        $data = [];
        foreach ($heroes as $hero) {
            $data[] = $hero->getArrayInfo();
        }
        $res = $heroes->toArray();
        $res['data'] = $data;
        $res['total'] = $heroes->total();
        $res['status'] = 200;
        return response()->json($res);
    }

    public function create() {
        $this->data['category'] = new Category();
//        $pieces = Piece::all();
//        $data = [];
//        foreach ($pieces as $piece) {
//            $data[] = $piece->getArrayInfo();
//        }
//        $this->data['pieces'] = $data;
        return view('categories.form', $this->data);
    }

    public function store () {
        $rule = [
            'title'     => 'required',
        ];
        $validator = Validator::make(Input::all(), $rule);
        if ($validator->fails()){
            return back()->withErrors($validator->messages())->withInput();
        }
        $category = new Category();
        $category->title=Input::get('title');
        // $slug = str_slug(Input::get('title'));
        $slug = Input::get('slug');
        $category->slug = $category->checkSlug($slug);
        $fields = $category->languageFields();

        $category->save();
        $category->cleanCache();
        return redirect('/categories')->withSuccess('Tạo mới thành công');
    }

    public function edit($id) {
        $category = Category::find($id);
        $this->data['category'] = $category;

        if (!$category) {
            return view('errors.404');
        } else {
//            $pieces = Piece::all();
//            $data = [];
//            foreach ($pieces as $piece) {
//                $data[] = $piece->getArrayInfo();
//            }
//            $this->data['pieces'] = $data;
            return view('categories.form', $this->data);
        }
    }

    public function update ($id) {
        $rule = [];
        $validator = Validator::make(Input::all(), $rule);
        if ($validator->fails()){
            return back()->withErrors($validator->messages())->withInput();
        }
        $category = Category::find($id);
        if (!$category) {
            return view('error.404');
        } else {
            $category->title=Input::get('title');
            $slug = Input::has('slug') ? Input::get('slug', $category->title) : str_slug(Input::get('title'));
            $category->slug = $category->checkSlug($slug, $id);

            $category->save();
            $category->cleanCache();
            return redirect('/categories')->withSuccess('Tạo mới thành công');
        }
    }

    public function destroy($id) {
        $category = Category::find($id);
        $category->cleanCache();
        $category->delete();
        return redirect('/categories')->withSuccess('Xóa thành công');
    }

}

