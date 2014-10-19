<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Exec extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
     
    public function __construct(){
		parent::__construct();
        
        $this->load->database($this->db());
        $this->load->model('mexec');
	}
    
	public function index()
	{   
        $this->load->model('mmission');
        $this->load->helper('custom_function');
        $data['missions'] = $this->mmission->get_mission();
        $this->load->view('vExec',$data);
	}
    
    function run(){
        $id = intval($this->input->post('id'));
        $co = intval($this->input->post('count'));
        $pars = array('id'=>$id,'limit'=>$co);
        
        //$pars['only'] = 'yahoo';
        
        $ret = array();
        $ret['status'] = 'success';
        if($id>0 && $co>0){
            $info = array();
            $infx = $this->mexec->get_info_sender($pars);
            foreach($infx->result() as $inf) $info = $inf;
            //$ret['info'] = json_encode($info);
            
            $data = array();
            $data['user'] = $info->email;
            $data['pass'] = $info->password;
            $data['from'] = $info->email;
            $data['subject'] = $info->subject;
            $data['message'] = htmlspecialchars_decode($info->message);
            $data['server']  = $info->server;
            
            $mail  = array();
            $mails = $this->mexec->get_target_contact($pars);
            foreach($mails->result() as $ma){
                $data['to'] = $ma->email;
                array_push($mail, $ma->email);
                $send = $this->sendMail($data);
                if($send['status']=='success'){
                    $this->mexec->update_status($ma->id, 1);
                }else{
                    $this->mexec->update_status($ma->id, 2);
                }
            }
            $ret['mail'] = json_encode($mail);
            $ret['last_info'] = $this->mexec->last_info($pars);
            $ret['sent'] = 0;
            $ret['waiting'] = 0;
        }
        echo json_encode($ret);
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
                $conf['protocol'] = 'smtp';
                $conf['smtp_host'] = 'ssl://smtp.mail.yahoo.com';
                $conf['smtp_port'] = 465;
                $conf['newline']   = "\r\n"; 
                $conf['mailtype']  = 'html';
                $conf['charset']   = 'utf-8';
                $conf['smtp_user'] = $data['user'];
                $conf['smtp_pass'] = $data['pass'];
                break;
                
            case 'kreswanti':
                $conf['protocol'] = 'smtp';
                $conf['smtp_host'] = 'ssl://sayegan.idwebhost.com';
                $conf['smtp_port'] = 465;
                $conf['newline']   = "\r\n"; 
                $conf['mailtype']  = 'html';
                $conf['charset']   = 'utf-8';
                $conf['smtp_user'] = $data['user'];
                $conf['smtp_pass'] = $data['pass'];
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
        
        //echo json_encode($res);
        return $res;
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

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */