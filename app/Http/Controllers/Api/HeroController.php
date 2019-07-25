<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Models\Heros;
use Illuminate\Http\Request;
use App\Models\Piece;
use App\Http\Controllers\Controller;

use App\Models\Equipment;
use App\Libraries\ImageLib;

use Input, Validator;

class HeroController extends Controller
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
        $heroes = Heros::where(function($query) use($search) {
                        if($search) {
                            return $query->where('title','like','%'.$search.'%');
                        }
                    })->where(function($query) use($searchCategory) {
                        if($searchCategory) {
                            return $query->where('category', $searchCategory);
                        }
                    })
                    ->where('_id','!=','')
                    ->orderBy('title', 'asc')
                    ->paginate($per_page);
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
        $this->data['hero'] = new Heros;
        $categories = Category::all();
        $data = [];
        foreach ($categories as $category) {
            $data[] = $category->getArrayInfo();
        }
        $this->data['categories'] = $data;
//        dd( $this->data);
        return view('heroes.form', $this->data);
    }

    public function store () {
        $rule = [
            'title'     => 'required',
            'content'     => 'required',
        ];
        $validator = Validator::make(Input::all(), $rule);
        if ($validator->fails()){
            return back()->withErrors($validator->messages())->withInput();
        }
        $hero = new Heros();
        $hero->title=Input::get('title');
        // $slug = str_slug(Input::get('title'));
        $slug = Input::get('slug');
        $hero->slug = $hero->checkSlug($slug);
        $hero->content = Input::get('content');
        $hero->status = intval(Input::get('status'));
        $hero->category = Input::get('category');

        $fields = $hero->languageFields();
        foreach ($fields as $field) {
            $key = $field['key'];
            $hero->$key = Input::get($key);
        }

        $hero->save();
        if (Input::hasFile('image_upload')) {
            $key = str_random(6);
            $full_item_photo_dir = config('image.image_root').'/heroes';
            $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
            $size = config('image.sizes.heroes');
            ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
            $hero->image = $fileName;
            $hero->save();
        }
        $hero->cleanCache();
        return redirect('/heroes')->withSuccess('Tạo mới thành công');
    }

    public function edit($id) {
        $hero = Heros::find($id);
        $this->data['hero'] = $hero;

        if (!$hero) {
            return view('errors.404');
        } else {
            $categories = Category::all();
            $data = [];
            foreach ($categories as $category) {
                $data[] = $category->getArrayInfo();
            }
            $this->data['categories'] = $data;
            return view('heroes.form', $this->data);
        }
    }

    public function update ($id) {
        $rule = [];
        $validator = Validator::make(Input::all(), $rule);
        if ($validator->fails()){
            return back()->withErrors($validator->messages())->withInput();
        }
        $hero = Heros::find($id);
        if (!$hero) {
            return view('error.404');
        } else {
            $hero->title=Input::get('title');
            $slug = Input::has('slug') ? Input::get('slug', $hero->title) : str_slug(Input::get('title'));
            $hero->slug = $hero->checkSlug($slug, $id);
            $hero->content = Input::get('content');
            $hero->short_content = Input::get('short_content');
            $hero->status = intval(Input::get('status'));
            $hero->category = Input::get('category');
//            $hero->piece2 = Input::get('piece2');
            $fields = $hero->languageFields();
            foreach ($fields as $field) {
                $key = $field['key'];
                $hero->$key = Input::get($key);
            }
            $hero->save();
            if (Input::hasFile('image_upload')) {
                $key = str_random(6);
                $full_item_photo_dir = config('image.image_root').'/heroes';
                $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
                $size = config('image.sizes.heroes');
                ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
                $hero->image = $fileName;
                $hero->save();
            }
            $hero->cleanCache();
            return redirect('/heroes')->withSuccess('Tạo mới thành công');
        }
    }

    public function destroy($id) {
        $hero = Heros::find($id);
        if (!empty($hero->image)) {
            $full_item_photo_dir = config('image.image_root').'/heroes';
            $size = config('image.sizes.heroes');
            ImageLib::delete_image($full_item_photo_dir, $hero->image, $size);
        }
        $hero->cleanCache();
        $hero->delete();
        return redirect('/heroes')->withSuccess('Xóa thành công');
    }
}
