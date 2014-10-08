<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
    
function decode_json_tags( $str ){
    $ret = '';
    $data = json_decode($str);
    $n = 0;
    foreach($data as $d){
        if($n > 0) $ret .= ', ';
        $ret .= '<label class="param_tag" var="'. $d->id .'">'. $d->tag .'</label>';
        $n++;
    }
    return $ret;
}

function tags_name( $str ){
    $data = json_decode($str);
    $html = '<div>';
    foreach($data as $d){
        $html .= '<input type="checkbox" class="kotak_tag" var="'. $d->tag_name .'"> '. $d->tag_name .'<br/>';
    }
    $html .= '</div>';
    return $html;
}
?>