<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
	
//     public function index(){
//         $this->redirect('');
//     }

	public function test(){
		$this->display();
	}

	public function test_mutiPost(){
		$this->ajaxReturn(spt_json_success(I('post.')));
	}

//     public function req_get_test(){
//         $this->reqGet(array('id','ruby'));
//         $this->ajaxReturn(I('id'));
//     }

	
	public function resetPage(){
	    $this->display('resetPassword');
	}
	
	public function upload(){
	    $this->display();
	}
	
}