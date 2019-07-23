<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Libraries\ImageLib;
use Input, Validator;

class CategoryController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['categories'] = Category::orderBy('name', 'desc')->get();
        return view('categories.index', $this->data);
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
