<?php
namespace Common\Model;

use Common\Model\BaseModel;
class TimelineModel extends BaseModel{
    protected $_validate=array(
        array('content','','内容不能为空',self::EXISTS_VALIDATE,'notequal',1),
        array('content','1,140','内容长度不能超过140字',self::EXISTS_VALIDATE,'length',1)
    );
    
    protected $_auto=array(
        array('create_time',NOW_TIME,1)
    );
    
    /**
     * 发表一条 动态
     */
    public function send($data){
        if(isset($_FILES['picture'])){
            $res=$this->PicUpload(2,'timeline','picture');
            if(isset($res['imgurl'])){
                $data['picture'] = $res['imgurl'];
            }else{
                return spt_json_error('图片上传失败');//需要测试
            }
        }
        
        if($this->create($data)){//这里如果没有$data参数会出问题，sender_id字段会不见
            if($this->add()){
                return spt_json_success('发表成功');
            }
            return spt_json_error('发表失败');
        }
        return spt_json_error($this->getError());
    }
    
    /**
     * 删除一条动态 同时会删除相关的评论
     */
    public function deleteTimeline($data){
        if($this->where("tl_id=%d AND sender_id=%d",$data['tl_id'],$data['sender_id'])->delete()){
            M('Comment')->where("tl_id=%d",$data['tl_id'])->delete();
            return spt_json_success('删除成功');
        }
        return spt_json_error('删除失败');
    }
    
    /**
     * 显示某个人的动态
     */
    public function listsSpeTimeline($data){
        $this->pageLegal($data['page'], $data['limit']);
        $res=$this->table("spt_timeline tl,spt_user_info u")
                    ->field("tl.tl_id,tl.content,tl.picture,tl.create_time,tl.c_amount,tl.like_amount,tl.sender_id,u.nickname as sender_nickname,u.avatar as sender_avatar")
                    ->where("tl.sender_id=%d AND u.u_id=tl.sender_id",$data['sender_id'])
                    ->limit(($data['page']-1)*$data['limit'],$data['limit'])
                    ->order('create_time DESC')//以发表发表时间倒叙显示
                    ->select();
        if($res){
           return spt_json_success($res);
        }
        return spt_json_error('目前Ta还没发过动态');
    }
    
    /**
     * 显示指定某条动态
     */
    public function listsOneTimeline($tl_id){
        $res=$this->table("spt_timeline tl,spt_user_info u")
                    ->field("tl.tl_id,tl.content,tl.picture,tl.create_time,tl.c_amount,tl.like_amount,tl.sender_id,u.nickname as sender_nickname,u.avatar as sender_avatar")
                    ->where("tl.tl_id=%d AND u.u_id=tl.sender_id",$tl_id)
                    ->select();
        if($res){
            return spt_json_success($res);
        }
        return spt_json_error('无此动态或者已经删除');
    }
    
    
    /**
     * 列出我关注的人的动态（包含我的动态）
     */
    public function listsAllTimeline($me_id,$page,$limit){
        $this->pageLegal($page, $limit);
        $res=$this->table("spt_user_info u,spt_timeline tl,spt_friend f")
                    ->distinct(true)//与下面的field('f.me_id')字段一起使用，即数据集排除f.me_id字段中重复的的记录
                    ->field("f.me_id")
                    ->field("tl.tl_id,tl.content,tl.picture,tl.create_time,tl.c_amount,tl.like_amount,tl.sender_id,u.nickname as sender_nickname,u.avatar as sender_avatar")
                    //->where("(f.me_id=%d AND at.sender_id=f.friend_id) OR (f.friend_id=%d AND at.sender_id=f.me_id)",$me_id,$me_id)//取决于FriendModel的请求的完成才开启
                    ->where("u.u_id=tl.sender_id AND f.me_id=%d AND (tl.sender_id=f.friend_id OR tl.sender_id=%d)",$me_id,$me_id)
                    ->order("tl.create_time DESC")
                    ->limit(($page-1)*$limit,$limit)
                    ->select();
        
        if($res){
            return spt_json_success($res);
        }
        return spt_json_error($this->getDbError());
    }
    
    /**
     * 列出同城用户的动态
     */
    public function listsCityTimeline($region,$page,$limit){
        $this->pageLegal($page, $limit);
        $res=$this->table("spt_user_info u,spt_timeline tl")
                    ->field("tl.tl_id,tl.content,tl.picture,tl.create_time,tl.c_amount,tl.like_amount,tl.sender_id,u.nickname as sender_nickname,u.avatar as sender_avatar")
                   ->where("u.u_id=tl.sender_id AND tl.now_region='%s'",$region)
                   ->order("tl.create_time desc")
                   ->limit(($page-1)*$limit,$limit)
                   ->select();
        if($res){
            return spt_json_success($res);
        }
        return spt_json_error('暂无数据');
    }
}