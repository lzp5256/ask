<?php
namespace app\release\event;

use app\base\controller\Base;
use app\dynamic\model\DynamicComment;
use app\dynamic\model\DynamicLike;
use app\helper\helper;
use app\user\event\User as UserEvent;
use app\user\model\User;
use app\qa\model\Qa;
use app\dynamic\model\Dynamic;
use app\activity\model\ActivityDetail;
use think\Db;
use think\Request;

class Handle extends Base
{
    protected $log_level = 'error';

    /**
     * @desc 发布问答处理
     * @date 2019.04.16
     * @author lizhipeng
     * @return array
     */
    public function handleReleaseQaRes(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        // 验证用户是否存在
        $userModel = new User();
        $checkUserRes = $userModel->findUser(['id'=>$this->data['param']['uid'],'status'=>1]);
        if(empty($checkUserRes)){
            $Result['errCode'] = 'L10065';
            $Result['errMsg'] = '错误码[L10065]';
            return $Result;
        }
        $qaModel = new Qa();
        $saveRes = $qaModel->addQa($this->_getAddQaData());
        if(!$saveRes){
            $Result['errCode'] = 'L10066';
            $Result['errMsg'] = '错误码[L10066]';
            return $Result;
        }
        return $Result;
    }

    /**
     * @desc 发布文章处理
     * @date 2019.04.18
     * @author lizhipeng
     * @return array
     */
    public function handleReleaseArticleRes(){
        $Result = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        // 验证用户是否存在
        $userModel = new User();
        $checkUserRes = $userModel->findUser(['id'=>$this->data['param']['uid'],'status'=>1]);
        if(empty($checkUserRes)){
            $Result['errCode'] = 'L10071';
            $Result['errMsg'] = '错误码[L10071]';
            return $Result;
        }
        $model = new Dynamic();
        $saveRes = $model->addArticle($this->_getAddArticleData());
        if(!$saveRes){
            $Result['errCode'] = 'L10072';
            $Result['errMsg'] = '错误码[L10072]';
            return $Result;
        }
        return $Result;
    }

    public function handleReleaseCommentRes(){
        $Result = ['errCode' => '200', 'errMsg'  => '发布成功', 'data' => []];
        $handle_data = [];
        // 验证用户是否存在
        $userModel = new User();
        $checkUserRes = $userModel->findUser(['id'=>$this->data['param']['uid'],'status'=>1]);
        if(empty($checkUserRes)){
            $Result['errCode'] = 'L10076';
            $Result['errMsg'] = '抱歉,系统异常,未查询到用户信息';
            writeLog(getWriteLogInfo('发布评论,验证用户异常',json_encode($this->data),$userModel->getLastSql()),$this->log_level);
            return $Result;
        }

        // 判断cation
        if($this->data['param']['action']){
            switch ($this->data['param']['action']){
                case 'dynamic':
                    if(($res  = $this->_getHandleDynamicInfo() ) && $res['errCode'] != '200'){
                        return $res;
                    }
                    $handle_data = $res['data'];
                    break;
                case 'activity':
                    if(($res  = $this->_getHandleActivityInfo() ) && $res['errCode'] != '200'){
                        return $res;
                    }
                    $handle_data = $res['data'];
                    break;
                default:
                    $Result['errCode'] = 'L10076';
                    $Result['errMsg'] = '抱歉,系统异常,未查询到用户信息';
                    return $Result;
            }
        }


        $Result['data'] = $handle_data;
        return $Result;
    }

    /**
     * @desc 处理点赞操作 1.参数由setData方法传入
     *
     * @return array
     */
    public function handleReleaseCommentLikeRes(){
        $Result = ['errCode' => '200', 'errMsg'  => '', 'data' => []];
        $helper = new helper();
        // 验证用户信息
        $checkUser = $helper->setData(['uid'=>$this->data['param']['uid']])->GetUserStatusById();
        if(empty($checkUser)){
            $Result['errCode'] = 'L10085';
            $Result['errMsg'] = '抱歉,系统异常,未查询到用户信息';
            writeLog(getWriteLogInfo('点赞,验证用户异常',json_encode(['uid'=>$this->data['param']['uid']]),''),$this->log_level);
            return $Result;
        }

        // 判断cation
        if($this->data['param']['action']){
            switch ($this->data['param']['action']){
                case 'dynamic':
                    if(($res  = $this->_getHandleDynamicLikeInfo() ) && $res['errCode'] != '200'){
                        return $res;
                    }
                    $handle_data = $res['data'];
                    break;
                case 'activity':
                    if(($res  = $this->_getHandleActivityLikeInfo() ) && $res['errCode'] != '200'){
                        return $res;
                    }
                    $handle_data = $res['data'];
                    break;
                default:
                    $Result['errCode'] = 'L10076';
                    $Result['errMsg'] = '抱歉,系统异常,未查询到用户信息';
                    return $Result;
            }
        }

    }

    // 处理动态评论信息
    protected function _getHandleDynamicInfo(){
        $Result = ['errCode' => '200', 'errMsg'  => '', 'data' => []];
        Db::startTrans(); //开启事务
        // 验证动态是否存在
        $dymanicModel = new Dynamic();
        $findInfo = $dymanicModel->findArticle(['id'=>$this->data['param']['did'],'status'=>1]);
        if(empty($findInfo)){
            $Result['errCode'] = 'L10077';
            $Result['errMsg'] = '抱歉,系统异常,未查询到此动态详情';
            writeLog((getWriteLogInfo('发布评论,验证动态异常',json_encode($this->data),$dymanicModel->getLastSql())),$this->log_level);
            return $Result;
        }
        // 更新成功后更新首页数据
        $saveHomeCommentNum = $dymanicModel->where(['id'=>$this->data['param']['did'],'status'=>1])->setInc('comment','1');
        if(!$saveHomeCommentNum){
            $Result['errCode'] = 'L10082';
            $Result['errMsg'] = '错误码[L10082]';
            writeLog(getWriteLogInfo('发布评论,更新comment数据异常','comment setInc 1 failed',$dymanicModel->getLastSql()),$this->log_level);
            Db::rollback();
            return $Result;
        }
        // 储存数据
        $commentModel = new DynamicComment();
        $addComment = $commentModel->addDynamicComment($this->_getAddCommentData());
        if(!$addComment){
            $Result['errCode'] = 'L10078';
            $Result['errMsg'] = '错误码[L10072]';
            writeLog(getWriteLogInfo('发布评论,储存数据异常',json_encode($this->_getAddCommentData()),$commentModel->getLastSql()),$this->log_level);
            Db::rollback();
            return $Result;
        }


        // 查询更新的数据
        $findCommentInfo = $commentModel->findDynamicCommentInfo(['id'=>$commentModel->getLastInsID(),'status'=>1]);
        if(empty($findCommentInfo)){
            $Result['errCode'] = 'L10081';
            $Result['errMsg'] = '系统异常';
            writeLog(getWriteLogInfo('发布评论,查询最后一条数据异常',json_encode(['id'=>$commentModel->getLastInsID()]),$commentModel->getLastSql()),$this->log_level);
            return $Result;
        }

        if($addComment && $saveHomeCommentNum && $findCommentInfo){
            Db::commit();
        }

        $commentInfoArray = $findCommentInfo->toArray(); //转换为数组

        // 获取用户信息
        $event = new UserEvent();
        $userData = $event->setData(['uid'=>[$commentInfoArray['uid']]])->getAllUserList();
        $commentInfoArray['name'] = $userData[$commentInfoArray['uid']]['name'];
        $commentInfoArray['user_url'] = $userData[$commentInfoArray['uid']]['url'];
        $Result['data']= $commentInfoArray;
        return $Result;
    }

    // 处理活动评论
    protected function _getHandleActivityInfo(){
        $Result = ['errCode' => '200', 'errMsg'  => '', 'data' => []];
        Db::startTrans(); //开启事务
        // 验证动态是否存在
        $activityModel = new ActivityDetail();
        $findInfo = $activityModel->getOneActivityDetailInfo(['id'=>$this->data['param']['did'],'status'=>1]);
        if(empty($findInfo)){
            $Result['errCode'] = 'L10077';
            $Result['errMsg'] = '抱歉,系统异常,未查询到活动详情';
            writeLog((getWriteLogInfo('发布评论,验证动态异常',json_encode($this->data),$activityModel->getLastSql())),$this->log_level);
            return $Result;
        }
        // 更新成功后更新首页数据
        $saveHomeCommentNum = $activityModel->where(['id'=>$this->data['param']['did'],'status'=>1])->setInc('comments','1');
        if(!$saveHomeCommentNum){
            $Result['errCode'] = 'L10082';
            $Result['errMsg'] = '错误码[L10082]';
            writeLog(getWriteLogInfo('发布评论,更新comment数据异常','comment setInc 1 failed',$activityModel->getLastSql()),$this->log_level);
            Db::rollback();
            return $Result;
        }
        // 储存数据
        $commentModel = new DynamicComment();
        $addComment = $commentModel->addDynamicComment($this->_getAddCommentData());
        if(!$addComment){
            $Result['errCode'] = 'L10078';
            $Result['errMsg'] = '错误码[L10072]';
            writeLog(getWriteLogInfo('发布评论,储存数据异常',json_encode($this->_getAddCommentData()),$commentModel->getLastSql()),$this->log_level);
            Db::rollback();
            return $Result;
        }


        // 查询更新的数据
        $findCommentInfo = $commentModel->findDynamicCommentInfo(['id'=>$commentModel->getLastInsID(),'status'=>1]);
        if(empty($findCommentInfo)){
            $Result['errCode'] = 'L10081';
            $Result['errMsg'] = '系统异常';
            writeLog(getWriteLogInfo('发布评论,查询最后一条数据异常',json_encode(['id'=>$commentModel->getLastInsID()]),$commentModel->getLastSql()),$this->log_level);
            return $Result;
        }

        if($addComment && $saveHomeCommentNum && $findCommentInfo){
            Db::commit();
        }

        $commentInfoArray = $findCommentInfo->toArray(); //转换为数组

        // 获取用户信息
        $event = new UserEvent();
        $userData = $event->setData(['uid'=>[$commentInfoArray['uid']]])->getAllUserList();
        $commentInfoArray['name'] = $userData[$commentInfoArray['uid']]['name'];
        $commentInfoArray['user_url'] = $userData[$commentInfoArray['uid']]['url'];
        $Result['data']= $commentInfoArray;
        return $Result;
    }

    // 处理动态点赞操作
    public function _getHandleDynamicLikeInfo(){
        $Result = ['errCode' => '200', 'errMsg'  => '', 'data' => []];
        Db::startTrans(); //开启事务
        $dynamicModel = new Dynamic();
        $dynamicLikeModel = new DynamicLike();
        // 验证动态信息
        $findInfo = $dynamicModel->findArticle(['id'=>$this->data['param']['did'],'status'=>1]);
        if(empty($findInfo)){
            $Result['errCode'] = 'L10086';
            $Result['errMsg'] = '抱歉,系统异常,未查询到此动态详情';
            writeLog(getWriteLogInfo('点赞,验证动态异常',json_encode(['id'=>$this->data['param']['did']]),$dynamicModel->getLastSql()),$this->log_level);
            return $Result;
        }

        // 更新主表数据
        $upDynamic = $dynamicModel->where(['id'=>$this->data['param']['did'],'status'=>1])->setInc('likes','1');
        if(!$upDynamic){
            $Result['errCode'] = 'L10087';
            $Result['errMsg'] = '抱歉,系统异常,未查询到此动态详情';
            writeLog(getWriteLogInfo('点赞,更新主表动态数据异常',json_encode(['id'=>$this->data['param']['did']]),$dynamicModel->getLastSql()),$this->log_level);
            Db::rollback();
            return $Result;
        }

        // 新增详情记录
        $addDynamicLike = $dynamicLikeModel->addDynamicLikes($this->_getAddDynamicLike());
        if(!$addDynamicLike){
            $Result['errCode'] = 'L10088';
            $Result['errMsg'] = '抱歉,系统异常,未查询到此动态详情';
            writeLog(getWriteLogInfo('点赞,新增详情数据异常',json_encode($this->_getAddDynamicLike()),$dynamicLikeModel->getLastSql()),$this->log_level);
            Db::rollback();
            return $Result;
        }
        if($upDynamic && $addDynamicLike){
            writeLog('执行开始');
            Db::commit();
            writeLog('执行开始');
            return $Result;
        }
    }
    // 处理活动点赞操作
    public function _getHandleActivityLikeInfo(){
        $Result = ['errCode' => '200', 'errMsg'  => '', 'data' => []];
        Db::startTrans(); //开启事务
        $activityModel = new ActivityDetail();
        $dynamicLikeModel = new DynamicLike();
        // 验证动态信息
        $findInfo = $activityModel->getOneActivityDetailInfo(['id'=>$this->data['param']['did'],'status'=>1]);
        if(empty($findInfo)){
            $Result['errCode'] = 'L10086';
            $Result['errMsg'] = '抱歉,系统异常,未查询到此动态详情';
            writeLog(getWriteLogInfo('点赞,验证动态异常',json_encode(['id'=>$this->data['param']['did']]),$activityModel->getLastSql()),$this->log_level);
            return $Result;
        }

        // 更新主表数据
        $upActivityDetail = $activityModel->where(['id'=>$this->data['param']['did'],'status'=>1])->setInc('likes','1');
        if(!$upActivityDetail){
            $Result['errCode'] = 'L10087';
            $Result['errMsg'] = '抱歉,系统异常,未查询到此动态详情';
            writeLog(getWriteLogInfo('点赞,更新主表动态数据异常',json_encode(['id'=>$this->data['param']['did']]),$activityModel->getLastSql()),$this->log_level);
            Db::rollback();
            return $Result;
        }

        // 新增详情记录
        $addDynamicLike = $dynamicLikeModel->addDynamicLikes($this->_getAddDynamicLike());
        if(!$addDynamicLike){
            $Result['errCode'] = 'L10088';
            $Result['errMsg'] = '抱歉,系统异常,未查询到此动态详情';
            writeLog(getWriteLogInfo('点赞,新增详情数据异常',json_encode($this->_getAddDynamicLike()),$dynamicLikeModel->getLastSql()),$this->log_level);
            Db::rollback();
            return $Result;
        }
        if($upActivityDetail && $addDynamicLike){
            writeLog('执行开始');
            Db::commit();
            writeLog('执行开始');
            return $Result;
        }
    }

    protected function _getAddQaData(){
        return $data = [
            'uid'       => $this->data['param']['uid'],
            'title'     => $this->data['param']['title'],
            'pic_url'   => isset($this->data['param']['upload']) ? $this->data['param']['upload'] : '' ,
            'pet_type'  => $this->data['param']['pet_type'],
            'QA_type'   => $this->data['param']['QA_type'],
            'status'    => 1,
            'created_at'=> date('Y-m-d H:i:s'),
        ];
    }

    protected function _getAddArticleData(){
        return $data = [
            'uid'       => $this->data['param']['uid'],
            'title'     => $this->data['param']['title'],
            'cover'     => isset($this->data['param']['cover']) ? $this->data['param']['cover'] : '',
            'content'   => $this->data['param']['content'],
            'status'    => 1,
            'created_at'=> date('Y-m-d H:i:s'),
        ];
    }

    protected function _getAddCommentData(){
        return $data = [
            'uid'       => $this->data['param']['uid'],
            'did'       => $this->data['param']['did'],
            'content'   => $this->data['param']['content'],
            'status'    => 1,
            'created_at'=> date('Y-m-d H:i:s'),
            'action'    => $this->data['param']['action'],
        ];
    }

    protected function _getAddDynamicLike(){
        return $data = [
            'uid'       => $this->data['param']['uid'],
            'did'       => $this->data['param']['did'],
            'num'       => 1,
            'status'    => 1,
            'created_at'=> date('Y-m-d H:i:s'),
            'action'    => $this->data['param']['action'],
        ];
    }
}
