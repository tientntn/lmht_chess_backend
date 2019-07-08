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
}
