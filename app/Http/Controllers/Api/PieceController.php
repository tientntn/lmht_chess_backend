<?php

namespace App\Http\Controllers\Api;

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
        $per_page = Input::has('per_page') ? 0+Input::get('per_page') : 15;
        $search = Input::get('search');
//        $searchCategory = Input::get('category_id');
        $pieces = Piece::where(function($query) use($search) {
            if($search) {
                return $query->where('title','like','%'.$search.'%');
            }

        })->where('_id','!=','')->paginate($per_page);
        $data = [];
        foreach ($pieces as $piece) {
            $data[] = $piece->getArrayInfo();
        }
        $res = $pieces->toArray();
        $res['data'] = $data;
        $res['total'] = $pieces->total();
        $res['status'] = 200;
        return response()->json($res);
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
            $size = config('image.sizes.equipments');
            ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
            $equipment->image = $fileName;
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
        $equipment->cleanCache();
        $equipment->delete();
        return redirect('/equipments')->withSuccess('Xóa thành công');
    }
}
