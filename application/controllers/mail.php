<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mail extends CI_Controller {
    public function __construct(){
		parent::__construct();
        
        $this->load->database($this->db());
        $this->load->model('mmail');
	}
    
    public function index()
    {
        $data['message'] = $this->mmail->get_message_out();
        $this->load->view('vMail',$data);
    }
    
    function create( $id=null)
    {
        $data['message'] = '';
        if(isset($id) && is_numeric($id) && $id > 0){
            $data['message'] = $this->mmail->get_message_out( array('id'=>$id) );
        }
        $this->load->view('vMailCreate', $data);
    }
    
    function post_message()
    {
        $result = $this->mmail->post_message_out();
        $result['status'] = 'success';
        echo json_encode($result);
    }
    
    function remove_message_out()
    {
        $result = array();
        $result['status'] = 'failed';
        
        $id = $this->input->post('iid');
        $xx = $this->mmail->remove_message_out($id);
        if($xx) $result['status'] = 'success';
        echo json_encode($result);
    }
    
    function getServer( $data )
    {
        $conf = array();
        $conf['useragent'] = 'Mozilla/5.0 (Power PC) Gecko/20100101 Firefox/30.0';
        
        switch($data['server']){
            case 'google':
                $conf['protocol'] = 'smtp';
                $conf['smtp_host'] = 'ssl://smtp.googlemail.com';
                $conf['smtp_port'] = 465;
                $conf['newline']   = "\r\n"; 
                $conf['mailtype']  = 'html';
                $conf['charset']   = 'utf-8';
                $conf['smtp_user'] = $data['user'];
                $conf['smtp_pass'] = $data['pass'];
                break;
                
            case 'yahoo':
                break;
        }
        return $conf;
    }
    
    /*
     * $data['user'] -> user login
     * $data['pass'] -> user password
     * $data['from']
     * $data['to']
     * $data['subject']
     * $data['message']
     * $data['server']  -> google / yahoo / 
     */
    
    function sendMail( $data=array() ){
        $res = array(); 
        $conf = $this->getServer( $data );
        
        $this->load->library('email',$conf);
        $this->email->from($data['from']);
        $this->email->to($data['to']);
        $this->email->subject($data['subject']);
        $this->email->message($data['message']);
        
        if($this->email->send()){
            $res['status'] = 'success';
            $res['data']   = 'sent';
        }else{
            $res['status'] = 'failed';
            $res['data']   = 'failed';
        }
        
        echo json_encode($res);
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