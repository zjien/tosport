<?php
namespace Home\Controller;

use Common\Controller\BaseController;
class CommentController extends BaseController{
    /**
     * 发送/回复 消息/评论
     */
    public function sendComment(){
        $this->getlogin()->reqPost(array('content','tl_id','receiver_id'));
        $data=I('post.');
        $data['sender_id']=session('user.u_id');
        $this->ajaxReturn(D('Comment')->sendComment($data));
    }
    
    /**
     * 点/取消 赞
     */
    public function like(){
        $this->getlogin()->reqPost(array('tl_id','receiver_id'));
        $data=I('post.');
        $data['sender_id']=session('user.u_id');
        $data['send_time']=NOW_TIME;
        $this->ajaxReturn(D('Comment')->like($data));
    }
    
    /**
     * 显示自己发的评论
     */
    public function listsMyComment($page = 1,$limit = 10){
        $this->getlogin()->reqGet();
        $this->ajaxReturn(D('Comment')->listsMyComment(session('user.u_id'),$page,$limit));
    }
    
    /**
     * 删除自己的发表的评论
     */
    public function deleteComment(){
        $this->getlogin()->reqPost(array('c_id'));
        $c_id=I('post.c_id');
        $me_id=session('user.u_id');
        $this->ajaxReturn(D('Comment')->deleteComment($c_id,$me_id));
    }
    
    /**
     * 显示所有的评论/赞
     * @param number $page
     * @param number $limit
     */
    public function listsAllMessage($page = 1,$limit = 10){
        $this->getlogin()->reqGet();
        $this->ajaxReturn(D('Comment')->listsAllMessage(session('user.u_id'),$page,$limit));
    }
    
    /**
     * 显示特定某条动态的评论/赞
     */
    public function listsSpeComment(){
        $this->getlogin()->reqPost(array('tl_id'));
        $this->ajaxReturn(D('Comment')->listsSpeComment(I('post.tl_id')));
    }
    
    /**
     * 显示所有的点赞
     */
    public function listsLike($page = 1,$limit = 10){
        $this->getlogin()->reqGet();
        $this->ajaxReturn(D('Comment')->listsLike(session('user.u_id'),$page,$limit));
    }
}