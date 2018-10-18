<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Controller_logika extends CI_Controller {

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
	public function index()
	{
		$this->load->view('view_logika');
	}

	public function process(){
		$data = file_get_contents('php://input');
		$post = json_decode($data, true);
		if(empty($post)){
		 $post = $this->input->post();
		}

		$data = array();
		$step = $post['step'];
		for($i=$step;$i>=1;$i--){
			if($i==$step){
				for($j=1;$j<=$i;$j++){
					$data[$i][$j] = '1';
				}
			}else{
				for($j=$i;$j>=1;$j--){
					for($k=1;$k<=$i;$k++){
						if($j==$k){
							$data[$j][$k] = '2';
						}else{
							$data[$j][$k] = '1';
						}
					}
				}
			}
		}
		print_r($data);exit;
	}
}
