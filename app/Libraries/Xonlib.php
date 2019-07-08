<?php namespace App\Libraries;

class Xonlib {
    
  
  public static function auto_summary($str, $type, $return = false) {
    if(trim($str)=='') return '';
    $length = Str::length($str);
    $classTxt = 'summary-0';

    if($length < 25) {
        $classTxt = 'summary-0';
    } else if($length >= 25 && $length < 50) {
        $classTxt = 'summary-25';
    } else if($length >= 50 && $length < 75) {
        $classTxt = 'summary-50';
    } else if($length >= 75 && $length < 100) {
        $classTxt = 'summary-75';
    } else if($length >= 100 && $length < 125) {
        $classTxt = 'summary-100';
    } else if($length >= 125 && $length < 150) {
        $classTxt = 'summary-125';
    } else {
        $classTxt = 'summary-150';
    }

    if($return)
      return $classTxt;
    else
      echo $classTxt;
  }

  public static function timeago($input_date, $timeago = true){
    if(!$timeago) {
      if($input_date) {
        $zone=3600*7;
        $out_date=gmdate("d/m/Y - h:m:s", $input_date + $zone);
        return $out_date;
      } else {
        return null;
      }
    } else {
      $input_date = strtotime($input_date);
      $etime = time() - $input_date;

      if ($etime < 1) {
        return '1 vài giây trước';
      }

      $a = array( 12 * 30 * 24 * 60 * 60 => 'năm',
          30 * 24 * 60 * 60              => 'tháng',
          24 * 60 * 60                   => 'ngày',
          60 * 60                        => 'giờ',
          60                             => 'phút',
          1                              => 'giây'
      );

      foreach ($a as $secs => $str) {
        $d = $etime / $secs;
        if ($d >= 1) {
          $r = round($d);
          return $r . ' '.$str . ($r > 1 ? '' : '') . ' trước';
        }
      }
    }
  }

  public static function get_avatar($name_name, $size = 100, $width = 100) {
    if($name_name) {
      $avatar = '<img class="profile-img" src="'.url('uploads/avatar/'.$name_name.'_'.$size.'.jpg').'" width="'.$width.'" />';
    } else {
      $avatar = '<img class="profile-img" src="'.asset('img/taymay/avatar.jpg').'" width="'.$width.'" />';
    }
    return $avatar;
  }

  public static function denied_name() {
    return 'notifications,admin,site,auth,webmaster,profile,home,message,news,blog,user,users,help,page,pages,base,notice,oauth,guide,topic,tag,image,images,dash,dashboard,password,login,register,activated,logout,forum,homepage';
  }

  public static function vi2en($str)
  {
    $arrTViet=array("à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă",
    "ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề"
    ,"ế","ệ","ể","ễ",
    "ì","í","ị","ỉ","ĩ",
    "ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ"
    ,"ờ","ớ","ợ","ở","ỡ",
    "ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ",
    "ỳ","ý","ỵ","ỷ","ỹ",
    "đ",
    "À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă"
    ,"Ằ","Ắ","Ặ","Ẳ","Ẵ",
    "È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ",
    "Ì","Í","Ị","Ỉ","Ĩ",
    "Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ"
    ,"Ờ","Ớ","Ợ","Ở","Ỡ",
    "Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ",
    "Ỳ","Ý","Ỵ","Ỷ","Ỹ",
    "Đ");

    $arrKoDau=array("a","a","a","a","a","a","a","a","a","a","a"
    ,"a","a","a","a","a","a",
    "e","e","e","e","e","e","e","e","e","e","e",
    "i","i","i","i","i",
    "o","o","o","o","o","o","o","o","o","o","o","o"
    ,"o","o","o","o","o",
    "u","u","u","u","u","u","u","u","u","u","u",
    "y","y","y","y","y",
    "d",
    "A","A","A","A","A","A","A","A","A","A","A","A"
    ,"A","A","A","A","A",
    "E","E","E","E","E","E","E","E","E","E","E",
    "I","I","I","I","I",
    "O","O","O","O","O","O","O","O","O","O","O","O"
    ,"O","O","O","O","O",
    "U","U","U","U","U","U","U","U","U","U","U",
    "Y","Y","Y","Y","Y",
    "D");

    return str_replace($arrTViet, $arrKoDau, $str);
  }

  public static function replace_accents($string){ 
    return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string); 
  }


  public static function seems_utf8($str) {
    $length = strlen($str);
    for ($i=0; $i < $length; $i++) {
      $c = ord($str[$i]);
      if ($c < 0x80) $n = 0; # 0bbbbbbb
      elseif (($c & 0xE0) == 0xC0) $n=1; # 110bbbbb
      elseif (($c & 0xF0) == 0xE0) $n=2; # 1110bbbb
      elseif (($c & 0xF8) == 0xF0) $n=3; # 11110bbb
      elseif (($c & 0xFC) == 0xF8) $n=4; # 111110bb
      elseif (($c & 0xFE) == 0xFC) $n=5; # 1111110b
      else return false; # Does not match any model
      for ($j=0; $j<$n; $j++) { # n bytes matching 10bbbbbb follow ?
      if ((++$i == $length) || ((ord($str[$i]) & 0xC0) != 0x80))
        return false;
      }
    }
    return true;
  }
  
  /**
   * Encode the Unicode values to be used in the URI.
   *
   * @since 1.5.0
   *
   * @param string $utf8_string
   * @param int $length Max length of the string
   * @return string String with Unicode encoded for URI.
   */
  public static function utf8_uri_encode( $utf8_string, $length = 0 ) {
    $unicode = '';
    $values = array();
    $num_octets = 1;
    $unicode_length = 0;

    $string_length = strlen( $utf8_string );
    for ($i = 0; $i < $string_length; $i++ ) {
      $value = ord( $utf8_string[ $i ] );

      if ( $value < 128 ) {
        if ( $length && ( $unicode_length >= $length ) )
          break;
        $unicode .= chr($value);
        $unicode_length++;
      } else {
        if ( count( $values ) == 0 ) $num_octets = ( $value < 224 ) ? 2 : 3;

        $values[] = $value;

        if ( $length && ( $unicode_length + ($num_octets * 3) ) > $length )
          break;
        if ( count( $values ) == $num_octets ) {
          if ($num_octets == 3) {
            $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]) . '%' . dechex($values[2]);
            $unicode_length += 9;
          } else {
            $unicode .= '%' . dechex($values[0]) . '%' . dechex($values[1]);
            $unicode_length += 6;
          }
          $values = array();
          $num_octets = 1;
        }
      }
    }
    return $unicode;
  }

  public static function pretty_url($title, $spe = true) {
    $title = Xonlib::vi2en($title);

    $title = strip_tags($title);
    // Preserve escaped octets.
    $title = preg_replace('|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $title);
    // Remove percent signs that are not part of an octet.
    $title = str_replace('%', '', $title);
    // Restore octets.
    $title = preg_replace('|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $title);

    //$title = Xonlib::remove_accents($title);
    if (Xonlib::seems_utf8($title)) {
      if (function_exists('mb_strtolower')) {
        $title = mb_strtolower($title, 'UTF-8');
      }
      $title = Xonlib::utf8_uri_encode($title, 200);
    }

    $title = strtolower($title);
    $title = preg_replace('/&.+?;/', '', $title); // kill entities
    $title = str_replace('.', '-', $title);
    $title = preg_replace('/[^%a-z0-9 _-]/', '', $title);
    $title = preg_replace('/\s+/', '-', $title);
    $title = preg_replace('|-+|', '-', $title);
    $title = trim($title, '-');

    $title = Xonlib::vi2en($title);
    return $title;
  }

  //Deprecated function
  public static function pretty_url_old($text, $spe = true) { 
    //  Do you know your ABCs?
    $characterHash = array (
      'a' =>  array ('a', 'A', 'à', 'À', 'á', 'Á', 'â', 'Â', 'ã', 'Ã', 'ä', 'Ä', 'å', 'Å', 'ª', 'ą', 'Ą', 'а', 'А', 'ạ', 'Ạ', 'ả', 'Ả', 'Ầ', 'ầ', 'Ấ', 'ấ', 'Ậ', 'ậ', 'Ẩ', 'ẩ', 'Ẫ', 'ẫ', 'Ă', 'ă', 'Ắ', 'ắ', 'Ẵ', 'ẵ', 'Ặ', 'ặ', 'Ằ', 'ằ', 'Ẳ', 'ẳ'),
      'ae'  =>  array ('æ', 'Æ'),
      'b' =>  array ('b', 'B'),
      'c' =>  array ('c', 'C', 'ç', 'Ç', 'ć', 'Ć', 'č', 'Č'),
      'd' =>  array ('d', 'D', 'Ð', 'đ', 'Đ', 'ď', 'Ď'),
      'e' =>  array ('e', 'E', 'è', 'È', 'é', 'É', 'ê', 'Ê', 'ë', 'Ë', 'ę', 'Ę', 'е', 'Е', 'ё', 'Ё', 'э', 'Э', 'Ẹ', 'ẹ', 'Ẻ', 'ẻ', 'Ẽ', 'ẽ', 'Ề', 'ề', 'Ế', 'ế', 'Ệ', 'ệ', 'Ể', 'ể', 'Ễ', 'ễ', 'ε', 'Ε', 'ě', 'Ě'),
      'f' =>  array ('f', 'F'),
      'g' =>  array ('g', 'G', 'ğ', 'Ğ'),
      'h' =>  array ('h', 'H'),
      'i' =>  array ('i', 'I', 'ì', 'Ì', 'í', 'Í', 'î', 'Î', 'ï', 'Ï', 'ı', 'İ', 'Ị', 'ị', 'Ỉ', 'ỉ', 'Ĩ', 'ĩ', 'Ι', 'ι'),
      'j' =>  array ('j', 'J'),
      'k' =>  array ('k', 'K', 'к', 'К', 'κ', 'Κ'),
      'l' =>  array ('l', 'L', 'ł', 'Ł'),
      'm' =>  array ('m', 'M', 'м', 'М', 'Μ'),
      'n' =>  array ('n', 'N', 'ñ', 'Ñ', 'ń', 'Ń', 'ň', 'Ň'),
      'o' =>  array ('o', 'O', 'ò', 'Ò', 'ó', 'Ó', 'ô', 'Ô', 'õ', 'Õ', 'ö', 'Ö', 'ø', 'Ø', 'º', 'о', 'О', 'Ọ', 'ọ', 'Ỏ', 'ỏ', 'Ộ', 'ộ', 'Ố', 'ố', 'Ỗ', 'ỗ', 'Ồ', 'ồ', 'Ổ', 'ổ', 'Ơ', 'ơ', 'Ờ', 'ờ', 'Ớ', 'ớ', 'Ợ', 'ợ', 'Ở', 'ở', 'Ỡ', 'ỡ', 'ο', 'Ο'),
      'p' =>  array ('p', 'P'),
      'q' =>  array ('q', 'Q'),
      'r' =>  array ('r', 'R', 'ř', 'Ř'),
      's' =>  array ('s', 'S', 'ş', 'Ş', 'ś', 'Ś', 'š', 'Š'),
      'ss'  =>  array ('ß'),
      't' =>  array ('t', 'T', 'т', 'Т', 'τ', 'Τ', 'ţ', 'Ţ', 'ť', 'Ť'),
      'u' =>  array ('u', 'U', 'ù', 'Ù', 'ú', 'Ú', 'û', 'Û', 'ü', 'Ü', 'Ụ', 'ụ', 'Ủ', 'ủ', 'Ũ', 'ũ', 'Ư', 'ư', 'Ừ', 'ừ', 'Ứ', 'ứ', 'Ự', 'ự', 'Ử', 'ử', 'Ữ', 'ữ', 'ů', 'Ů'),
      'v' =>  array ('v', 'V'),
      'w' =>  array ('w', 'W'),
      'x' =>  array ('x', 'X', '×'),
      'y' =>  array ('y', 'Y', 'ý', 'Ý', 'ÿ', 'Ỳ', 'ỳ', 'Ỵ', 'ỵ', 'Ỷ', 'ỷ', 'Ỹ', 'ỹ'),
      'z' =>  array ('z', 'Z', 'ż', 'Ż', 'ź', 'Ź', 'ž', 'Ž', 'Ζ'),
      '-' =>  array ('_', '.', ',', '(', '{', '[',')','}',']','$', ' '),
      '!' =>  array ('!'),
      '~' =>  array ('~'),
      '*' =>  array ('*'),
      ""  =>  array ("'", '"'),
      '0' =>  array ('0'),
      '1' =>  array ('1', '¹'),
      '2' =>  array ('2', '²'),
      '3' =>  array ('3', '³'),
      '4' =>  array ('4'),
      '5' =>  array ('5'),
      '6' =>  array ('6'),
      '7' =>  array ('7'),
      '8' =>  array ('8'),
      '9' =>  array ('9'),
    );

    //  Get or detect the database encoding, firstly from the settings or language files
    if (preg_match('~.~su', $text))
      $encoding = 'UTF-8';
    //  or sadly... we may have to assume Latin-1
    else
      $encoding = 'ISO-8859-1';

    //  If the database encoding isn't UTF-8 and multibyte string functions are available, try converting the text to UTF-8
    if ($encoding != 'UTF-8' && function_exists('mb_convert_encoding'))
      $text = mb_convert_encoding($text, 'UTF-8', $encoding);
    //  Or maybe we can convert with iconv
    else if ($encoding != 'UTF-8' && function_exists('iconv'))
      $text = iconv($encoding, 'UTF-8', $text);
    //  Fix Turkish
    else if ($encoding == 'ISO-8859-9')
    {
      $text = str_replace(array("\xD0", "\xDD", "\xDE", "\xF0", "\xFD", "\xFE"), array('g', 'i', 's', 'g', 'i', 's'), $text);
      $text = utf8_encode($text);
    }
    //  Latin-1 can be converted easily
    else if ($encoding == 'ISO-8859-1')
      $text = utf8_encode($text);

    //  Change the entities back to normal characters
    $text = str_replace(array('&amp;', '&quot;', '-'), array('&', '"', ' '), $text);
    $text = str_replace(array("'"), array('&', '"'), $text);
    $prettytext = '';

    //  Split up $text into UTF-8 letters
    preg_match_all("~.~su", $text, $characters);
    foreach ($characters[0] as $aLetter)
    {
      foreach ($characterHash as $replace => $search)
      {
        //  Found a character? Replace it!
        if (in_array($aLetter, $search))
        {
          $prettytext .= $replace;
          break;
        }
      }
    }
      if($spe) {
          // Remove unwanted '-'s    
          $prettytext = preg_replace(array('~^-+|-+$~', '~-+~'), array(' ', '-'), $prettytext);
      } else {
          $prettytext = preg_replace(array('~^-+|-+$~', '~-+~'), array(' ', ' '), $prettytext);
      }
    return $prettytext;
  }

  public static function the_content_limit($max_char,$content) {
    $content = strip_tags($content);
    if ((strlen($content)>$max_char) && ($espacio = strpos($content, " ", $max_char ))){
      $content = substr($content, 0, $espacio);
      $content = $content;
      return $content;
    }else {
      return $content;
    }
  }

  public static function create_seo($title = '', $description = '', $image = '', $keyword = '', $created_at = '', $updated_at = '')
  {
    $seo =  array(
      'title' => $title,
      'description' => $description,
      'image' => $image,
      'keyword' => $keyword,
      'created_at' => $created_at,
      'updated_at' => $updated_at
    );
    return $seo;
  }

  public static function convert_objects_mongo_to_array($objects, $field = '_id')
  {
    if (empty($objects)) {
      return null;
    } else {
      $array = array();
      foreach ($objects as $object) {        
          $array[count($array)] = ''.$object[$field];
      }
      return $array;
    }
  }

  public static function get_image_default( $width, $heigh, $text = '', $textsize = '14' ) {
    return 'http://placeholdit.imgix.net/~text?txtsize='.$textsize.'&txt='.$text.'&w='.$width.'&h='.$heigh;
  }
}

?>