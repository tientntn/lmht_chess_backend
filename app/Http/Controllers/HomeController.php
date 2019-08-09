<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User, App\Models\Category;

use Session, Schema, Auth, Input;
use Carbon\Carbon, Cache;

class HomeController extends Controller
{
    public function __construct() {
        parent::__construct();
    }
    public function index() {
      $this->data['user'] = $this->data['auth'];
      return view('index', $this->data);
    }

    public function test() {
    	$categories = Category::first();
    	foreach ($categories as $category) {

    		$data_power = $category->power_data;
    		$new_power = [];
    		foreach ($data_power as $key => $value) {
    			foreach ($value as $index => $vl) {
    				$new_power[$index] = $vl;
    			}
    			
    		}
    		$category->power_data = $new_power;

    		$data_power = $category->power_data_en;
    		$new_power = [];
    		foreach ($data_power as $key => $value) {
    			foreach ($value as $index => $vl) {
    				$new_power[$index] = $vl;
    			}
    			
    		}
    		$category->power_data_en = $new_power;
    		$category->save();
    	}
    	dd($categories);
    }
}
