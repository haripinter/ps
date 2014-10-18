<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mexec extends CI_Model{
    
    function get_info_sender( $data=array() )
    {
        $this->db->select('server.server as server,email,password,missions.subject as subject,message');
        $this->db->from('missions');
        $this->db->join('sender','missions.sender_id=sender.id','left');
        $this->db->join('message_out','missions.msg_out_id=message_out.id','left');
        $this->db->join('server','sender.server=server.id','left');
        $this->db->where('missions.id',$data['id']);
        
        if(!isset($data['limit'])) $data['limit'] = 10;
        $this->db->limit($data['limit']);
        return $this->db->get();
    }
    
    // id mission and limit count
    function get_target_contact( $data=array() ){
        $this->db->select('mission_run.id as id,email');
        $this->db->from('mission_run');
        $this->db->join('contact','contact.id=contact_id','left');
        $this->db->where('mission_id',$data['id']);
        $this->db->where('mission_run.`status`','0');
        
        if(!isset($data['limit'])) $data['limit'] = 10;
        $this->db->limit($data['limit']);
        return $this->db->get();
    }
    
    function last_info( $data=array() ){
        $this->db->select('mission_run.`status` as `status`,count(id) as count');
        $this->db->from('mission_run');
        $this->db->where('mission_id',$data['id']);
        $this->db->group_by('`status`');
        return $this->db->get()->result();
    }
    
    function update_status( $id ){
        $data = array('status'=>'1');
        $this->db->where('id',$id);
        $this->db->update('mission_run',$data);
        return ($this->db->affected_rows() >= 0)? TRUE : FALSE;
    }
}
?>