<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
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
        $data = [];
        $per_page = Input::has('per_page') ? 0+Input::get('per_page') : 15;
        $search = Input::get('search');
        $lang = Input::get('lang', 'vi');
        if (empty($lang)) {
            $lang = 'vi';
        }

        $newss1 = News::where(function($query) use($search) {
                            if($search) {
                                return $query->where('title','like','%'.$search.'%');
                            }
                        })
                        ->where(function($query) use($lang) {
                            if($lang) {
                                return $query->where('lang','=', $lang);
                            }
                        })
                        ->where('status', '1')
                        ->orderBy('_id', 'desc')->get();

        $newss = News::where(function($query) use($search) {
                            if($search) {
                                return $query->where('title','like','%'.$search.'%');
                            }
                        })
                        ->where(function($query) use($lang) {
                            if($lang) {
                                return $query->where('lang','=', $lang);
                            }
                        })
                        ->where('status','!=', '1')
                        ->orderBy('_id', 'desc')->paginate($per_page);
        $data = [];
        foreach ($newss1 as $news) {
            $data[] = $news->getArrayInfo();
        }
        foreach ($newss as $news) {
            $data[] = $news->getArrayInfo();
        }
        $res = $newss->toArray();
        $res['data'] = $data;
        $res['total'] = $newss->total();
        $res['status'] = 200;
        return response()->json($res);
    }

    public function show($id) {
        $news = News::find($id);
        $this->data['post'] = $news;
        return view('news.show', $this->data);
    }
}
