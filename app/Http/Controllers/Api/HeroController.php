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

    public function show($id) {
        $news = Heros::find($id);
        $this->data['post'] = $news;
        return view('news.show', $this->data);
    }

   
}
