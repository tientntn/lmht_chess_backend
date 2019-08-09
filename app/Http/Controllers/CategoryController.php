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
        $this->data['categories'] = Category::orderBy('name', 'asc')->get();
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
        $category->content = Input::get('content');
        $power = Input::get('power');
        $data = explode(';', $power);

        $data_power = [];
        foreach ($data as $dt) {
            $values = $data = explode('=', $dt);
            if (count($values) == 2) {
                $data_power[$values[0]] = $values[1];
            }
        }
        $category->power = $data_power;
        $category->power_text = $power;
        $fields = $category->languageFields();
        foreach ($fields as $field) {
            $key = $field['key'];
            $category->$key = Input::get($key);
        }

        $power = Input::get('power_en');
        $data = explode(';', str_replace("\r\n", "", $power));
        $data_power = [];
        foreach ($data as $dt) {
            $values = $data = explode('=', $dt);
            if (count($values) == 2) {
                $data_power[$values[0]] = $values[1];
            }
        }
        $category->power_data_en = $data_power;
        $category->power_text_en = $power;

        $fields = $category->languageFields();
        foreach ($fields as $field) {
            $key = $field['key'];
            $category->$key = Input::get($key);
        }
        
        $category->save();

        if (Input::hasFile('image_upload')) {
                $key = str_random(6);
                $full_item_photo_dir = config('image.image_root').'/categories';
                $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
                $size = config('image.sizes.categories');
                ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
                $category->image_active = $fileName;
                $category->save();
            }

            if (Input::hasFile('image_upload2')) {
                $key = str_random(6);
                $full_item_photo_dir = config('image.image_root').'/categories';
                $fileName = str_slug(Input::file('image_upload2')->getClientOriginalName()).'_'.$key;
                $size = config('image.sizes.categories');
                ImageLib::upload_image(Input::file('image_upload2'), $full_item_photo_dir, $fileName, $size, 0);
                $category->image_inactive = $fileName;
                $category->save();
            }

        $category->cleanCache();
        return redirect('/categories')->withSuccess('Tạo mới thành công');
    }

    public function edit($id) {
        $category = Category::find($id);
        $this->data['category'] = $category;
        // dd($category);
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
            $category->content = Input::get('content');
            $power = Input::get('power');
            $data = explode(';', str_replace("\r\n", "", $power));
            $data_power = [];
            foreach ($data as $dt) {
                $values = $data = explode('=', $dt);
                if (count($values) == 2) {
                    // $row = [];
                    // $row[$values[0]] = $values[1];
                    $data_power[$values[0]] = $values[1];
                }
            }
            $category->power_data = $data_power;
            $category->power_text = $power;

            $power = Input::get('power_en');
            $data = explode(';', str_replace("\r\n", "", $power));
            $data_power = [];
            foreach ($data as $dt) {
                $values = $data = explode('=', $dt);
                if (count($values) == 2) {
                    $data_power[$values[0]] = $values[1];
                }
            }
            $category->power_data_en = $data_power;
            $category->power_text_en = $power;

            $fields = $category->languageFields();
            foreach ($fields as $field) {
                $key = $field['key'];
                $category->$key = Input::get($key);
            }

            $category->save();

            if (Input::hasFile('image_upload')) {
                $key = str_random(6);
                $full_item_photo_dir = config('image.image_root').'/categories';
                $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
                $size = config('image.sizes.categories');
                ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
                $category->image_active = $fileName;
                $category->save();
            }

            if (Input::hasFile('image_upload2')) {
                $key = str_random(6);
                $full_item_photo_dir = config('image.image_root').'/categories';
                $fileName = str_slug(Input::file('image_upload2')->getClientOriginalName()).'_'.$key;
                $size = config('image.sizes.categories');
                ImageLib::upload_image(Input::file('image_upload2'), $full_item_photo_dir, $fileName, $size, 0);
                $category->image_inactive = $fileName;
                $category->save();
            }


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
