<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mission extends CI_Controller {
    public function __construct(){
		parent::__construct();
        
        $this->load->database($this->db());
        $this->load->model('mmission');
	}
    
    public function index()
    {
        $this->load->helper('custom_function');
        $data = array();
        $data['missions_default'] = $this->mmission->get_mission();
        $data['missions'] = $this->mission_tag($data);
        $this->load->view('vMission',$data);
    }
    
    function mission_tag( $datas ){
        $this->load->model('mcontact');
        $datas = $datas['missions_default'];

        $res = new stdClass;
        $res->num_rows = $datas->num_rows;
        $res->data = array();
        if($datas->num_rows > 0){
            foreach($datas->result() as $data){
                $tmp = $data;
                $tmp->tags = array();
                $tmq = json_decode($tmp->contact_tags);
                foreach($tmq as $t){
                    $u = $this->mcontact->get_tags($t);
                    $v = array();
                    if($u->num_rows > 0){
                        foreach($u->result() as $w){
                            $wt = array();
                            $wt['id'] = $w->id;
                            $wt['tag_name'] = $w->tag_name;
                            array_push($v,$wt);
                        }
                    }
                    array_push($tmp->tags, $v);
                }
                array_push($res->data, $tmp);
            }
        }
        return $res;
    }
    
    function create( $id=null )
    {
        $this->load->helper('custom_function');
        
        $data = array();
        $data['sender'] = $this->get_sender();
        $data['mission'] = '';
        
        if(isset($id) && intval($id) > 0){
            $data['mission'] = $this->mmission->get_mission( array('id'=>$id) );
        }
        
        $this->load->view('vMissionCreate',$data);
    }
    
    function remove_mission()
    {
        $result = array();
        $result['status'] = 'failed';
        
        $id = $this->input->post('id');
        $xx = $this->mmission->remove_mission($id);
        if($xx) $result['status'] = 'success';
        echo json_encode($result);
    }
    
    function post_mission()
    {
        $result = array();
        $result['data'] = $this->filter_post_mission();
        $result['status'] = 'success';
        echo json_encode($result);
    }
    
    function filter_post_mission()
    {
        $idm  = intval($this->input->post('val_id'));
        $tit  = $this->input->post('val_title');
        $sub  = $this->input->post('val_subject');
        $mas  = $this->input->post('val_master');
        $sen  = $this->input->post('val_sender');
        $tar  = $this->input->post('val_target');
        
        $data = array();
        $data['id'] = $idm;
        $data['name'] = $tit;
        $data['subject'] = $sub;
        $data['msg_out_id'] = $mas;
        $data['sender_id'] = $sen;
        $data['contact_tags'] = $tar;
        
        // for result
        $result = array();
        
        if($idm > 0){
            $r = $this->mmission->update_mission($data);
            $result['id'] = $data['id'];
        }else{
            /* default value */
            $data['mission_cat_id'] = 0;
            $data['status'] = 0;
            $data['order'] = 0;
            
            $r = $this->mmission->insert_mission($data);
            $result['id'] = ($r == FALSE)? 0 : $r;
        }
        
        return $result;
    }
    
    function get_sender(){
        $this->load->model('msender');
        $data = array();
        $rets = $this->msender->get_sender();
        if($rets->num_rows > 0){
            foreach($rets->result() as $ret){
                $tmp = array();
                $tmp['id'] = $ret->id;
                $tmp['email'] = $ret->email;
                array_push($data,$tmp);
            }
        }
        return json_encode($data);
    }
    
    function get_message(){
        $this->load->model('mmail');
        $data = array();
        $rets = $this->mmail->get_message_out();
        if($rets->num_rows > 0){
            $data['status'] = 'success';
            $data['data'] = array();
            foreach($rets->result() as $ret){
                $tmp = array();
                $tmp['id'] = $ret->id;
                $tmp['title'] = $ret->title;
                $tmp['subject'] = $ret->subject;
                array_push($data['data'],$tmp);
            }
        }
        echo json_encode($data);
    }
    
    function get_target(){
        $this->load->model('mcontact');
        $data = array();
        $rets = $this->mcontact->get_tags();
        if($rets->num_rows > 0){
            $data['status'] = 'success';
            $data['data'] = array();
            foreach($rets->result() as $ret){
                $tmp = array();
                $tmp['id'] = $ret->id;
                $tmp['tag_name'] = $ret->tag_name;
                array_push($data['data'],$tmp);
            }
        }
        echo json_encode($data);
    }
    
    function change_status(){
        $data = array();
        $id = intval($this->input->post('id'));
        $st = intval($this->input->post('status'));
        $data['id'] = $id;
        $data['status'] = ($st == 1)? 0 : 1;
        
        $result = array();
        $result['status'] = 'success';
        $result['data'] = $this->mmission->change_status($data);
        echo json_encode($result);
    }
    
    function db(){
        $conf = array();
        $conf['hostname'] = 'localhost';
        $conf['username'] = 'root';
        $conf['password'] = '';
        $conf['database'] = 'broadcast_msg';
        $conf['dbdriver'] = 'mysql';
        $conf['db_debug'] = TRUE;
        return $conf;
    }
}
?>