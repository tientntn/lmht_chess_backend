<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Moloquent;
use Cache;

class Combo extends Moloquent {
    use LanguageField;
    protected $collection = 'combo';

    public function urlPath($size = '') {
        if ($this->image == '') {
            return config('image.image_url_admin').'/back/images/thumb_default.png';
        } else {
            if ($size) {
                return env("IMAGE_URL").'combos/'.$this->image.'_'.$size.'.png';
            } else {
                return env("IMAGE_URL").'combos/'.$this->image.'.png';
            }
        }
    }

    public function displayStatus() {
        return $this->status == 1 ? 'Hiển thị' : 'Ẩn';
    }

    public function cleanCache(){
        $cache_key = config('cache.key.combo').$this->slug;
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
            "link"  => $this->link,
            "thumb" => $this->getImages(),
            "content" => ''.$this->transa('content'),
        );
        return $rels;
    }

    public function getImages() {
        $data = [];
        if ($this->image) {
            $data['Small'] = env("IMAGE_URL").'/combos/'.$this->image.'_100x100.png';
            $data['Medium'] = env("IMAGE_URL").'/combos/'.$this->image.'_100x100.png';
            $data['Large'] = env("IMAGE_URL").'/combos/'.$this->image.'_600x600.png';
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
                'name' => 'Tên',
                'key' => 'title_'.$lang,
                'type' => 'text',
                'required' => false,
                'placehoder' => ''
            ],
            [
                'name' => 'Noi dung',
                'key' => 'content_'.$lang,
                'type' => 'richtext',
                'required' => false,
                'placehoder' => ''
            ],
        ];
        return $data;
    }


}

