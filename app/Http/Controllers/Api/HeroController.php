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

     public function chess() {
        $hero_ids = Input::get('heros');
        $data_category = [];
        foreach ($hero_ids as $hero_id) {
            $hero = Heros::find($hero_id);
            foreach ($hero->category as $category_id) {
                if (isset($data_category[$category_id])) {
                    $data_category[$category_id]['count'] = $data_category[$category_id]['count'] +1;
                } else {
                    $data_category[$category_id]['count'] = 1;
                }
            }
            // $data_category = array_merge($data_category, $hero->category);
        }

        $data_results = [];

        foreach ($data_category as $key => $cat) {
            $category = Category::find($key);
            $cat['name'] = $category->trans('title');
            $cat['image'] = $category->urlPath('','image_active');
            $cat['content'] = $category->trans('content');
            $cat['active'] = false;


            $power_data = $category->trans('power_data');
            $new_power = [];
            $is_active = '';
            $i = 0;
            $count = count($power_data);
            if ($power_data) {
                foreach ($power_data as $number => $content) {
                    $active = false;
                    if ($i + 1  == $count && $number <= $cat['count']) {
                        $active = true;
                    }
                    if ($i + 1  < $count && $number <= $cat['count'] && empty($is_active)) {
                        $active = true;
                    }
                    $row = [
                        'key' => $number,
                        'content' => $content,
                        'active' => $active
                    ];
                    $new_power[] = $row;
                    if ($active) {
                        $cat['active'] = true;
                    }
                    $i++;
                    # code...
                }
            }

            $cat['power'] = $new_power;
            if ($cat['active']) {
                array_unshift($data_results, $cat);
            } else {
                $data_results[] = $cat;
            }
        }

        // $heroes = Heros::whereIn('_id', $hero_ids)->get();
        // $data_heroes = $heroes->toArray();
        $res['data'] = [
            'results' => $data_results
            // 'heroes' => $data_heroes,
            
        ];
        $res['status'] = 200;
        return response()->json($res);
    }

   
}
