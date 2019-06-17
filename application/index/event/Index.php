<?php
/**
 * Created by PhpStorm.
 * User: lizhipeng
 * Date: 2019/2/12
 * Time: 4:18 PM
 */
namespace app\index\event;

use app\base\controller\Base;
use app\demand\model\Demand as DemandModel;
use app\region\model\Region;
use app\user\model\User as UserModel;
use app\dynamic\model\Dynamic;
use app\user\event\User as UserEvent;
use app\adopt\model\AdoptList;
use app\helper\helper;


class Index extends Base
{
    /**
     * @desc 获取首页列表(旧版)
     * @param int  $param['page']  查询页数
     * @return array
     */
    public function getReList($param)
    {
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $model = new DemandModel();
        $data = $model->selectDemand(true,$param['page'],5);
        if(count($data)<=0){
            $Result['errCode'] = 'L10029';
            $Result['errMsg'] = '抱歉，暂无更多数据！';
            return $Result;
        }
        $arr = collection($data)->toArray();
        foreach ($arr as $k=>$v){
            $repeat_uids[] = $v['uid'];
            $region_ids[] = $v['region'];
        }
        $new_uids = array_unique($repeat_uids);
        $r_ids = array_unique($region_ids);
        $userModel =new UserModel();
        $userData = $userModel->selectUser(['status'=>'1','id'=>['IN',$new_uids]],0,count($new_uids));
        $regionModel = new Region();
        $selectRegion =  $regionModel->selectRegion(['status'=>'1','rg_id'=>['IN',$r_ids]],0,count($r_ids));
        foreach ($selectRegion as $k => $v){
            $region[$v['rg_id']] = $v['rg_name'];
        }
        if(count($userData)<=0){
            $Result['errCode'] = 'L10030';
            $Result['errMsg'] = '抱歉，暂无用户数据！';
            return $Result;
        }
        foreach ($userData as $k => $v){
            $user[$v->id] = [
                'name'=>$v->name,//用户名
                'head_portrait' =>$v->head_portrait_url,//头像地址
            ];
        }
        foreach ($arr as $k => $v){
            $arr[$k]['upload'] = unserialize($v['upload']);
            $arr[$k]['uname'] = base64_decode($user[$v['uid']]['name']);
            // 新增头像字段 --Author:lizhipeng Date:2019.02.15
            $arr[$k]['head_portrait'] = $user[$v['uid']]['head_portrait'];
            // 字符串转换
            $arr[$k]['type_str'] = strToType($v['type']);
            $arr[$k]['gender_str'] = $v['gender']=='1' ? '公' : '母';
            $arr[$k]['charge_str'] = $v['charge']=='1' ? '免费' : '收费';
            $arr[$k]['vaccine_str'] = $v['vaccine']=='1' ? '未注射' : '已注射';
            // 地区转换
            $arr[$k]['region_str'] = $region[$v['region']];
        }

        $Result['data']=$arr;
        return $Result;
    }

    /**
     * @desc 获取首页列表数据 （V1）
     *
     */
    public function getHomeList(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $articleList = $this->_getArticleList();
        $Result['data'] =$articleList;
        return $Result;
    }

    protected function _getArticleList(){
        $top_list = $dynamic_list = $list = [];
        $articleModel = new Dynamic();
        $getArticleList = $articleModel->selectArticle(['status'=>1], $this->data['page'],5);
        if(empty($getArticleList)){
            return [];
        }
        $getArticleList =selectDataToArray($getArticleList);
        $getAllUid = $allDid = [];
        foreach ($getArticleList as $k => $v){
            $getAllUid[] = $v['uid'];
            $allDid[] = $v['id'];
        }
        $allUid = array_unique($getAllUid);
        $event = new UserEvent();
        // 获取相关用户信息
        $userData = $event->setData(['uid'=>$allUid])->getAllUserList();

        foreach ($getArticleList as $k => $v){
            $getArticleList[$k]['name'] = $userData[$v['uid']]['name'];
            $getArticleList[$k]['user_url'] = $userData[$v['uid']]['url'];
        }
        $i = 0 ;
        foreach ($getArticleList as $k => $v){
            if($v['top'] == '1'){
                $top_list[] = $v;
            }else{
                $dynamic_list[]  = $v ;
            }
            $i++;
        }
        $list = [
            'top_list' => $top_list,
            'dynamic_list' => $dynamic_list,
        ];
        return $list;

    }

    /**
     * @desc 获取首页列表数据（V2）
     *
     */
    public function getHomeListVT(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => 'success',
            'data'    => [],
        ];
        $articleList = $this->_getAdoptList();
        $Result['data'] =$articleList;
        return $Result;
    }

    protected function _getAdoptList(){
        $adoptModel = new AdoptList();
        $getAdoptList = $adoptModel->getAdoptPageList(['state'=>1,'adoptState'=>1], $this->data['page'],5,'id,uid,imgList,describe,browses,createdAt,adoptState');
        if(empty($getAdoptList)){
            return [];
        }
        $getAdoptList =selectDataToArray($getAdoptList);
        $getAllUid = $allDid = [];
        foreach ($getAdoptList as $k => $v){
            $getAllUid[] = $v['uid'];
            $allDid[] = $v['id'];
        }
        $allUid = array_unique($getAllUid);
        $event = new UserEvent();
        // 获取相关用户信息
        $userData = $event->setData(['uid'=>$allUid])->getAllUserList();

        foreach ($getAdoptList as $k => $v){
            $getAdoptList[$k]['name'] = $userData[$v['uid']]['name'];
            $getAdoptList[$k]['user_url'] = $userData[$v['uid']]['url'];
        }

//        $helper = new helper();
//        $flagList = $helper->GetFlagList();
//        $flagKeys = array_keys($flagList);
        foreach ($getAdoptList as $k => $v){
            $getAdoptList[$k]['imgList'] = json_decode($v['imgList'],true);
//            foreach ($flagKeys as $k1 => $v1){
//                if(isset($getAdoptList[$k][$v1])){
//                    $getAdoptList[$k][$v1] = $flagList[$v1][$v[$v1]];
//                }
//
//            }
        }
        return $getAdoptList;

    }

    /**
     * 暂未使用
     * @param $allDid
     * @return array
     */
    protected function _getCommentNum($allDid){
        if(empty($allDid))return [];  //未获取到did直接返回空
        $helper = new helper();
        $data = $helper->setData(['did'=>$allDid])->GetCommentList();
        $IdToNum = [];
        if(empty($data)){
            foreach ($allDid as $K => $v){
                $IdToNum[$v]=0;
            }
            return $IdToNum;
        }

        foreach ($data as $did => $list){
            $data[$did] = count($list['list']);
        }
        return $data;
    }

}