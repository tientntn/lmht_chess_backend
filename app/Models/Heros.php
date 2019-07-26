<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Moloquent;
use Cache;

class Heros extends Moloquent {
    use LanguageField;
    protected $collection = 'heroes';

    public function urlPath($size = '') {
        if ($this->image == '') {
            return config('image.image_url_admin').'/back/images/thumb_default.png';
        } else {
            if ($size) {
                return env("IMAGE_URL").'heroes/'.$this->image.'_'.$size.'.png';
            } else {
                return env("IMAGE_URL").'heroes/'.$this->image.'.png';
            }
        }
    }

    public function displayStatus() {
        return $this->status == 1 ? 'Hiển thị' : 'Ẩn';
    }

    public function cleanCache(){
        $cache_key = config('cache.key.hero').$this->slug;
        Cache::forget($cache_key);
    }

    public function checkSlug($slug, $id = null) {
        if ($id) {
            $check = Piece::where('_id', '!=', $id)->where('slug', $slug)->count();
        } else {
            $check = Piece::where('slug', $slug)->count();
        }
        if ($check > 0) {
            $slug = $slug.'-'.str_random(10);
        }
        return $slug;
    }

    public function equipments() {
      return Equipment::whereIn('_id', $this->equipment_ids)->get();
    }

    public function getArrayInfo() {
        $rels = array(
            "id"       =>      $this->_id,
            "title"     =>     $this->transa('title'),
            "slug"      =>     $this->slug,
            "content"   =>     $this->transa('content'),
            "short_content"   =>     $this->transa('short_content'),
            "category"  =>     $this->category,
            "categoryArray"  =>$this->categoryArray(),
            "status"    =>     $this->status,
            "thumb" => $this->getImages(),
        );
       
        $data_equipment = [];
        if ($this->equipment_ids) {
             $equipments = Equipment::whereIn('_id', $this->equipment_ids)->get();
            foreach ($equipments as $equipment) {
                $data_equipment[] = $equipment->getArrayInfo();
            }
        }
        $rels['equipments'] = $data_equipment;
        return $rels;
    }
    public function getImages() {
        $data = [];
        if ($this->image) {
            $data['Small'] = env("IMAGE_URL").'/heroes/'.$this->image.'_600x600.png';
            $data['Medium'] = env("IMAGE_URL").'/heroes/'.$this->image.'_600x600.png';
            $data['Large'] = env("IMAGE_URL").'/heroes/'.$this->image.'_600x600.png';
        } else {
            $data['Small'] = env("HOME_PAGE").'/images/thumb_default.png';
            $data['Medium'] = env("HOME_PAGE").'/images/thumb_default.png';
            $data['Large'] = env("HOME_PAGE").'/images/thumb_default.png';
        }
        return $data;
    }

    public function categoryArray() {
        $data = [];
       $categories = $this->category;
       if($categories) {
           foreach ($categories as $category) {
               $categoryFind = Category::find($category);
               if($categoryFind) {
                   $data[] = $categoryFind->title;
               }
           }
       }
        return $data;
    }
//    public function categories() {
//        return $this->hasMany('App\Models\Category','category','_id');
//    }

    public function languageFields($lang = 'en') {
        $data = [
            [
                'name' => 'Tên tướng',
                'key' => 'title_'.$lang,
                'type' => 'text',
                'required' => false,
                'placehoder' => ''
            ],
            [
                'name' => 'Nội dung ngắn',
                'key' => 'short_content_'.$lang,
                'type' => 'textarea',
                'required' => false,
                'placehoder' => ''
            ],
            [
                'name' => 'Nội dung',
                'key' => 'content_'.$lang,
                'type' => 'richtext',
                'required' => false,
                'placehoder' => ''
            ]
        ];
        return $data;
    }


}

