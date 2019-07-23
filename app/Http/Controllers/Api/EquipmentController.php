<?php

namespace App\Http\Controllers\Api;

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
        $data = [];
        $per_page = Input::has('per_page') ? 0+Input::get('per_page') : 15;
        $search = Input::get('search');
        $searchCategory = Input::get('category_id');
        $heroes = Equipment::where(function($query) use($search) {
            if($search) {
                return $query->where('title','like','%'.$search.'%');
            }
        })->where(function($query) use($searchCategory) {
            if($searchCategory) {
                return $query->where('category', $searchCategory);
            }
        })
            ->where('_id','!=','')
            ->orderBy('name', 'asc')
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

    public function search() {
        $search1 = Input::get('piece_id_1');
        $search2 = Input::get('piece_id_2');
        if($search1 && $search2) {
            $data = $this->search1();
            return $data;
        } elseif ($search1 || $search2) {
            if($search1) {
                $data = $this->search2($search1);
                return $data;
            } else {
                $data = $this->search2($search2);
                return $data;
            }
        } else {
            $res['data'] = [];
            $res['status'] = 404;
            return response()->json($res);
        }
    }

    public function search1() {
        $search1 = Input::get('piece_id_1');
        $search2 = Input::get('piece_id_2');
            $equipments = Equipment::where(function($query) use($search1) {
                if($search1) {
                    return $query->where('piece1', $search1);
                }
            })->where(function($query) use($search2) {
                if($search2) {
                    return $query->where('piece2', $search2);
                }
            })->where(function($query) use($search1,$search2) {
                if(!$search2 && !$search1) {
                    return $query->where('_id', '');
                }
            })->paginate(100);
        $data = [];
        $search = '';
        if($search1) {
            $search = $search1;
            if($search2) {
                $search ='';
            }
        }elseif ($search2) {
            $search = $search2;
            if($search1) {
                $search ='';
            }
        }
        foreach ($equipments as $equipment) {
            $data[] = $equipment->getArrayInfoPiece($search);
        }
        $res = $equipments->toArray();
        $res['data'] = $data;
        $res['total'] = $equipments->total();
        $res['status'] = 200;
        return response()->json($res);
    }

    public function search2($search) {
        $piece = Piece::find($search);
        $data = [];
        $search = '';
        $data[] = $piece->getArrayInfo();
        $res['data'] = $data;
        $res['status'] = 200;
        return response()->json($res);
    }
}
