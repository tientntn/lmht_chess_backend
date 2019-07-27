<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Moloquent;
use Cache, File, Auth, Input, Session;

trait LanguageField
{
    public function trans($field) {
        $lang = Session::get('locale');
        $key = $lang == 'vi' ? $field : $field.'_'.$lang;
        return $this->$key ? $this->$key : $this->$field;
    }
    public function transa($field) {
        $lang = Input::get('lang') == 'vi' ? 'vi' : 'en';
        $key = $lang == 'vi' ? $field : $field.'_'.$lang;
        return $this->$key ? $this->$key : $this->$field;
    }
}
