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
        //$data['message'] = '';
        //if(isset($id) && is_numeric($id) && $id > 0){
        //    $data['message'] = $this->mmail->get_message_out( array('id'=>$id) );
        //}
        $this->load->library('crypto');
        echo $this->crypto->enkrip('hai').'<br/>';
        echo $this->crypto->dekrip('XyMjI8KAIyMjX1Ze ');
        $this->load->view('vMissionCreate');
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