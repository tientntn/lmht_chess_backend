<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Moloquent;
use Cache;

class News extends Moloquent {
    use LanguageField;
    protected $collection = 'news';

    public function urlPath($size = '') {
        if ($this->image == '') {
            return config('image.image_url_admin').'/back/images/thumb_default.png';
        } else {
            if ($size) {
                return env("IMAGE_URL").'news/'.$this->image.'_'.$size.'.png';
            } else {
                return env("IMAGE_URL").'news/'.$this->image.'.png';
            }
        }
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
            $check = News::where('_id', '!=', $id)->where('slug', $slug)->count();
        } else {
            $check = News::where('slug', $slug)->count();
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
            "short_content" => $this->short_content,
            "content" =>  $this->content,
            "description" =>  $this->description,
            "thumb" => $this->getImages(),
            "created_at" => date('Y-m-d', strtotime($this->created_at))
        );
        return $rels;
    }

    public function getImages() {
        $data = [];
        if ($this->image) {
            $data['Small'] = env("IMAGE_URL").'/news/'.$this->image.'_100x100.png';
            $data['Medium'] = env("IMAGE_URL").'/news/'.$this->image.'_100x100.png';
            $data['Large'] = env("IMAGE_URL").'/news/'.$this->image.'_600x600.png';
        } else {
            $data['Small'] = env("HOME_PAGE").'/images/thumb_default.png';
            $data['Medium'] = env("HOME_PAGE").'/images/thumb_default.png';
            $data['Large'] = env("HOME_PAGE").'/images/thumb_default.png';
        }
        return $data;
    }

    public function languageFields($lang = 'en') {
        // $data = [
        //     [
        //         'name' => 'Tiêu đề',
        //         'key' => 'title_'.$lang,
        //         'type' => 'text',
        //         'required' => false,
        //         'placehoder' => ''
        //     ],
        //     [
        //         'name' => 'Nội dung',
        //         'key' => 'content_'.$lang,
        //         'type' => 'textarea',
        //         'required' => false,
        //         'placehoder' => ''
        //     ]
        // ];
        // return $data;
    }


}

