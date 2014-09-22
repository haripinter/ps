<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mmail extends CI_Model{
    
    function get_message_out( $data=array() )
    {
        $ret = array();
        $this->db->select('id,title,subject,message,`status`,`timestamp`');
        $this->db->from('message_out');
        if(isset($data['id']) && is_numeric($data['id']) && $data['id']>0){
            $this->db->where('id',$data['id']);
        }
        if(isset($data['order'])){
            $this->db->order_by($data['order']);
        }
        if(isset($data['limit']) && is_numeric($data['limit']) && $data['limit']>0){
            $this->db->limit($data['limit']);
        }
        
        $ret = $this->db->get();
        return $ret;
    }
    
    function post_message_out()
    {
        $ret  = array();
        $data = array();
        $data['id']  = $this->input->post('idc');
        $data['msg_in_id']  = 0;
        $data['title']  = $this->input->post('title');
        $data['subject']  = $this->input->post('subject');
        $data['message']  = htmlspecialchars($this->input->post('message'));
        $data['type'] = 'html';
        $data['timestamp'] = date("Y-m-d h:i:s");
        $data['status'] = 1;
        
        $s = FALSE;
        if(is_numeric($data['id']) && $data['id']>0){
            $s = $this->update_message_out( $data );
        }else{
            $s = $this->insert_message_out( $data );
            $data['id'] = $this->db->insert_id();
        }
        $ret['data'] = $data;
        if($s){
            $ret['status'] = 'success';
        }else{
            $ret['status'] = 'failed';
        }
        
        return $ret;
    }
    
    function insert_message_out( $data=array() )
    {
        $this->db->insert('message_out',$data);
        return ($this->db->affected_rows() > 0)? TRUE : FALSE;
    }
    
    function update_message_out( $data=array() )
    {
        $this->db->where('id',$data['id']);
        $this->db->update('message_out',$data);
        return ($this->db->affected_rows() > 0)? TRUE : FALSE;
    }
    
    function remove_message_out( $id=null )
    {
        if(is_numeric($id) && $id>0){
            $this->db->where('id',$id);
            $this->db->delete('message_out');
            return ($this->db->affected_rows() > 0)? TRUE : FALSE;
        }
        return FALSE;
    }
}