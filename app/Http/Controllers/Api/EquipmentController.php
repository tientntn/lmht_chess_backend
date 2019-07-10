<?php

namespace App\Http\Controllers\Api;

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
      $equipments = Equipment::orderBy('name', 'asc')->paginate(100);
      $data = [];
      foreach ($equipments as $equipment) {
        $data[] = $equipment->getArrayInfo();
      }
      $res = [
        'status' => 200,
        'data' => $data,
        'total' => $equipments->total()
      ];
      return $res;
    }

}
