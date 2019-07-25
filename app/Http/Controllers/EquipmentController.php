<?php

namespace App\Http\Controllers;

use App\Models\Piece;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Equipment;
use App\Libraries\ImageLib;

use Input, Validator;

class EquipmentController extends Controller
{
    public function __construct()
    {
      parent::__construct();
    }

    public function index()
    {
      $this->data['equipments'] = Equipment::orderBy('name', 'desc')->get();
      return view('equipments.index', $this->data);
    }

    public function create() {
      $this->data['equipment'] = new Equipment;
      $pieces = Piece::all();
      $data = [];
      foreach ($pieces as $piece) {
          $data[] = $piece->getArrayInfo();
      }
      $this->data['pieces'] = $data;
      return view('equipments.form', $this->data);
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
      $equipment = new Equipment;
      $equipment->title=Input::get('title');
      // $slug = str_slug(Input::get('title'));
      $slug = Input::get('slug');
      $equipment->slug = $equipment->checkSlug($slug);
      $equipment->content = Input::get('content');
      $equipment->short_content = Input::get('short_content');
      $equipment->status = intval(Input::get('status'));
      $equipment->piece1 = Input::get('piece1');
      $equipment->piece2 = Input::get('piece2');

      $fields = $equipment->languageFields();
      foreach ($fields as $field) {
        $key = $field['key'];
        $equipment->$key = Input::get($key);
      }

      $equipment->save();
      if (Input::hasFile('image_upload')) {
        $key = str_random(6);
        $full_item_photo_dir = config('image.image_root').'/equipments';
        $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
        $size = config('image.sizes.equipments');
        ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
        $equipment->image = $fileName;
        $equipment->save();
      }
      $equipment->cleanCache();
      return redirect('/equipments')->withSuccess('Tạo mới thành công');
    }

    public function edit($id) {
      $equipment = Equipment::find($id);
      $this->data['equipment'] = $equipment;

      if (!$equipment) {
        return view('errors.404');
      } else {
          $pieces = Piece::all();
          $data = [];
          foreach ($pieces as $piece) {
              $data[] = $piece->getArrayInfo();
          }
          $this->data['pieces'] = $data;
        return view('equipments.form', $this->data);
      }
    }

    public function update ($id) {
      $rule = [];
      $validator = Validator::make(Input::all(), $rule);
      if ($validator->fails()){
        return back()->withErrors($validator->messages())->withInput();
      }
      $equipment = Equipment::find($id);
      if (!$equipment) {
        return view('error.404');
      } else {
        $equipment->title=Input::get('title');
        $slug = Input::has('slug') ? Input::get('slug', $equipment->title) : str_slug(Input::get('title'));
        $equipment->slug = $equipment->checkSlug($slug, $id);
        $equipment->content = Input::get('content');
        $equipment->short_content = Input::get('short_content');
        $equipment->status = intval(Input::get('status'));
          $equipment->piece1 = Input::get('piece1');
          $equipment->piece2 = Input::get('piece2');
        $fields = $equipment->languageFields();
        foreach ($fields as $field) {
          $key = $field['key'];
          $equipment->$key = Input::get($key);
        }
        $equipment->save();
        if (Input::hasFile('image_upload')) {
          $key = str_random(6);
          $full_item_photo_dir = config('image.image_root').'/equipments';
          $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
          $size = config('image.sizes.equipments');
          ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
          $equipment->image = $fileName;
          $equipment->save();
        }
        $equipment->cleanCache();
        return redirect('/equipments')->withSuccess('Tạo mới thành công');
      }
    }

    public function destroy($id) {
     $equipment = Equipment::find($id);
     if (!empty($equipment->image)) {
        $full_item_photo_dir = config('image.image_root').'/equipments';
        $size = config('image.sizes.equipments');
        ImageLib::delete_image($full_item_photo_dir, $equipment->image, $size);
      }
      $equipment->cleanCache();
      $equipment->delete();
      return redirect('/equipments')->withSuccess('Xóa thành công');
    }
}
