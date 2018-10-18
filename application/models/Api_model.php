<?php
  class Api_model extends CI_Model
  {
    public function __construct()
    {
      $this->load->database();
      date_default_timezone_set("Asia/Jakarta");
    }

    public function userRegister($phone,$gender,$birthdate,$nationality){
      $hit_data = 'phone:'.serialize($phone).'Gender:'.serialize($gender).'Birthdate:'.serialize($birthdate).'Nationality:'.serialize($nationality);

      $action = "USER REGISTER";
      $string_log = 'Init Hit Data '.$hit_data;
      $insert_log = $this->logging($phone,$action,$string_log);

      $data = array(
        'phone'       => $phone,
        'gender'      => $gender,
        'birthdate'   => $birthdate,
        'nationality' => $nationality,
      );
      $this->db->trans_begin();
      $this->db->insert('user_table', $data);
      $status = $this->db->trans_status();
      if($status){
        $string_log = 'User register success';
        $insert_log = $this->logging($phone,$action,$string_log);
        $this->db->trans_commit();
        return true;
      }else{
        $string_log = 'User register failed';
        $insert_log = $this->logging($phone,$action,$string_log);
        $this->db->trans_rollback();
        return false;
      }
    }

    public function checkUser($phone){
			$this->db->select("count(*) as jumlah");
			$this->db->from("user_table");
			$this->db->where("phone",$phone);
			$exec = $this->db->get();
			$result = $exec->result_array();
			if(!empty($result)){
				$data = $result[0]['jumlah'];
			}else{
				$data = 0;
			}
			return $data;
	 	}

    //FUNCTION FOR LOGGING
    private function logging($user,$action,$data){
      $ip = $_SERVER['REMOTE_ADDR'];
      $arr = array(
        'users' 					=> $user,
        'action' 					=> $action,
        'data' 				    => $data,
				'ip' 					    => $ip,
				'logging_date' 		=> date('Y-m-d H:i:s'),
			);
			$exec = $this->db->insert('logging_api', $arr);
    }

    public function wiselyEscapeString($str){
      return htmlspecialchars(htmlentities(strip_tags(trim($str))),ENT_QUOTES);
    }
}
