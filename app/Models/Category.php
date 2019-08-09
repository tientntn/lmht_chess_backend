<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Moloquent;
use Cache;

class Category extends Moloquent {
    use LanguageField;
    protected $collection = 'category';

    public function urlPath($size = '', $field) {
        // if ($this->$field == '') {
        //     return config('image.image_url_admin').'/back/images/thumb_default.png';
        // } else {
        //     if ($size) {
        //         return env("IMAGE_URL").'categories/'.$this->$field.'_'.$size.'.png';
        //     } else {
                return env("IMAGE_URL").'categories/'.$this->$field.'.png';
        //     }
        // }
    }

    public function displayStatus() {
        return $this->status == 1 ? 'Hiển thị' : 'Ẩn';
    }

    public function cleanCache(){
        $cache_key = config('cache.key.equipment').$this->slug;
        Cache::forget($cache_key);
    }

    public function checkSlug($slug, $id = null) {
        if ($id) {
            $check = Category::where('_id', '!=', $id)->where('slug', $slug)->count();
        } else {
            $check = Category::where('slug', $slug)->count();
        }
        if ($check > 0) {
            $slug = $slug.'-'.str_random(10);
        }
        return $slug;
    }

    public function getArrayInfo() {
        $rels = array(
            "id"   => $this->_id,
            "title" => $this->transa('title'),
            "slug" => $this->slug,
            "short_content" => $this->transa('content'),
            "content" =>  $this->transa('content'),
            "description" =>  $this->transa('content'),
            "thumb" => $this->getImages(),
        );
        return $rels;
    }

    public function getArrayInfoFull() {
        $rels = array(
            "id"   => $this->_id,
            "title" => $this->title,
            "title_en" => $this->title_en,
            "slug" => $this->slug,
            "short_content" => $this->transa('content'),
            "content" =>  $this->transa('content'),
            "description" =>  $this->transa('content'),
            "thumb" => $this->getImages(),
        );
        return $rels;
    }

    public function getImages() {
        $data = [];
        if ($this->image) {
            $data['Small'] = env("IMAGE_URL").'/equipments/'.$this->image.'_100x100.png';
            $data['Medium'] = env("IMAGE_URL").'/equipments/'.$this->image.'_100x100.png';
            $data['Large'] = env("IMAGE_URL").'/equipments/'.$this->image.'_600x600.png';
        } else {
            $data['Small'] = env("HOME_PAGE").'/images/thumb_default.png';
            $data['Medium'] = env("HOME_PAGE").'/images/thumb_default.png';
            $data['Large'] = env("HOME_PAGE").'/images/thumb_default.png';
        }
        return $data;
    }

    public function languageFields($lang = 'en') {
        $data = [
            [
                'name' => 'Tiêu đề',
                'key' => 'title_'.$lang,
                'type' => 'text',
                'required' => false,
                'placehoder' => ''
            ],
            [
                'name' => 'Nội dung',
                'key' => 'content_'.$lang,
                'type' => 'textarea',
                'required' => false,
                'placehoder' => ''
            ],
             [
                'name' => 'Kích hoạt',
                'key' => 'power_'.$lang,
                'type' => 'textarea',
                'required' => false,
                'placehoder' => ''
            ]
        ];
        return $data;
    }


}

