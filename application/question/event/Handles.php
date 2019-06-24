<?php
namespace app\question\event;

use app\base\controller\Base;
use app\question\model\AskQuestion;

class Handles extends Base
{
    public function handleQrRes(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '发布成功',
            'data'    => [],
        ];
        $model = new AskQuestion();
        try{
           $addData = $this->_getAddData();
           if(($add = $model->add($addData)) && $add == 0){
               return $this->setReturnMsg('00003');
           }
           return $res;
        }catch (Exception $e){
            return $this->setReturnMsg('00001');
        }
    }

    protected function _getAddData(){
        return [
            'uid'       => (int)$this->data['param_list']['uid'],
            'title'     => (string)$this->data['param_list']['title'],
            'describe'  => (string)$this->data['param_list']['describe'],
            'show'      => 1,
            'state'     => 1,
            'created_at'=> date('Y-m-d H:i:s')
        ];
    }
}
