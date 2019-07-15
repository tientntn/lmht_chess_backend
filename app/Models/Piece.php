<?php

namespace App\Models;

use Moloquent;
use Cache;

class Piece extends Moloquent {
    use LanguageField;
    protected $collection = 'pieces';

    public function urlPath($size = '') {
        if ($this->image == '') {
            return config('image.image_url_admin').'/back/images/thumb_default.png';
        } else {
            if ($size) {
                return env("IMAGE_URL").'pieces/'.$this->image.'_'.$size.'.png';
            } else {
                return env("IMAGE_URL").'pieces/'.$this->image.'.png';
            }
        }
    }

    public function displayStatus() {
        return $this->status == 1 ? 'Hiển thị' : 'Ẩn';
    }

    public function cleanCache(){
        $cache_key = config('cache.key.piece').$this->slug;
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
    public function getArrayInfo() {
        $rels = array(
            "id"       =>      $this->_id,
            "title"     =>     $this->title,
            "slug"      =>     $this->slug,
            "content"   =>     $this->content,
            "status"    =>     $this->status,
            "thumb" => $this->getImages(),
            "image_equipment" => $this->getImagesList(),
        );
        return $rels;
    }
    public function getImages() {
        $data = [];
        if ($this->image) {
            $data['Small'] = env("IMAGE_URL").'/pieces/'.$this->image.'_100x100.png';
            $data['Medium'] = env("IMAGE_URL").'/pieces/'.$this->image.'_100x100.png';
            $data['Large'] = env("IMAGE_URL").'/pieces/'.$this->image.'_600x600.png';
        } else {
            $data['Small'] = env("HOME_PAGE").'/images/thumb_default.png';
            $data['Medium'] = env("HOME_PAGE").'/images/thumb_default.png';
            $data['Large'] = env("HOME_PAGE").'/images/thumb_default.png';
        }
        return $data;
    }

    public function getImagesList() {
        $data = [];
        if ($this->image_list) {
            $data['Small'] = env("IMAGE_URL").'/piecesList/'.$this->image.'_100x100.png';
            $data['Medium'] = env("IMAGE_URL").'/piecesList/'.$this->image.'_100x100.png';
            $data['Large'] = env("IMAGE_URL").'/piecesList/'.$this->image.'_600x600.png';
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
                'name' => 'Đường dẫn hiển thị trên url',
                'key' => 'slug_'.$lang,
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
            ]
        ];
        return $data;
    }


}
