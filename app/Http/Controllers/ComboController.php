<?php

namespace App\Http\Controllers;

use App\Models\Combo;
use Illuminate\Http\Request;
use App\Libraries\ImageLib;

use Input, Validator;

class ComboController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['combos'] = Combo::orderBy('title', 'asc')->get();
        return view('combos.index', $this->data);
    }

    public function create() {
        $this->data['combo'] = new Combo;
        return view('combos.form', $this->data);
    }

    public function store () {
        $rule = [
            'title'     => 'required',
            'link'     => 'required',
        ];
        $validator = Validator::make(Input::all(), $rule);
        if ($validator->fails()){
            return back()->withErrors($validator->messages())->withInput();
        }
        $combo = new Combo();
        $combo->title=Input::get('title');
        // $slug = str_slug(Input::get('title'));
        $slug = Input::get('slug');
        $combo->slug = $combo->checkSlug($slug);
        $combo->link = Input::get('link');
        $combo->status = intval(Input::get('status'));
        if (Input::hasFile('image_upload')) {
            $key = str_random(6);
            $full_item_photo_dir = config('image.image_root').'/combos';
            $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
            $size = config('image.sizes.combos');
            ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
            $combo->image = $fileName;
            $combo->save();
        }

        $combo->save();
        $combo->cleanCache();
        return redirect('/combos')->withSuccess('Tạo mới thành công');
    }

    public function edit($id) {
        $combo = Combo::find($id);
        $this->data['combo'] = $combo;
        if (!$combo) {
            return view('errors.404');
        } else {
            return view('combos.form', $this->data);
        }
    }

    public function update ($id) {
        $rule = [];
        $validator = Validator::make(Input::all(), $rule);
        if ($validator->fails()){
            return back()->withErrors($validator->messages())->withInput();
        }
        $combo = Combo::find($id);
        if (!$combo) {
            return view('error.404');
        } else {
            $combo->title=Input::get('title');
            $slug = Input::has('slug') ? Input::get('slug', $combo->title) : str_slug(Input::get('title'));
            $combo->slug = $combo->checkSlug($slug, $id);
            $combo->link = Input::get('link');
            $combo->status = intval(Input::get('status'));
            if (Input::hasFile('image_upload')) {
                $key = str_random(6);
                $full_item_photo_dir = config('image.image_root').'/combos';
                $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
                $size = config('image.sizes.combos');
                ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
                $combo->image = $fileName;
                $combo->save();
            }

            $combo->save();
            $combo->cleanCache();
            return redirect('/combos')->withSuccess('Tạo mới thành công');
        }
    }

    public function destroy($id) {
        $combo = Combo::find($id);
        if (!empty($combo->image)) {
            $full_item_photo_dir = config('image.image_root').'/pieces';
            $size = config('image.sizes.equipments');
            ImageLib::delete_image($full_item_photo_dir, $combo->image, $size);
        }
        $combo->cleanCache();
        $combo->delete();
        return redirect('/combos')->withSuccess('Xóa thành công');
    }
}

