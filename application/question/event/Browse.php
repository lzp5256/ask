<?php
namespace app\question\event;

use app\base\controller\Base;
use app\question\model\AskQuestion;

class Browse extends Base
{
    public function addBrowse(){
        $res = [
            'errCode' => '200',
            'errMsg'  => '添加成功',
            'data'    => [],
        ];
        $model = new AskQuestion();
        try{
            if(!($u_res = $model->setUpdate(['state'=>1,'qid'=>(int)$this->data['param_list']['qid']],'Inc','browse'))){
                return $this->setReturnMsg('00006');
            }
            return $res;
        }catch (Exception $e){
            return $this->setReturnMsg('00001');
        }
    }
}