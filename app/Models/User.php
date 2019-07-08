<?php

namespace App\Models;

    use Illuminate\Notifications\Notifiable;
    use Illuminate\Auth\Authenticatable;
    use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
    // use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
    use Moloquent;
    use Auth;

class User extends Moloquent implements AuthenticatableContract {
    use Notifiable;
    use Authenticatable;
    protected $collection = 'users';
    protected $connection = 'mongodb';

// use Illuminate\Notifications\Notifiable;
// use Illuminate\Foundation\Auth\User as Authenticatable;
// class User extends Authenticatable
// {
//     protected $connection = 'mysql';
//     use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function role() 
    {
        return $this->belongsTo('App\Models\Role', 'role_id', 'type');
    }

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isRoot() {
        return $this->role_id == 1;
    }

    public function isAdmin() {
        //return $this->role_id == 1;
        return true;
    }

    public function isDistric() {
        return $this->role_id == 2;
        //return true;
    }

    public function isNotUser() {
       // return $this->role_id != 3;
        return true;
    }

    public function city() 
    {
        return $this->belongsTo('App\Models\CityMG', 'city_slug', 'slug');
    }

    public function district() {
      return $this->belongsTo('App\Models\District', 'district_slug', 'slug');
    }

    public function commune() {
      return $this->belongsTo('App\Models\Commune', 'commune_slug', 'slug');
    }

    public function facility() {
      return $this->belongsTo('App\Models\Facility', 'facility_id', 'id');
    }

    public function displayRole() {
        $role = '';
        switch ($this->role_id) {
            case 1:
                $role = 'Admin';
                break;
            case 2:
                $role = 'Cấp Quận/Huyện';
                break;
            case 3:
                $role = 'Cộng đồng';
                break;
            case 4:
                $role = 'Cấp Tỉnh/Thành phố';
                break;
            case 5:
                $role = 'Cấp Tỉnh/Thành phố Cập nhật BN';
                break;
        }
        return $role;
    }

    public function displayStatus() {
        $status = '';
        switch ($this->status) {
            case 1:
                $status = 'Active';
                break;
            case 2:
                $status = 'Inactive';
                break;
        }
        return $status;
    }

}
