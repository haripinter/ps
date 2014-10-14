<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Msender extends CI_Model{
    /*
     *
     */
    function get_server( $id=null,$order=null,$limit=50 )
    {
        $data = null;
        $this->db->select("id, server, host, port, `status`");
        $this->db->from('server');
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
    
    function get_server_json(){
        $data = array();
        $server = $this->get_server();
        if($server->num_rows > 0){
            foreach($server->result() as $serv){
                $tmp = array();
                $tmp['id'] = $serv->id;
                $tmp['server'] = $serv->server;
                $tmp['host'] = $serv->host;
                $tmp['status'] = $serv->status;
                array_push($data,$tmp);
            }
        }
        return json_encode($data);
    }
    
    function get_server_name( $id ){
        $ret = $this->get_server($id, null, 1);
        $ret = $ret->result();
        return $ret[0]->server;
    }
    
    /*
     *  -- sender --
     */
    
    function is_dobel( $email )  // string email
    {
        $this->db->select('id')->from('sender')->where('email',$email);
        $data = $this->db->get();
        return ($this->db->affected_rows() > 0)? TRUE : FALSE;
    }

     
    function get_sender( $id=null,$order=null,$limit=50 )
    {
        $data = null;
        $this->db->select("sender.id, server.id as server, server.server as server_name, email, password, sender.`status`");
        $this->db->from('sender');
        $this->db->join('server','sender.server=server.id','left');
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
    
    function remove_sender( $id=null )
    {
        if(is_numeric($id) && $id>0){
            $this->db->where('id',$id);
            $this->db->delete('sender');
            return ($this->db->affected_rows() > 0)? TRUE : FALSE;
        }
        return FALSE;
    }
    
    function insert_sender( $data=array() )
    {
        $this->db->insert('sender',$data);
        return ($this->db->affected_rows() > 0)? $this->db->insert_id() : FALSE;
    }
    
    function update_sender( $data=array() )
    {
        $this->db->where('id',$data['id']);
        $this->db->update('sender',$data);
        return ($this->db->affected_rows() >= 0)? TRUE : FALSE;
    }
    
    function count_sender( $data=array() ){
        $this->db->select('count(id) as tot')->from('sender');
        $r = $this->db->get();
        $s = $r->result();
        return $s[0]->tot;
    }
}
?>