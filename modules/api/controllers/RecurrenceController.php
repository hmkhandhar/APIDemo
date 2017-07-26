<?php

namespace app\modules\api\controllers;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\models\User;
use app\models\Post;
use app\models\Follow;
use app\models\Notification;
use app\models\Images;
use app\models\Blockuser;
use app\models\Category;

use app\models\Charity;
use app\models\Country;
use app\models\State;
use app\models\Purpose;
use app\models\RecurrenceMaster;


class RecurrenceController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;
    private function setHeader($status)
    {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . Yii::$app->params['response_text'][$status];
        $content_type="application/json; charset=utf-8";
        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "Donation");
    }


    
     public function actionHistorylist()
    {
         if(isset($_REQUEST['userid']) && $_REQUEST['userid'] != null &&
           isset($_REQUEST['encrypted_data']) && $_REQUEST['encrypted_data'] != null 
           )

        {
            $check_user = Yii::$app->mycomponent->validate_user($_REQUEST['userid'],$_REQUEST['encrypted_data']);
            $data = User::find()->where(['id'=>$_POST['userid'],'user_type'=>'U','is_deleted'=>'N'])->one();
    
            if($data != array())
            {
                if($data->lang_pref != '' && $data->lang_pref == 'A')
                Yii::$app->language = "ar";
            $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
            $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:20;
            $limit = $limit+1;
            $history_array = array();
            
                $query = new \yii\db\Query;
                $query->select("r.*,c.name,c.id as charity_id,c.address,c.logo,p.purpose")
              ->from('recurrence_master r')
              ->leftJoin('charity c','c.id = r.charityid')
              ->leftJoin('purpose p','p.id = r.purpose_id')
              
              //->where(['r.is_deleted'=>'N'])
              ->where(['c.is_deleted'=>'N'])
              ->andWhere(['p.is_deleted' => 'N'])
              ->andWhere(['user_id' => $_REQUEST['userid']])
              ->orderBy('r.id desc')
              ->limit($limit)
              ->offset($offset);
              $command = $query->createCommand();
              //(object)$history_array  = $command->queryAll();
              $history_array  = $command->queryAll();
              
                $result['is_last'] = 'Y';
                $result['history'] = array();
               
                
           if($history_array != array()){
              $i = 0;
             
              foreach($history_array as $each_history) {

                     $result['history'][$i]['subscription_id'] = (int)$each_history['id'];
                     $result['history'][$i]['charity_id'] = (int)$each_history['charity_id'];
                     $result['history'][$i]['name'] = $each_history['name'];
                     //$result['history'][$i]['purpose'] = $purpose!=array()?$purpose->purpose:'';
                     $result['history'][$i]['purpose'] = $each_history['purpose'];
                     //$result['history'][$i]['address'] = $charrity!=array()?$charrity->address:'';
                     $result['history'][$i]['address'] = $each_history['address'];
                     //$result['history'][$i]['image'] = $charrity!=array()?$charrity->full_image:'';
                     $result['history'][$i]['image'] = Url::to('@web/'.$each_history['logo'],true);
                     $result['history'][$i]['date'] = $each_history['i_date'];
                     $result['history'][$i]['time'] = (int)$each_history['end_date_timestamp'];
                     $result['history'][$i]['money'] = (int)$each_history['amount'];
                     $i++;

                }
                if($i == $limit){
                    unset($result['history'][$i-1]);
                    $result['is_last'] = 'N';
                    }
                    
                    $result['code'] = 200;
                    if(Yii::$app->language == "ar")
                    $result['message'] = Yii::t('app','Success');
                    else
                    $result['message'] = Yii::t('app','Success');
                    $resultstring =  json_encode($result);
                    $resultstring = str_replace("null",'""',$resultstring);
                    echo $resultstring ;
                    die;
            } else
        {
            $this->setHeader(400);
            if(Yii::$app->language == "ar")
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app','No History Found')));
            else
            echo json_encode(array('code'=>400,'status'=>'error','message'=>'No History Found'));
            die;
        }
         }else{
                $this->setHeader(400);
                if(Yii::$app->language == "ar")
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                else
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
                die;
        }    

        }
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }

    public function actionHistorydescription()
    {
        if(isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != null &&
          isset($_REQUEST['encrypted_data']) && $_REQUEST['encrypted_data'] != null &&
          isset($_REQUEST['history_id']) && $_REQUEST['history_id'] != null)
       {
           $check_user = Yii::$app->mycomponent->validate_user($_REQUEST['user_id'],$_REQUEST['encrypted_data']);
           $data = User::find()->where(['id'=>$_POST['user_id'],'user_type'=>'U','is_deleted'=>'N'])->one();
    
            if($data != array())
            {
                if($data->lang_pref != '' && $data->lang_pref == 'A')
                Yii::$app->language = "ar";

          $each_history = RecurrenceMaster::find()->where(['id'=>$_REQUEST['history_id']])->one();
          if($each_history!= array()){
                $charrity=$purpose=array();
                if($each_history->charityid != "")
                   $charrity = Charity::find()->where(["id"=>$each_history->charityid])->one();
               if($each_history->purpose_id != "")
                   $purpose = Purpose::find()->where(["id"=>$each_history->purpose_id])->one();

                $result['subscriptions']['subscription_id'] = $each_history->id;
                $result['subscriptions']['charity_id'] = $charrity->id;
                $result['subscriptions']['name'] = $charrity!=array()?$charrity->name:'';
                $result['subscriptions']['purpose_id'] = $purpose!=array()?$purpose->id:'';
                $result['subscriptions']['purpose'] = $purpose!=array()?$purpose->purpose:'';
                $result['subscriptions']['address'] = $charrity!=array()?$charrity->address:'';
                $result['subscriptions']['image'] = $charrity!=array()?$charrity->full_image:'';
                $result['subscriptions']['date'] = $each_history->i_date;
                $result['subscriptions']['time'] = (int)$each_history->end_date_timestamp	;
                $result['subscriptions']['money'] =$each_history->amount;
           }
           $result['code'] = 200;
           if(Yii::$app->language == "ar")
           $result['message'] = Yii::t('app','Success');
           else
           $result['message'] = Yii::t('app','Success');
           $resultstring =  json_encode($result);
           $resultstring = str_replace("null",'""',$resultstring);
           echo $resultstring ;
           die;
           
         }else{
                $this->setHeader(400);
                if(Yii::$app->language == "ar")
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                else
                echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
                die;
        }  
       }else
       {
           $this->setHeader(400);
           echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
           die;
       }
   }






}
