<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mcontact extends CI_Model{
    
    /*
     * -- tags
     */
    
    function get_tags( $id=null,$order=null )
    {
        $data = array();
        $this->db->select('id,tag_name,`status`');
        $this->db->from('contact_tags_cat');
        if(is_numeric($id) && $id>0){
            $this->db->where('id',$id);
        }
        if($order != null){
            $this->db->order_by($order);
        }
        $data = $this->db->get();
        return ($this->db->affected_rows() > 0)? $data : FALSE;
    }
    
    function json_tags_name(){
        $ret = array();
        $tagname = $this->get_tags();
        foreach($tagname->result() as $tag){
            $tmp = array();
            $tmp['id'] = $tag->id;
            $tmp['tag_name'] = $tag->tag_name;
            array_push($ret,$tmp);
        }
        return json_encode($ret);
    }
    
    function remove_tag( $id=null )
    {
        if(is_numeric($id) && $id>0){
            $this->db->where('id',$id);
            $this->db->delete('contact_tags_cat');
            return ($this->db->affected_rows() > 0)? TRUE : FALSE;
        }
        return FALSE;
    }
    
    function insert_tag( $data=array() )
    {
        $this->db->insert('contact_tags_cat',$data);
        return ($this->db->affected_rows() > 0)? TRUE : FALSE;
    }
    
    function update_tag( $data=array() )
    {
        $this->db->where('id',$data['id']);
        $this->db->update('contact_tags_cat',$data);
        return ($this->db->affected_rows() >= 0)? TRUE : FALSE;
    }
    
    function filter_input()
    {
        $result['data'] = 0;
        $result['success'] = 0;
        $result['error'] = 0;
        $result['error_data'] = array();
        $result['success_data'] = array();
        $tmp  = $this->input->post('itag_name');
        $idc  = $this->input->post('idc');
        
        if(is_array($idc) && count($idc) > 0){
            for($n=0; $n<count($tmp); $n++){
                if($tmp[$n]=='') continue;
                $data = array();
                $data['tag_name'] = $tmp[$n];
                $data['status'] = 1;
                $data['order'] = 1;
                
                $dota = array();
                $dota['tag_name'] = $data['tag_name'];
                
                $r = FALSE;
                if(is_numeric($idc[$n]) && $idc[$n] > 0){
                    $data['id'] = $idc[$n];
                    $r = $this->update_tag($data);
                    $dota['id'] = $idc[$n];
                }else{
                    $r = $this->insert_tag($data);
                    $dota['id'] = $this->db->insert_id();
                }
                
                if(!$r){
                    $result['error']++;
                    array_push($result['error_data'],$dota);
                }else{
                    $result['success']++;
                    array_push($result['success_data'],$dota);
                }
                $result['data']++;
            }
            return $result;
        }
        return FALSE;
    }
    
    
    /*
     *  -- contat --
     */
     
    function filter_post_contact()
    {
        $result['data'] = 0;
        $result['success'] = 0;
        $result['error'] = 0;
        $result['error_data'] = array();
        $result['success_data'] = array();
        $idc  = $this->input->post('idc');
        $inm  = $this->input->post('iname');
        $iml  = $this->input->post('iemail');
        $itg  = $this->input->post('itag'); // tag must be jsonstring
        
        if(is_array($iml) && count($iml) >0){
            for($n=0; $n<count($iml); $n++){
                if($iml[$n]=='') continue;
                $data = array();
                $data['name'] = $inm[$n];
                $data['email'] = strtolower($iml[$n]);
                $data['status'] = 1;
                $data['order'] = 1;
                
                // for result
                $dota = array();
                $dota['name'] = $data['name'];
                $dota['email'] = $data['email'];
                
                // for insert contact tag
                $doto = array();
                
                $r = FALSE;
                if(is_numeric($idc[$n]) && $idc[$n] > 0){
                    $data['id'] = $idc[$n];
                    $r = $this->update_contact($data);
                    $dota['id'] = $idc[$n];
                }else{
                    $r = $this->insert_contact($data);
                    $dota['id'] = $this->db->insert_id();
                }
                
                // manage contact tag
                $doto['contact_id'] = $dota['id'];
                $doto['contact_tag_id'] = $itg[$n]; // json string
                $doto['status'] = 1;
                
                // result
                if($doto['contact_tag_id']!=''){ 
                    $dota['tags'] = $this->manage_contact_tag($doto);
                }else{
                    $dota['tags'] = '[]';
                }
                
                if(!$r){
                    $result['error']++;
                    array_push($result['error_data'],$dota);
                }else{
                    $result['success']++;
                    array_push($result['success_data'],$dota);
                }
                $result['data']++;
            }
            return $result;
        }
        return FALSE;
    }
    
    function is_dobel( $email )  // string email
    {
        $this->db->select('id')->from('contact')->where('email',$email);
        $data = $this->db->get();
        return ($this->db->affected_rows() > 0)? TRUE : FALSE;
    }
    
    function manage_contact_tag( $data )
    {   
        $result = array();
        
        $tagid = json_decode($data['contact_tag_id']);
        $tmp   = array();
        $tmp['contact_id'] = $data['contact_id'];
        $tmp['status'] = $data['status'];
        
        // delete old tags
        $param = array('contact_id'=>$data['contact_id']);
        $this->db->delete('contact_tags',$param);
        
        // insert new tags
        foreach($tagid as $id){
            $tmp['contact_tag_id'] = $id;
            $f = $this->insert_contact_tags($tmp);
            if($f){
                array_push($result,$this->db->insert_id());
            }
        }
        return $result;
    }
    
    function insert_contact_tags( $data=array() )
    {
        $this->db->insert('contact_tags',$data);
        return ($this->db->affected_rows() > 0)? TRUE : FALSE;
    }
     
    function get_contact( $id=null,$order=null,$limit=50 )
    {
        $data = null;
        $inlineSQL = "(SELECT CONCAT(CONCAT(GROUP_CONCAT(tag_name)),')(',CONCAT(GROUP_CONCAT(contact_tags_cat.id))) FROM contact_tags JOIN contact_tags_cat ON contact_tags.contact_tag_id=contact_tags_cat.id WHERE contact_tags.contact_id=contact.id)";
        $this->db->select("id,name,email,`status`,$inlineSQL as tags");
        $this->db->from('contact');
        $this->db->limit($limit);
        if(is_numeric($id) && $id>0){
            $this->db->where('id',$id);
        }
        if($order!=null){
            $this->db->order_by( $order );
        }
        
        $data = $this->db->get();
        return ($this->db->affected_rows() > 0)? $this->pre_result_tags($data) : FALSE;
    }
    
    function pre_result_tags( $data ){
        foreach( $data->result() as $d ){
            $d->tags = $this->json_tags_get_tag($d->tags);
        }
        return $data;
    }
    
    function json_tags_get_tag( $str='' ){
        $ret = array();
        $par = ')(';
        if($str!=''){
            $data = explode($par,$str);
            if(count($data)>0){
                $id = explode(',',$data[1]);
                $name = explode(',',$data[0]);
                if(count($id) == count($name)){
                    for($x=0; $x<count($id); $x++){
                        $tmp = array();
                        $tmp['id'] = $id[$x];
                        $tmp['tag'] = $name[$x];
                        array_push($ret,$tmp);
                    }
                }
            }
        }
        return json_encode($ret);
    }
    
    function remove_contact( $id=null )
    {
        if(is_numeric($id) && $id>0){
            $this->db->where('id',$id);
            $this->db->delete('contact');
            return ($this->db->affected_rows() > 0)? TRUE : FALSE;
        }
        return FALSE;
    }
    
    function insert_contact( $data=array() )
    {
        $this->db->insert('contact',$data);
        return ($this->db->affected_rows() > 0)? TRUE : FALSE;
    }
    
    function update_contact( $data=array() )
    {
        $this->db->where('id',$data['id']);
        $this->db->update('contact',$data);
        return ($this->db->affected_rows() >= 0)? TRUE : FALSE;
    }
    
    /*
     * Filter upload
     */
    function filter_upload( $data )
    {
        $result = array();
        $result['double'] = array();
        $result['success'] = array();
        
        if(!isset($data['filename']) || $data['filename']==''){
            return FALSE;
        }
        
        if(file_exists($data['filename'])){
            $read = fopen($data['filename'],'r');
            while($line = fgets($read)){
                $mail = strtolower($line);
                
                $data = array();
                $data['name'] = '';
                $data['email'] = $mail;
                $data['status'] = 1;
                $data['order'] = 1;
                
                if($this->is_dobel($mail)){
                    array_push($result['double'],$mail);
                }else{
                    array_push($result['success'],$mail);
                    $this->insert_contact($data);
                }
            }
            fclose($read);
        }
        return $result;
    }
    
    function count_mail( $data=array() ){
        $this->db->select('count(id) as tot')->from('contact');
        $r = $this->db->get();
        $s = $r->result();
        return $s[0]->tot;
    }
}
?>