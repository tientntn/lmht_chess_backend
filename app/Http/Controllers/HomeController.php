<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

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
}
