<?php
namespace Common\Model;

use Common\Model\BaseModel;
class DateExerciseModel extends BaseModel{
    protected $_validate=array(
        array('sport_time',array(0,NOW_TIME),'运动时间不能早于发布时间',self::MUST_VALIDATE,'notbetween',1),
        array('people_amount',array(1,25),'人数必须在1~25人之间',self::MUST_VALIDATE,'between',1),
        array('content','0,140','附加内容不可超过140字',self::MUST_VALIDATE,'length',1)
    );
    
    protected $_auto=array(
        array('create_time',NOW_TIME,1)
    );
    
    /**
     * 创建一条约运动
     */
    public function createDE($data){
        if(isset($_FILES['picture'])){
            $res=$this->PicUpload(2,'exercise','picture');
            if(isset($res['imgurl'])){
                $data['picture'] = $res['imgurl'];
            }else{
                return spt_json_error('图片上传失败');
            }
        }
        if($this->create($data,1)){
            if($this->add()){
                return spt_json_success('发布成功！');
            }
            return spt_json_error('发布失败！');
        }
        return spt_json_error($this->getError());
    }
    
    /**
     * 删除一条约运动
     */
    public function deleteDE($de_id,$creator_id){
        if($this->where("de_id=%d AND creator_id=%d",$de_id,$creator_id)->delete()){
            return spt_json_success('删除成功!');
        }
        return spt_json_error('删除失败!');
    }
    
    /**
     * 显示 指定某条约运动
     */
    public function listsSpeDE($de_id){
        $res=$this->where("de_id=%d",$de_id)->find();
        if($res){
            return spt_json_success($res);
        }
        return spt_json_error('出现错误了');
    }
    
    /**
     * 显示同城最新的约运动
     */
    public function listsCityDE($my_region,$page,$limit){
        $this->pageLegal($page, $limit);
        $res=$this->table("spt_user_info u,spt_date_exercise de")
                   ->field("de_id,creator_id,nickname,avatar,sport_type,sport_place,sport_time,content,people_amount,booked_amount,picture,create_time")
                   ->where("creator_region='%s' AND booked_amount<people_amount AND u.u_id=de.creator_id",$my_region)
                   ->order('create_time DESC')
                   ->limit(($page-1)*$limit,$limit)
                   ->select();
        if($res){
            return spt_json_success($res);
        }
        return spt_json_error('暂无约，你来发布一条吧');
    }
    
    /**
     * 显示热门的约运动
     */
    public function listsHotDE($my_region,$page,$limit){
        $this->pageLegal($page, $limit);
        $res=$this->table("spt_user_info u,spt_date_exercise de")
                    ->field("de_id,creator_id,nickname,avatar,sport_type,sport_place,sport_time,content,people_amount,booked_amount,picture,create_time")
                    ->where("creator_region='%s' AND booked_amount<people_amount AND u.u_id=de.creator_id",$my_region)
                    ->order('de.booked_amount DESC')
                    ->limit(($page-1)*$limit,$limit)
                    ->select();
        if($res){
            return spt_json_success($res);
        }
        return spt_json_error('暂无信息');
    }
    
    /**
     * 约ta
     */
    public function toDate($data){
        if($this->table("spt_date_person")->where("de_id=%d AND me_id=%d",$data['de_id'],$data['me_id'])->delete()){
            return spt_json_success('取消预约成功');
        }
        if($this->where("people_amount=booked_amount AND de_id=%d",$data['de_id'])->find()){
            return spt_json_error('预约人数已经满了');
        }
        $data['create_time']=NOW_TIME;
        if(M('DatePerson')->data($data)){
            if(M('DatePerson')->add()){
                return spt_json_success('预约运动成功！');
            }
            return spt_json_error('预约运动失败！');
        }
        return spt_json_error('出现问题了');
    }
    
    /**
     * 列出约我的人
     */
    public function listsDateGuy($creator_id,$page,$limit){
        $this->pageLegal($page, $limit);
        $res=$this->table('spt_date_person dp,spt_user_info u')
                   ->field("u.u_id,u.nickname,u.sex,u.avatar,dp.de_id,dp.create_time")
                   ->where("dp.creator_id=%d AND u.u_id=dp.me_id",$creator_id)
                   ->order("dp.create_time DESC")
                   ->limit(($page-1)*$limit,$limit)
                   ->select();
        if($res){
            return spt_json_success($res);
        }
        return spt_json_error('暂无约');
    }
    
    /**
     * 列出我参加的约运动
     */
    public function listsJoin($me_id,$page,$limit){
        $this->pageLegal($page, $limit);
        $res=$this->table('spt_date_person dp,spt_date_exercise de')
                    ->where('dp.me_id=%d AND de.de_id=dp.de_id',$me_id)
                    ->order('dp.create_time DESC')
                    ->limit(($page-1)*$limit,$limit)
                    ->select();
        if($res){
            return spt_json_success($res);
        }
        return spt_json_error('暂无参加的约运动');
    }
}