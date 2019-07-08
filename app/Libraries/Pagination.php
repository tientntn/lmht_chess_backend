<?php namespace App\Libraries;

use File, Image, Input;

class Pagination {

  public static function render($items, $number_link = 0)
  {
        if( $items->lastPage() <= 1 ) {
            $pagination = '';
        } else {
            
            $paged = Input::has('page') ? 0+Input::get('page') : 1;
            $max   = intval( $items->lastPage() );
            if ($paged <1) {
                $paged = 1;
            }
            if ($paged > $max) {
                $paged = $max;
            }

            /** Add current page to the array */
            if ( $paged >= 1 )
                $links[] = $paged;

            /** Add the pages around the current page to the array */
            if ( $paged >= (1+$number_link) ) {
                for ($i = 1; $i<1+$number_link ; $i++) {
                    $links[] = $paged - $i;
                }
            }

            if ( ( $paged + 0+$number_link ) <= $max ) {
                for ($i = 0+$number_link; $i>0 ; $i--) {
                    $links[] = $paged + $i;
                }
            }
            if ($paged <= 5 && $max > 5) {
                for ($i = 2; $i <=5; $i++) {
                    if (!in_array( $i, $links )) {
                        $links[] = $i;
                    }
                }
            }

            if ($paged <= $max && $paged > $max-4 && $paged >5) {
                for ($i = $max-4; $i <$max; $i++) {
                    if (!in_array( $i, $links )) {
                        $links[] = $i;
                    }
                }
            }

            $pagination = '<ul class="nav-page">';

            /** Previous Post Link */

            $preview = $paged-1;
            if ($preview>=1) {
                $preview = $preview <= 1 ? 1 : $preview;
                $pagination = $pagination.'<li><a class="prev" href="'.$items->url($preview).'">Trang trước</a></li>';
            }

            /** Link to first page, plus ellipses if necessary */
            if ( ! in_array( 1, $links ) ) {
                $class = 1 == $paged ? ' class="cur"' : '';

                $pagination = $pagination.'<li><a '.$class.' href="'.$items->url(1).'">1</a></li>';            

                if ( ! in_array( 2, $links ) ) {
                    $pagination = $pagination.'<li><a>...</a></li>';
                }
            }

            /** Link to current page, plus 2 pages in either direction if necessary */
            sort( $links );
            foreach ( (array) $links as $link ) {
                $class = $paged == $link ? ' class="cur"' : '';
                $pagination = $pagination.'<li><a '.$class.' href="'.$items->url($link).'">'.$link.'</a></li>';
            }

            /** Link to last page, plus ellipses if necessary */
            
            if ( ! in_array( $max, $links ) ) {
                if ( ! in_array( $max - 1, $links ) ) {
                    $pagination = $pagination.'<li><a>...</a></li>';
                }

                $class = $paged == $max ? ' class="cur"' : '';
                $pagination = $pagination.'<li><a '.$class.' href="'.$items->url($max).'">'.$max.'</a></li>';
            }

            /** Next Post Link */
            if ( $paged < $items->lastPage() ) {
                $next = $paged+1;
                $next = $next >= $max ? $max : $next;
                $pagination = $pagination.'<li><a class="next" href="'.$items->url($next).'">Trang sau</a></li>';
            }

            $pagination = $pagination.'</ul>';            
        }       

        return $pagination;
  }

  public static function renderArray($url, $per_page = 20, $number_link = 0, $params = array())
  {
    if (!empty($params)) {
      $url = $url.http_build_query($params);
    }
    $total = 201;
    $last_page = ceil($total/$per_page);
        if( $last_page <= 1 ) {
            $pagination = '';
        } else {
            
            $paged = Input::has('page') ? 0+Input::get('page') : 1;
            $max   = $last_page;
            if ($paged <1) {
                $paged = 1;
            }
            if ($paged > $max) {
                $paged = $max;
            }

            /** Add current page to the array */
            if ( $paged >= 1 )
                $links[] = $paged;

            /** Add the pages around the current page to the array */
            if ( $paged >= (1+$number_link) ) {
                for ($i = 1; $i<1+$number_link ; $i++) {
                    $links[] = $paged - $i;
                }
            }

            if ( ( $paged + 0+$number_link ) <= $max ) {
                for ($i = 0+$number_link; $i>0 ; $i--) {
                    $links[] = $paged + $i;
                }
            }
            if ($paged <= 5 && $max > 5) {
                for ($i = 2; $i <=5; $i++) {
                    if (!in_array( $i, $links )) {
                        $links[] = $i;
                    }
                }
            }

            if ($paged <= $max && $paged > $max-4 && $paged >5) {
                for ($i = $max-4; $i <$max; $i++) {
                    if (!in_array( $i, $links )) {
                        $links[] = $i;
                    }
                }
            }

            $pagination = '<ul class="nav-page">';

            /** Previous Post Link */

            $preview = $paged-1;
            if ($preview>=1) {
                $preview = $preview <= 1 ? 1 : $preview;
                $pagination = $pagination.'<li><a class="prev" href="'.$url.$preview.'">Trang trước</a></li>';
            }

            /** Link to first page, plus ellipses if necessary */
            if ( ! in_array( 1, $links ) ) {
                $class = 1 == $paged ? ' class="cur"' : '';

                $pagination = $pagination.'<li><a '.$class.' href="'.$url.'1">1</a></li>';            

                if ( ! in_array( 2, $links ) ) {
                    $pagination = $pagination.'<li><a>...</a></li>';
                }
            }

            /** Link to current page, plus 2 pages in either direction if necessary */
            sort( $links );
            foreach ( (array) $links as $link ) {
                $class = $paged == $link ? ' class="cur"' : '';
                $pagination = $pagination.'<li><a '.$class.' href="'.$url.$link.'">'.$link.'</a></li>';
            }

            /** Link to last page, plus ellipses if necessary */
            
            if ( ! in_array( $max, $links ) ) {
                if ( ! in_array( $max - 1, $links ) ) {
                    $pagination = $pagination.'<li><a>...</a></li>';
                }

                $class = $paged == $max ? ' class="cur"' : '';
                $pagination = $pagination.'<li><a '.$class.' href="'.$url.$max.'">'.$max.'</a></li>';
            }

            /** Next Post Link */
            if ( $paged < $max ) {
                $next = $paged+1;
                $next = $next >= $max ? $max : $next;
                $pagination = $pagination.'<li><a class="next" href="'.$url.$next.'">Trang sau</a></li>';
            }

            $pagination = $pagination.'</ul>';            
        }       

        return $pagination;
  }

 

}