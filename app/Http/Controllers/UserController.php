<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\User;
use Input, Validator, Auth, Hash;
use App\Libraries\ImageLib, App\Libraries\Xonlib, App\Libraries\ExcelLib;

class UserController extends Controller {

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  protected $per_page = 50;

  public function __construct() {
      parent::__construct();
  }
  public function index()
  {
    $user = $this->data['auth'];
    $this->data['user'] = $user;

    $this->data['role_id'] = Input::get('role_id');
    $this->data['status'] = Input::has('status') ? Input::get('status') : 5;
    return view('users.index', $this->data);
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

    $user = Auth::user();
    $role_filter = isset($requestData['role']) ? $requestData['role'] : '';

    $total= User::count();
    $role_id = 0;
    $status = 0+$requestData['status'];
    $keyword = $requestData['search']['value'];
    $totalFilter = User::where(function($query) use($status) {
                                    if ($status<5) {
                                      return $query->where('status', '=', $status);
                                    }
                              })
                          ->where(function($query) use($role_filter){
                            if (!empty($role_filter)) {
                              return $query->where('role_id',0+$role_filter);
                            }
                          })
                          ->where(function($query) use($keyword) {
                              if (!empty($keyword)) {
                                return $query->where('username', 'like', '%'.$keyword.'%')
                                ->orWhere('email', 'like', '%'.$keyword.'%')
                                ->orWhere('full_name', 'like', '%'.$keyword.'%');
                              }
                          })
                          ->count();
    $users = User::where(function($query) use($status) {
                        if ($status<5) {
                          return $query->where('status', '=', $status);
                        }
                  })
                    ->where(function($query) use($role_filter){
                            if (!empty($role_filter)) {
                              return $query->where('role_id',0+$role_filter);
                            }
                          })
                  ->where(function($query) use($keyword) {
                    if (!empty($keyword)) {
                      return $query->where('username', 'like', '%'.$keyword.'%')
                        ->orWhere('email', 'like', '%'.$keyword.'%')
                        ->orWhere('full_name', 'like', '%'.$keyword.'%');
                    }
                  })
                  ->skip(0+$start)->take(0+$requestData['length'])->orderBy($columns[$requestData['order'][0]['column']], $sort)->orderBy('id', 'desc')->get();

    $data = array();
    $i = 1+$start;
    foreach ($users as $user) {
      $nestedData=array();
      $nestedData[] = $i.' <input type="checkbox" name="user_ids" class="checkbox_user" id="'.$user->id.'"/>';
      if (empty($user->avatar)) {
        $img = '<img alt="'.$user->full_name.'" src="/images/avatar_default.png" style="width: 50px;">';
      } else {
        $img = '<img alt="'.$user->full_name.'" src="'.config('image.image_url').'/avatars/'.$user->avatar.'_40x40.png">';
      }
      $nestedData[] = '<div class="row"><div class="col-sm-3">'.$img.'</div><div class="col-sm-6"> <strong>'.$user->username.'</strong><br/>'.$user->full_name.'</div></div>';
      $nestedData[] = $user->displayRole();
      $nestedData[] = date('d-m-Y H:i:s', strtotime($user->created_at));
      if (is_null($user->status) || $user->status == 1) {
        $action = '<a class="btn btn-primary btn-margin" user-id="'.$user->id.'" href="'.url('/users/'.$user->id.'/edit').'" data-original-title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a><button data-toggle="modal" data-target="#mod-error" class="lock_user btn btn-success btn-margin" user-id="'.$user->id.'" user-status="1" ><i class="fa fa-unlock"></i></button>';
        $action = $action.'<button data-toggle="modal" data-target="#mod-error" class="delete_user btn btn-danger btn-margin" user-id="'.$user->id.'"  ><i class="fa fa-times"></i></button>';
        $nestedData[] = $action;
      } else {
        $action = '<a class="btn btn-primary btn-margin" user-id="'.$user->id.'" href="'.url('/users/'.$user->id.'/edit').'" data-original-title="Edit" data-toggle="tooltip"><i class="fa fa-pencil"></i></a><button data-toggle="modal" data-target="#mod-error" class="lock_user btn btn-danger btn-margin" user-id="'.$user->id.'" user-status="0" ><i class="fa fa-lock"></i></button>';
        $action = $action.'<button data-toggle="modal" data-target="#mod-error" class="delete_user btn btn-danger btn-margin" user-id="'.$user->id.'"  ><i class="fa fa-times"></i></button>';
        $nestedData[] = $action;
      }
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

  public function create()
  {
    $auth = Auth::user();
    $role_types = [];
    $city_code = '';
    if ($auth->role_id == 1) {
      $role_types = [1,2,3,4,5];
    } else if ($auth->role_id == 2) {
      $role_types = [3];
      $city = $auth->city;
      $city_code = $city ? $city->acis_slug : '';
    } else if ($auth->role_id == 4 ) {
      $role_types = [2,3,5];
      $city = $auth->city;
      $city_code = $city ? $city->acis_slug : '';
    }
    $this->data['roles'] = Role::whereIn('type', $role_types)->orderBy('position', 'asc')->get();
    $user = new User;
    if (Auth::user()->isDistric()) {
      $user->city_slug = $auth->city_slug;
      $user->district_slug = $auth->district_slug;
    }
    if (!empty($city_code)) {
        $city = City::where('slug', $city_code)->first();
        $city_code = $city ? $city->code : '';
    }
    $this->data['user'] = $user;
    $this->data['auth'] = $auth;
    $this->data['facilities'] = Facility::where('facility_type', 'congdong')
                                ->where(function($query) use($city_code) {
                                  if (!empty($city_code)) {
                                    return $query->where('city_code', $city_code);
                                  }
                                })
                                ->orderBy('name', 'asc')->get();


    $data_role = [];
    if ($auth->role_id == 3 || $auth->role_id == 5) {
        $data_role = ['manage', 'admin'];
    } else if ($auth->role_id == 2) {
        $data_role = ['admin'];
    }

    $this->data['surveys'] = Survey::where('status','=',1)
                        ->where(function($query) use($data_role) {
                            if (!empty($data_role)) {
                                return $query->whereNotIn('role', $data_role);
                            }
                        })
                        ->get();
    return view('users.form', $this->data);
  }

  public function store()
  {
    $password = Input::get('password');
    $role = 0+Input::get('role');
    $rule = [
              'email'     => 'required|unique:users,email|email',
              'username'     => 'required|unique:users,username',
            ];
    if (!empty($password)) {
      $rule['password'] = 'required|confirmed|min:4';
    }
    
    $validator = Validator::make(
      Input::all(),
      $rule
    );
    if ($validator->fails()) {
      return back()->withErrors($validator->messages())
                        ->withInput();
    }
    $user = new User;
    $user->username = strtolower(Input::get('username'));
    $user->password = bcrypt(Input::get('password'));
    $user->email = Input::get('email', '');
    $user->full_name = Input::get('full_name', '');
    $user->telephone = Input::get('telephone', '');
    $user->role_id = 0+Input::get('role');
    $user->status = 1;
    $user->address = Input::get('address');
    $user->save();

    return redirect('/users')->withSuccess('Tạo mới thành công');
  }

  public function show($id)
  {
    $user = User::find($id);
    if (!$user) {
      return view('errors.404');
    } else {
      $skinResult = new SkinResult;
      $this->data['skintypes'] = $skinResult->types();
      $this->data['roles'] = Role::all();
      $this->data['user'] = $user;
      return view('users.form', $this->data);
    }
  }

  public function edit($id)
  {
    $user = User::find($id);
    if (!$user) {
      return view('errors.404');
    } else {
      $auth = Auth::user();

      $this->data['user'] = $user;
      return view('users.form', $this->data);
    }  
  }

  public function update($id)
  {
    $password = Input::get('password');
    $role = 0+Input::get('role');
    $rule = [
              'email'     => 'required|unique:users,email,'.$id.',_id|email',
            ];
    if (!empty($password)) {
      $rule['password'] = 'required|confirmed|min:4';
    }
    $validator = Validator::make(
      Input::all(),
      $rule
    );
    if ($validator->fails()) {
      return back()->withErrors($validator->messages())
                        ->withInput();
    }

    $user = User::find($id);
    if (!$user) {
      return view('errors.404');
    } else {

      if (!empty($password)) {
        $user->password = bcrypt(Input::get('password'));
      }
      $user->email = Input::get('email', '');
      $user->full_name = Input::get('full_name', '');
      $user->telephone = Input::get('telephone', '');
      $user->role_id = 0+Input::get('role');
      $user->status = 1;
      $user->save();

      return redirect('/users')->withSuccess('Cập nhật thành công');
    }
    
  }

  public function updateStatus($id)
  {
    $user = User::find($id);
    if (!$user) {
      return view('errors.404');
    } else {
      if (is_null($user->status) || $user->status == 1) {
        $user->status = 0;
      } else {
        $user->status = 1;
      }
      $user->save();
      return redirect('/users')->withSuccess('Cập nhật thành công');
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $user = User::find($id);
    if ($user) {
      if (!empty($user->avatar)) {
        $full_item_photo_dir = config('image.image_root').'/avatars';
        ImageLib::delete_image($full_item_photo_dir, $user->avatar, config('image.images.avatars'));
      }
      $user->delete();
      return redirect('/users')->withSuccess('Xóa thành công');
    } else {
      return redirect('/users')->withErrors('Xóa thất bại');
    }
  }

   public function exportCSV() {
     ini_set("memory_limit", "2000M");
        set_time_limit(3000);
        header("content-type:application/csv;charset=UTF-8");
        header( "Content-type: application/vnd.ms-excel; charset=UTF-8" );
        $filename = 'DataUser_'.date('d_m_Y_H');
        header("Content-disposition: attachment;filename=".$filename.".csv");
        header("Pragma: no-cache");
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Expires: 0");
        echo pack("CCC",0xef,0xbb,0xbf);

        $dataTitle = ['Username', 'Email', 'Phone' ,'Full name', 'Role Type', 'City', 'District', 'Commune', 'Location of referral in VITIMES', 'Status of account'];
        $title = $this->str_putcsv($dataTitle);
        echo $title;
        echo "\n";

        $dataTitle = ['Tên đăng nhập', 'Email', 'Số điện thoại' ,'Họ tên', 'Quyền', 'Tỉnh/thành phố', 'Quận/huyện', 'Xã/phường', 'Cơ sở chuyển gửi', 'Trạng thái hoạt động'];
        $title = $this->str_putcsv($dataTitle);
        echo $title;
        echo "\n";


        $auth = Auth::user();
        $commune_ids = [];
        $district_ids = [];

        $city_filter = Input::get('city', '');
        $district_filter = Input::get('district', '');
        $commune_filter = Input::get('commune', '');
        $role_filter = Input::get('role', '');

        if ($auth->role_id == 2) {
          $city_filter = $auth->city_slug;
          $district_filter = $auth->district_slug;
          $city = CityMG::where('slug', $auth->city_slug)->first();
          $district = $city->districts;
          if ($city) {
            $district = $city->districts()->where('slug',$auth->district_slug)->first();
            if ($district) {
              $communes = $district->communes;
              foreach ($communes as $commune) {
                $commune_ids[] = $commune->slug;
              }
            }
          }
        }
        if ($auth->role_id == 4 || $auth->role_id == 5) {
          $city_filter = $auth->city_slug;
          $city = CityMG::where('slug', $auth->city_slug)->first();
          if ($city) {
            $districts = $city->districts;
            foreach ($districts as $d) {
              $district_ids[] = $d->slug;
            }
          }
        }
        $total= User::count();
        $query = User::where(function($query) use($commune_ids) {
                                if (!empty($commune_ids)) {
                                  return $query->whereIn('commune_slug', $commune_ids);
                                }
                          })
                          ->where(function($query) use($district_ids) {
                                if (!empty($district_ids)) {
                                  return $query->whereIn('district_slug', $district_ids);
                                }
                          })
                          ->where(function($query) use($city_filter){
                            if (!empty($city_filter)) {
                              return $query->where('city_slug',$city_filter);
                            }
                          })
                          ->where(function($query) use($district_filter){
                            if (!empty($district_filter)) {
                              return $query->where('district_slug',$district_filter);
                            }
                          })
                          ->where(function($query) use($commune_filter){
                            if (!empty($commune_filter)) {
                              return $query->where('commune_slug',$commune_filter);
                            }
                          })
                          ->where(function($query) use($role_filter){
                            if (!empty($role_filter)) {
                              return $query->where('role_id',0+$role_filter);
                            }
                          });


        $query->chunk(10, function($users) {
                  foreach ($users as $user) {
                    $row['Username'] = $user->username;
                    $row['Email'] = $user->email;
                    $row['Phone'] = ''.$user->telephone;
                    $row['full_name'] = $user->full_name;
                    $row['role_type'] = $user->displayRole();
                    $row['City'] = $user->city ? $user->city->name : '';
                    $row['District'] = $user->district ? $user->district->name : '';
                    $row['Commune'] = $user->commune ? $user->commune->name : '';
                    $facility = $user->role_id == 3 ? $user->facility : null;
                    $row['facility'] = $facility ? $facility->name : '';
                    $row['status'] = $user->displayStatus();


                    $csv = $this->str_putcsv($row) ;
                    echo $csv;
                    echo "\n";
                    flush();
                }
        });

        die;
  }

  public function exportExcel(){
        // $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_phpTemp;
        // $cacheSettings = array( 'memoryCacheSize' => '500M');
        // PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        ini_set("memory_limit", "500M");
        $type = Input::get('type');
        $page = Input::has('page') ? 0+Input::get('page') : 0;
        if ($page == 0) {
            ExcelLib::createTemplate('export_user');
        }
        $per_page = 15000;
        $skip = ($page)*$per_page;
        
        $data = array();
        if ($type == '1') {
          $total = User::where('devices.browser', '!=', 'App Happyskin')->where('status', '!=', 0)->count();
          $users = User::where('devices.browser', '!=', 'App Happyskin')->where('status', '!=', 0)->orderBy('name', 'asc')->skip($skip)->take($per_page)->get();
        } elseif ($type == '2') {
          $total = User::where('devices.app_id', '=', 2)->where('devices.browser', '=', 'App Happyskin')->where('status', '!=', 0)->count();
          $users = User::where('devices.app_id', '=', 2)->where('devices.browser', '=', 'App Happyskin')->where('status', '!=', 0)->orderBy('name', 'asc')->skip($skip)->take($per_page)->get();
        } elseif ($type == '3') {
          $total = User::where('devices.app_id', '=', 3)->where('devices.browser', '=', 'App Happyskin')->where('status', '!=', 0)->count();
          $users = User::where('devices.app_id', '=', 3)->where('devices.browser', '=', 'App Happyskin')->where('status', '!=', 0)->orderBy('name', 'asc')->skip($skip)->take($per_page)->get();
        } elseif ($type == '4') {
          $total = User::whereNotNull('email')->where('status', '!=', 0)->count();
          $users = User::whereNotNull('email')->where('status', '!=', 0)->orderBy('name', 'asc')->skip($skip)->take($per_page)->get();
        } elseif ($type == '5') {
          $total = User::whereNotNull('skintest')->where('skintest', '!=', '')->where('status', '!=', 0)->count();
          $users = User::whereNotNull('skintest')->where('skintest', '!=', '')->where('status', '!=', 0)->orderBy('skintest_completed', 'desc')->skip($skip)->take($per_page)->get();
        } else {
          $total = User::where('status', '!=', 0)->count();
          $users = User::where('status', '!=', 0)->orderBy('name', 'asc')->skip($skip)->take($per_page)->get();
        }

        if (count($users)==0) {
          $rels['status'] = 200;
          $rels['filename'] = 'export_user_'.date('d-m-Y');
          return response()->json($rels);
        } else {
          $data = array();
          foreach ($users as $user) {
            $row['email'] = $user->email;
            $row['username'] = $user->username;
            $row['first_name'] = $user->first_name;
            $row['last_name'] = $user->last_name;
            $row['full_name'] = $user->full_name;
            $row['created_at'] = date('d-m-Y H:i:s', strtotime($user->created_at));
            $row['skintest'] = $user->skintest;
            $row['skintest_completed'] = $user->skintest_completed ? date('d-m-Y H:i:s', strtotime($user->skintest_completed)) : '';
            $data[] = $row;
          }
          $data['datauser'] = $data;

          $fileName = 'export_user_'.date('d-m-Y');
          if ($page == 0) { 
              ExcelLib::createTemplate($fileName);
          }
          ExcelLib::exportExcelExist($fileName.'.xlsx', $data);
          $count_export = $skip+count($users);
          $page = $page+1;
          $rels['status'] = 1;
          $rels['page'] = $page;
          $rels['progress'] = round($count_export*100/$total);
          return response()->json($rels);
        }
    }

    public function downloadExcel($file)
    {
        ExcelLib::downloadExcel($file.'.xlsx');
    }

    public function deleteMulti()
    {
      if(Input::has('user_ids')) {
        $user_ids = Input::get('user_ids');
        foreach ($user_ids as $user_id) {
          $user = User::find($user_id);
          if($user) {
            if (!empty($user->avatar)) {
              $full_item_photo_dir = config('image.image_root').'/avatars';
              ImageLib::delete_image($full_item_photo_dir, $user->avatar, config('image.images.avatars'));
            }
            $user->delete();
          }
        }
        return back()->withSuccess('Xóa thành công');
      } else {
        return back()->withErrors('Xóa thất bại');
      }
    }

    function dashboardUser($id) {
      $user = User::find($id);
      if (!$user) {
        return view('errors.404');
      } else {
        $this->data['user'] = $user;
        // dd($this->data['user']);
        $this->data['count_comment'] = Comment::where('user_id', '=', $user->id)->get();
        $this->data['user_raty'] = UserRaty::where('user_id', '=', $user->id)->get();
        $groupfeeds = GroupFeed::where('user_id', '=', $user->id)->get();
        // dd($groupfeed);
        if($groupfeeds) {
          $this->data['groupfeeds'] = $groupfeeds;
        }
        $skinResult = new SkinResult;
        $this->data['skintypes'] = $skinResult->types();
        $this->data['roles'] = Role::all();
        return view('users.dashboardUser',$this->data);
      }
    }

    public function getChangePassword() {
      return view('users.change_password',$this->data);
    }

    public function postChangePassword() {
      $auth = Auth::user();
      $username = $auth->username;
       $input_valid = [
          'password_old' => 'required',
          'password' => 'required',
          'password_confirm' => 'required|same:password',
      ];
      $validator = Validator::make(
          Input::all(), $input_valid
      );

      if ($validator->fails()) {
          return redirect()->back()
              ->withErrors($validator);
      } else {
        //Auth::logout();
       // $credentials = ['username' => $username, 'password' => Input::get('password_old') ];

        $current_password = Auth::User()->password;           
        if(Hash::check(Input::get('password_old'), $current_password))
        {           
          $user_id = Auth::User()->id;
          $obj_user = User::find($user_id);
          $obj_user->password = Hash::make(Input::get('password'));;
          $obj_user->save(); 
          return redirect('users/change-password')->withSuccess('Đổi mật khẩu thành công');
        //}


        // if (Auth::attempt($credentials))
        // {
        //   $user = Auth::user();
        //   $user->password = bcrypt(Input::get('password'));
        //   $user->save();

        //   return redirect()->withSuccess('Đổi mật khẩu thành công');
        } else {
          return redirect()->back()->withErrors('Mật khẩu hiện tại không đúng');
        }
      }
    }

     public function str_putcsv($input, $delimiter = ',', $enclosure = '"')
    {
        $fp = fopen('php://temp', 'r+');
        fputcsv($fp, $input, $delimiter, $enclosure);
        rewind($fp);
        $data = fread($fp, 1048576);
        $output = stream_get_contents($fp);
        fclose($fp);
        return rtrim($data, "\n");
    }
}
