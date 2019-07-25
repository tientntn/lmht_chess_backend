<?php

namespace App\Http\Controllers;

use App\Models\Piece;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Equipment;
use App\Libraries\ImageLib;

use Input, Validator;

class PieceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['pieces'] = Piece::orderBy('title', 'desc')->get();
        return view('pieces.index', $this->data);
    }

    public function create() {
        $this->data['piece'] = new Piece;
        return view('pieces.form', $this->data);
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
        $equipment = new Piece;
        $equipment->title=Input::get('title');
        // $slug = str_slug(Input::get('title'));
        $slug = Input::get('slug');
        $equipment->slug = $equipment->checkSlug($slug);
        $equipment->content = Input::get('content');
        $equipment->status = intval(Input::get('status'));

        $fields = $equipment->languageFields();
        foreach ($fields as $field) {
            $key = $field['key'];
            $equipment->$key = Input::get($key);
        }

        $equipment->save();
        if (Input::hasFile('image_upload')) {
            $key = str_random(6);
            $full_item_photo_dir = config('image.image_root').'/pieces';
            $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
            $size = config('image.sizes.pieces');
            ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
            $equipment->image = $fileName;
            $equipment->save();
        }
        if (Input::hasFile('image_upload_list')) {
            $key = str_random(6);
            $full_item_photo_dir = config('image.image_root').'/piecesList';
            $fileName = str_slug(Input::file('image_upload_list')->getClientOriginalName()).'_'.$key;
            $size = config('image.sizes.pieces');
            ImageLib::upload_image(Input::file('image_upload_list'), $full_item_photo_dir, $fileName, $size, 0);
            $equipment->image_list = $fileName;
            $equipment->save();
        }
        $equipment->cleanCache();
        return redirect('/pieces')->withSuccess('Tạo mới thành công');
    }

    public function edit($id) {
        $equipment = Piece::find($id);
        $this->data['piece'] = $equipment;
        if (!$equipment) {
            return view('errors.404');
        } else {
            return view('pieces.form', $this->data);
        }
    }

    public function update ($id) {
        $rule = [];
        $validator = Validator::make(Input::all(), $rule);
        if ($validator->fails()){
            return back()->withErrors($validator->messages())->withInput();
        }
        $equipment = Piece::find($id);
        if (!$equipment) {
            return view('error.404');
        } else {
            $equipment->title=Input::get('title');
            $slug = Input::has('slug') ? Input::get('slug', $equipment->title) : str_slug(Input::get('title'));
            $equipment->slug = $equipment->checkSlug($slug, $id);
            $equipment->content = Input::get('content');
            $equipment->short_content = Input::get('short_content');
            $equipment->status = intval(Input::get('status'));
            $fields = $equipment->languageFields();
            foreach ($fields as $field) {
                $key = $field['key'];
                $equipment->$key = Input::get($key);
            }
            $equipment->save();
            if (Input::hasFile('image_upload')) {
                $key = str_random(6);
                $full_item_photo_dir = config('image.image_root').'/pieces';
                $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
                $size = config('image.sizes.equipments');
                ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
                $equipment->image = $fileName;
                $equipment->save();
            }
            if (Input::hasFile('image_upload_list')) {
//                dd(Input::all());
                $key = str_random(6);
                $full_item_photo_dir = config('image.image_root').'/piecesList';
                $fileName = str_slug(Input::file('image_upload_list')->getClientOriginalName()).'_'.$key;
                $size = config('image.sizes.pieces');
                ImageLib::upload_image(Input::file('image_upload_list'), $full_item_photo_dir, $fileName, $size, 0);
                $equipment->image_list = $fileName;
                $equipment->save();
            }
            $equipment->cleanCache();
            return redirect('/pieces')->withSuccess('Tạo mới thành công');
        }
    }

    public function destroy($id) {
        $equipment = Piece::find($id);
        if (!empty($equipment->image)) {
            $full_item_photo_dir = config('image.image_root').'/pieces';
            $size = config('image.sizes.equipments');
            ImageLib::delete_image($full_item_photo_dir, $equipment->image, $size);
        }
        if (!empty($equipment->image_list)) {
            $full_item_photo_dir = config('image.image_root').'/pieces';
            $size = config('image.sizes.equipments');
            ImageLib::delete_image($full_item_photo_dir, $equipment->image, $size);
        }
        $equipment->cleanCache();
        $equipment->delete();
        return redirect('/pieces')->withSuccess('Xóa thành công');
    }
}
