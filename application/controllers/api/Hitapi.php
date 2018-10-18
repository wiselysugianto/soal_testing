<?php defined('BASEPATH') OR exit('No direct script access allowed');
header('Access-Control-Allow-Origin: *');
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Hitapi extends REST_Controller {
  public $requestData = array();

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->model('Api_model');
        date_default_timezone_set("Asia/Jakarta");

        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['userRegister_post']['limit'] = 9999999999; // 100 requests per hour per user/key
    }

    public function userRegister_post(){
      $data = file_get_contents('php://input');
      $post = json_decode($data, true);
  	  if(empty($post)){
  		 $post = $this->input->post();
  	  }

      if((isset($post['phone'])) && (isset($post['gender'])) && (isset($post['birthdate'])) && (isset($post['nationality']))){
        $this->setParam('phone',$post['phone']);
        $this->setParam('gender',$post['gender']);
        $this->setParam('birthdate',$post['birthdate']);
        $this->setParam('nationality',$post['nationality']);

        $this->checkParam('phone');
        $this->checkParam('gender');
        $this->checkParam('birthdate');
        $this->checkParam('nationality');
      }
      $check_user = $this->Api_model->checkUser($this->getParam('phone'));
      if($check_user=='0'){
        $server_output = $this->Api_model->userRegister($this->getParam('phone'),$this->getParam('gender'),$this->getParam('birthdate'),$this->getParam('nationality'));
        if($server_output){
          $result = 'User register success';
          $this->response(array('code' => '0200','msg' => $result), 200);
        }else{
          $result = 'User register failed';
          $this->response(array('code' => '0400','msg' => $result), 400);
        }
      }else{
        $result = 'Duplicate entry';
        $this->response(array('code' => '1062','msg' => $result), 403);
      }
    }

    // Set POST parameter name and its value
    public function setParam($name, $value){
      $this->requestData[$name] = $value;
    }

    // Retrieve POST parameter value
    public function getParam($name){
      if(isset($this->requestData[$name])){
        return $this->requestData[$name];
      }
      return "";
    }

    public function checkParam($requestData){
      if(null == $this->getParam($requestData)){
        $result = 'Some fields are required';
        die($this->response(array('code' => '1064','msg' => $result), 422));
      }
    }
}
