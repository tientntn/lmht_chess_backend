<?php namespace App\Models;

use Moloquent;
use Cache;
class Equipment extends Moloquent {
  use LanguageField;
  protected $collection = 'equipments';

  public function urlPath($size = '') {
    if ($this->image == '') {
        return config('image.image_url_admin').'/back/images/thumb_default.png';
    } else {
        if ($size) {
            return env("IMAGE_URL").'equipments/'.$this->image.'_'.$size.'.png';
        } else {
            return env("IMAGE_URL").'equipments/'.$this->image.'.png';
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
      $check = Equipment::where('_id', '!=', $id)->where('slug', $slug)->count();
    } else {
      $check = Equipment::where('slug', $slug)->count();
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
        "short_content" => $this->transa('short_content'),
        "content" => $this->transa('content'),
        "thumb" => $this->getImages(),
    );
    $first = Piece::find($this->piece1);
    $sercond = Piece::find($this->piece2);
    $data_piece = [];
    if ($first) {
      $data_piece[] = $first->getArrayInfo();
    }
    if ($sercond) {
      $data_piece[] = $sercond->getArrayInfo();
    }
    
    $rels['pieces'] = $data_piece;
    return $rels;
  }
  public function getArrayInfoPiece($search) {
      $rels = array(
          "id"   => $this->_id,
          "title" => $this->transa('title'),
          "slug" => $this->slug,
          "short_content" => $this->transa('short_content'),
          "content" => $this->transa('content'),
          "thumb" => $this->getImages(),
          "piece" => $this->pieceArray($search),
      );
      return $rels;
  }
  public function pieceArray($search) {
      $data = [];
      if($search && $search == $this->piece1) {
          $piece = Piece::find($this->piece2);

          $data = $piece->getArrayInfo();
      }if($search &&$search == $this->piece2) {
          $piece = Piece::find($this->piece1);
          $data = $piece->getArrayInfo();
      }
      return $data;
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
        'name' => 'Tên trang bị',
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
        'type' => 'richtexa',
        'required' => false,
        'placehoder' => ''
      ]
    ];
    return $data;
  }


}
