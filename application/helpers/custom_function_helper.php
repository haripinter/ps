<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
    
function decode_json_tags( $str ){
    $ret = '';
    $data = json_decode($str);
    $n = 0;
    foreach($data as $d){
        if($n > 0) $ret .= ', ';
        $ret .= '<label class="param_tag" var="'. $d->id .'" style="font-weight:normal;">'. $d->tag .'</label>';
        $n++;
    }
    return $ret;
}

function tags_name( $str ){
    $data = json_decode($str);
    $html = '<div>';
    foreach($data as $d){
        $html .= '<input type="checkbox" class="kotak_tag" var="'. $d->id .'"> '. $d->tag_name .'<br/>';
    }
    $html .= '</div>';
    return $html;
}

function select_sender( $name, $clas, $str, $sel=null){
    $data = json_decode($str);
    $html = '<select name="'. $name .'" class="'. $clas .'">';
    $html .= '<option value="0">- Change -</option>';
    foreach($data as $d){
        $v = '';
        if($sel==$d->id) $v = 'selected';
        $html .= '<option value="'. $d->id .'" '. $v .'>'. $d->email .'</option>';
    }
    $html .= '</select>';
    return $html;
}

function status_button($id, $status){
    $html = '';
    if($status == 0){
        $html = '<button class="mission-status mission-status'. $id .' btn btn-sm btn-warning" var="'. $id .'" stat="'. $status .'">off</button>';
    }else{
        $html = '<button class="mission-status mission-status'. $id .' btn btn-sm btn-success" var="'. $id .'" stat="'. $status .'">on</button>';
    }
    return $html;
}


?>