<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use App\Models\Equipment;
use App\Libraries\ImageLib;
use Input, Validator;

class NewsController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['newss'] = News::orderBy('name', 'asc')->get();
        return view('news.index', $this->data);
    }

     public function search()
      {
        $requestData= $_REQUEST;

        $columns = array(
          0 => '_id', 
          1 => 'username',
          2 => 'role_id',
          3 => 'created_at',
          4 => 'created_at'
        );

        $start = isset($requestData['start']) ? $requestData['start'] : 0 ;
        $sort = isset($requestData['order'][0]['dir']) && $requestData['order'][0]['dir'] == 'asc' ? 'asc' : 'desc';

        $total= News::count();
        $keyword = isset($requestData['search']['value']) ? $requestData['search']['value'] : '';
        $totalFilter = News::where(function($query) use($keyword) {
                                  if (!empty($keyword)) {
                                    return $query->where('title', 'like', '%'.$keyword.'%');
                                  }
                              })
                              ->count();
        $newss = News::where(function($query) use($keyword) {
                                  if (!empty($keyword)) {
                                    return $query->where('title', 'like', '%'.$keyword.'%');
                                  }
                              })
                      ->skip(0+$start)->take(0+$requestData['length'])->orderBy($columns[$requestData['order'][0]['column']], $sort)->orderBy('id', 'desc')->get();

        $data = array();
        $i = 1+$start;
        foreach ($newss as $news) {
          $nestedData=array();
          $nestedData[] = $i;
          $nestedData[] = '<img alt="'.$news->title.'" src="'.$news->urlPath('100x100').'" style="width: 50px;">';

          $nestedData[] = $news->title;
          $nestedData[] = $news->lang;
          $nestedData[] = date('d-m-Y H:i:s', strtotime($news->created_at));
        
            $action = '<a class="btn btn-primary btn-margin" news-id="'.$news->id.'" href="'.url('/newss/'.$news->id.'/edit').'" data-original-title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>';
            $action = $action.'<button data-toggle="modal" data-target="#mod-error" class="delete_news btn btn-danger btn-margin" news-id="'.$news->id.'"  ><i class="fa fa-times"></i></button>';
            $nestedData[] = $action;

          $data[] = $nestedData;
          $i++;
        }
        $json_data = array(
                    "draw"            => intval( $_REQUEST['draw'] ),
                    "recordsTotal"    => $total,
                    "recordsFiltered" => $totalFilter,
                    "data"            => $data
                );
        return response()->json($json_data);
      }

    public function create() {
        $this->data['news'] = new News();
        return view('news.form', $this->data);
    }

    public function store () {
        $rule = [
            'title'     => 'required',
        ];
        $validator = Validator::make(Input::all(), $rule);
        if ($validator->fails()){
            return back()->withErrors($validator->messages())->withInput();
        }
        $news = new News;
        $news->title=Input::get('title');
        $slug = Input::get('slug');
        $news->slug = $news->checkSlug($slug);
        $news->content =  Input::get('content');
        $news->short_content =  Input::get('short_content');
        $news->lang =  Input::get('lang');
        
        $fields = $news->languageFields();
        if ($fields) {
            foreach ($fields as $field) {
                $key = $field['key'];
                $news->$key = Input::get($key);
            }
        }
        
        $news->save();

        if (Input::hasFile('image_upload')) {
            $key = str_random(6);
            $full_item_photo_dir = config('image.image_root').'/news';
            $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
            $size = config('image.sizes.news');
            ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
            $news->image = $fileName;
            $news->save();
        }

        $news->cleanCache();
        return redirect('/newss')->withSuccess('Tạo mới thành công');
    }

    public function edit($id) {
        $news = News::find($id);
        $this->data['news'] = $news;

        if (!$news) {
            return view('errors.404');
        } else {
//            $pieces = Piece::all();
//            $data = [];
//            foreach ($pieces as $piece) {
//                $data[] = $piece->getArrayInfo();
//            }
//            $this->data['pieces'] = $data;
            return view('news.form', $this->data);
        }
    }

    public function update ($id) {
        $rule = [];
        $validator = Validator::make(Input::all(), $rule);
        if ($validator->fails()){
            return back()->withErrors($validator->messages())->withInput();
        }
        $news = News::find($id);
        if (!$news) {
            return view('error.404');
        } else {
            $news->title=Input::get('title');
            $slug = Input::get('title');
            $news->slug = $news->checkSlug($slug);
            $news->content =  Input::get('content');
            $news->short_content =  Input::get('short_content');
            $news->lang =  Input::get('lang');

            $fields = $news->languageFields();
            if ($fields) {
                foreach ($fields as $field) {
                    $key = $field['key'];
                    $news->$key = Input::get($key);
                }
            }

            $news->save();

            if (Input::hasFile('image_upload')) {
                $key = str_random(6);
                $full_item_photo_dir = config('image.image_root').'/news';
                $fileName = str_slug(Input::file('image_upload')->getClientOriginalName()).'_'.$key;
                $size = config('image.sizes.news');
                ImageLib::upload_image(Input::file('image_upload'), $full_item_photo_dir, $fileName, $size, 0);
                $news->image = $fileName;
                $news->save();
            }

            $news->cleanCache();
            return redirect('/newss')->withSuccess('Tạo mới thành công');
        }
    }

    public function destroy($id) {
        $news = News::find($id);
        $news->cleanCache();
        $news->delete();
        return redirect('/newss')->withSuccess('Xóa thành công');
    }
}
