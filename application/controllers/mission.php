<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mission extends CI_Controller {
    public function __construct(){
		parent::__construct();
        
        $this->load->database($this->db());
        //$this->load->model('mmail');
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
    
    function post_message()
    {
        //$result = $this->mmail->post_message_out();
        //$result['status'] = 'success';
        //echo json_encode($result);
    }
    
    function remove_message_out()
    {
        //$result = array();
        //$result['status'] = 'failed';
        //
        //$id = $this->input->post('iid');
        //$xx = $this->mmail->remove_message_out($id);
        //if($xx) $result['status'] = 'success';
        //echo json_encode($result);
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