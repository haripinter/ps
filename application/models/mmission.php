<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mmission extends CI_Model{
    
    function get_mission( $id=null,$order=null,$limit=50 )
    {
        $data = null;
        $this->db->select("id,mission_cat_id,name,subject,msg_out_id,sender_id,contact_tags,`status`");
        $this->db->from('missions');
        $this->db->limit($limit);
        if(is_numeric($id) && $id>0){
            $this->db->where('id',$id);
        }
        if($order!=null){
            $this->db->order_by( $order );
        }else{
            $this->db->order_by( 'id' );
        }
        
        $data = $this->db->get();
        return ($this->db->affected_rows() > 0)? $data : FALSE;
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