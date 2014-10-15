<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mission extends CI_Controller {
    public function __construct(){
		parent::__construct();
        
        $this->load->database($this->db());
        $this->load->model('mmission');
	}
    
    public function index()
    {
        //$data['message'] = $this->mmail->get_message_out();
        $this->load->view('vMission');
    }
    
    function create( $id=null)
    {
        $this->load->helper('custom_function');
        
        $data = array();
        $data['sender'] = $this->get_sender();
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
        $data['mission_cat_id'] = $sen;
        $data['name'] = $tit;
        $data['subject'] = $sub;
        $data['msg_out_id'] = $mas;
        $data['sender_id'] = $sen;
        $data['contact_tags'] = $tar;
        $data['status'] = 0;
        $data['order'] = 0;
        
        // for result
        $result = array();
        
        if($idm > 0){
            $r = $this->mmission->update_mission($data);
            $result['id'] = $data['id'];
        }else{
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