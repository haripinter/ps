<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mmission extends CI_Model{
    
    function get_mission( $data=array() )
    {
        $this->db->select("missions.id as id,mission_cat_id,name,subject,msg_out_id,sender_id,contact_tags,missions.`status` as `status`,count(mission_run.id) as mail_count");
        $this->db->from('missions');
        $this->db->join('mission_run','missions.id = mission_run.mission_id','left');
        $this->db->group_by('missions.id');
        if(isset($data['id']) && intval($data['id'])>0){
            $this->db->where('id',$data['id']);
        }
        if(isset($data['limit']) && intval($data['limit'])>0){
            $this->db->limit($data['limit']);
        }
        if(isset($data['order'])){
            $this->db->order_by($data['order']);
        }else{
            $this->db->order_by( 'missions.id' );
        }
        return $this->db->get();
    }
    
    function get_mission_by_tags( $tags=array() ){
        $sql = 'SELECT id FROM `contact`';
        
        $like = '';
        $n = 0;
        foreach($tags as $tag){
            if($n++ > 0) $like .= ' OR ';
            $like .= 'contact_tags like \'%"'. $tag .'"%\'';
        }
        if($like !='')
        $sql .= " WHERE ". $like;
        
        return $this->db->query($sql);
    }
    
    function remove_mission( $id=null )
    {
        if(is_numeric($id) && $id>0){
            $this->db->where('id',$id);
            $this->db->delete('missions');
            return ($this->db->affected_rows() > 0)? TRUE : FALSE;
        }
        return FALSE;
    }
    
    function insert_mission_run( $data=array()){
        $this->db->insert('mission_run',$data);
        return ($this->db->affected_rows() > 0)? $this->db->insert_id() : FALSE;
    }
    
    function insert_mission( $data=array() )
    {
        $this->db->insert('missions',$data);
        return ($this->db->affected_rows() > 0)? $this->db->insert_id() : FALSE;
    }
    
    function update_mission( $data=array() )
    {
        $this->db->where('id',$data['id']);
        $this->db->update('missions',$data);
        return ($this->db->affected_rows() >= 0)? TRUE : FALSE;
    }
    
    function change_status( $data=array() ){
        $tmp = array('status' => $data['status']);
        $this->db->where('id',$data['id']);
        $this->db->update('missions',$data);
        return ($this->db->affected_rows() > 0)? $data['status'] : FALSE;
    }
    
    function count_mission()
    {
        $this->db->select('count(id) as tot')->from('missions');
        $r = $this->db->get();
        $s = $r->result();
        return $s[0]->tot;
    }
}
?>