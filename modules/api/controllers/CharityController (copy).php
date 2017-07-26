<?php

namespace app\modules\api\controllers;
//namespace app\controllers;
use Yii;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

use app\models\User;

use app\models\Charity;
use app\models\Country;
use app\models\State;
use app\models\Purpose;
use app\models\CharityPurpose;
use app\models\City;

class CharityController extends \yii\web\Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {
        $u = 0;
        if(isset($_REQUEST['userid']))
        $u=$_REQUEST['userid'];
        $secretkey = Yii::$app->params['encryption_key'];
        $user = hash_hmac('sha256',$u, $secretkey);
        echo $user;
    }

    private function setHeader($status)
    {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . Yii::$app->params['response_text'][$status];
        $content_type="application/json; charset=utf-8";
        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "Five Claps");
    }

     public function actionCreateFolder() {
        
        $this->layout = false;
        if( isset($_POST['folder_name']) && $_POST['folder_name'] != null &&
           isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null ) {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            //$data1 = User::find()->where(['username'=>$_POST['user_name'],'user_type'=>'U','is_deleted'=>'N'])->one();
            $data = Folder::find()->where(['name'=>$_POST['folder_name'],'user_id'=>$_POST['user_id'],'is_deleted'=>'N'])->one();
            if($data == array()) {
                $data = new Folder();

                $data->name = $_POST['folder_name'];
                $data->user_id = $_POST['user_id'];
                $data->i_date = date('Y-m-d');
                $data->u_date = date('Y-m-d');
                $data->is_deleted = 'N';

                if(isset($_POST['device_type']) && $_POST['device_type'] != '')
                $data->device_type = $_POST['device_type'];

                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                $data->device_id = $_POST['device_id'];

                if(isset($_POST['lang_pref']) && $_POST['lang_pref'] != '')
                $data->lang_pref = $_POST['lang_pref'];

                if($data->save(false)) {

                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['message'] = Yii::$app->params['folder_create_success_msg'];
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];

                    $data = Folder::find()->where(['id'=>$data->id])->one();

                    $result['folder_info']['folder_id'] = $data->id;
                    $result['folder_info']['folder_name'] = $data->name;
                    $result['folder_info']['user_id'] = $data->user_id;
                    $result["folder_info"]['count'] = 0;
                    $result["folder_info"]["lists"] = array();
                    $resultstring =  json_encode($result);
                    $resultstring = str_replace("null",'""',$resultstring);
                    echo $resultstring ;
                    die;
                } else {
                    $this->setHeader(602);
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['error_in_save']));
                    die;
                }
            } else {
                if($data != array()) {
                    $this->setHeader(404);
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['folder_exist_already']));
                    die;
                }
            }
        }
        else {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }


    public function actionEditDeleteFolder() {

        $this->layout = false;
        if(isset($_POST['folder_name']) && $_POST['folder_name'] != null &&
           isset($_POST['folder_id']) && $_POST['folder_id'] != null &&
           isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['should_delete']) && $_POST['should_delete'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null) {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            $data = Folder::find()->where(['id'=>$_POST['folder_id'],'user_id'=>$_POST['user_id'],'is_deleted'=>'N'])->one();
            if(count($data)>0) {
                $data->id = $_POST['folder_id'].'-copy';
                $data->user_id = $_POST['user_id'];
                $data->name = $_POST['folder_name'];
                $data->u_date = date('Y-m-d');
                if($_POST['should_delete'] == 'true')
                $data->is_deleted = 'Y';

                if(isset($_POST['device_type']) && $_POST['device_type'] != '')
                $data->device_type = $_POST['device_type'];

                if(isset($_POST['device_id']) && $_POST['device_id'] != '')
                $data->device_id = $_POST['device_id'];

                if(isset($_POST['lang_pref']) && $_POST['lang_pref'] != '')
                $data->lang_pref = $_POST['lang_pref'];

                if($data->save(false)) {

                    $this->setHeader(200);
                    $result['code'] = 200;
                    if($data->is_deleted == 'Y')
                    $result['message'] = Yii::$app->params['folder_delete_success_msg'];
                    else
                    $result['message'] = Yii::$app->params['folder_update_success_msg'];
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];

                    $data = Folder::find()->where(['id'=>$data->id])->one();

                    $result['folder_info']['folder_id'] = $data->id;
                    $result['folder_info']['folder_name'] = $data->name;
                    $result['folder_info']['user_id'] = $data->user_id;
                    $folder_task_list = Folderlist::find()->where(["folder_id"=>$data->id])->orderby("id desc")->all();
                    $result["folder_info"]['count'] = count($folder_task_list);

                    $list_key = 0;
                    if(isset($folder_task_list) && count($folder_task_list)>0){
                        foreach ($folder_task_list as $list)
                            if(isset($list->id)) {
                                $result["folder_info"]['list'][$list_key]["list_id"] = $list->id;
                                $result["folder_info"]['list'][$list_key]["list_name"] = $list->list_name;
                                $list_key++;
                            }
                    } else {
                        $result["folder_info"]["lists"]=array();
                    }
                    $resultstring =  json_encode($result);
                    $resultstring = str_replace("null",'""',$resultstring);
                    echo $resultstring ;
                    die;
                }
                else {
                    $this->setHeader(602);
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['error_in_save']));
                    die;
                }
            } else {
                $this->setHeader(404);
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['folder_not_exist']));
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

    /*
     *  API Name : feature product showing the detail of the product which is mostly viewd
     *  Created By : Indra
     *  Creation Date : 03-11-2016
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionListOfCharity() { 
        
        
      
        if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null){
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
           
          

            $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
            $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:10;
       //      echo "<pre>";
       //print_r($limit);
       //exit;
            $charity_data = array();
            $query = new \yii\db\Query;
      
            
            if(isset($_POST['purpose_id']) && $_POST['purpose_id'] != '' || isset($_POST['country_id']) && $_POST['country_id'] != '' || isset($_POST['state_id']) && $_POST['state_id'] != '' || isset($_POST['city_id']) && $_POST['city_id'] != '')
            {
            if(isset($_POST['purpose_id']) && $_POST['purpose_id'] != '')
            {
              $charity_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","purpose_id"=>$_POST['purpose_id']])
                                            ->orderby("id DESC")->limit($limit)->offset($offset)->all();
                                            
                  
            }                         
            if(isset($_POST['country_id']) && $_POST['country_id'] != '')
            {
                 $charity_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","country_id"=>$_POST['country_id']])
                                            ->orderby("id DESC")->limit($limit)->offset($offset)->all();
                                            
               //echo "<pre>";
               //print_r($charity_data);
               //exit;
            }
            if(isset($_POST['state_id']) && $_POST['state_id'] != '')
            {
                //echo "sdf";
                //exit;
                 $charity_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","state_id"=>$_POST['state_id']])
                                            ->orderby("id DESC")->limit($limit)->offset($offset)->all();
            }
             if(isset($_POST['city_id']) && $_POST['city_id'] != '')
            {
                 $charity_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","city_id"=>$_POST['city_id']])
                                            ->orderby("id DESC")->limit($limit)->offset($offset)->all();
            }
            
            }
            else{
                
                //$charity_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N"])
                //                            ->orderby("id DESC")->limit($limit)->offset($offset)->all();
                                            
                                            $query->select("charity.*,countries.name as country_name,states.name as state_name,cities.name as city_name")
              ->from('charity')
              ->leftJoin('countries','countries.id = charity.country_id')
              ->leftJoin('states','states.id = charity.state_id')
              ->leftJoin('cities','cities.id = charity.city_id')
              ->where(['charity.is_deleted'=>'N'])
              //->andWhere(['user_master.is_deleted'=>'N'])
              
              ->orderBy('charity.id desc')
              ->limit($limit)
              ->offset($offset);
      $command = $query->createCommand();
      $charity_data  = $command->queryAll();
                  echo "<pre>";
                  print_r($charity_data);
                  exit;
                                            
                                            // var_dump($charity_data->prepare(Yii::$app->db->queryBuilder)->createCommand()->rawSql);
                   //exit;
            }
            $main_key = 0;
            $main_key1= 0;
            
             //echo "<pre>";
             //   print_r($charity_data);
             //   exit;
            $result = array();
            if(isset($charity_data) && count($charity_data)>0) {
               
                foreach($charity_data as $charity) {
                    $result["charity_list"][$main_key]["charity_id"] = (isset($charity->id) && $charity->id!=null)?$charity->id:"";
                    $result["charity_list"][$main_key]["charity_name"] = (isset($charity->name) && $charity->name!=null)?$charity->name:"";
                    $result["charity_list"][$main_key]["charity_address"] = (isset($charity->address) && $charity->address!=null)?$charity->address:"";
                    
                    $result["charity_list"][$main_key]["charity_logo"] = (isset($charity->logo) && $charity->logo!=null)?Url::to('@web/img/logos/'.$charity->logo,true):"";
                   
                    $main_key++;
                }
               $result['is_last'] = 'Y';
                if( $main_key == $limit)
                {
                    unset($result['charity_list'][ $main_key-1]);
                    $result['is_last'] = "N";
                }
                
                $purpose_data = Purpose::find()->where(['is_deleted'=>'N','is_active'=>'Y'])->all();
                $country_data = Country::find()->all();
                //echo "<pre>";
                //print_r($purpose_data);
                //exit;
                if($offset == 0)
                {
                $result["purpose_list"]= array();
                if(isset($purpose_data) && count($purpose_data)>0)
                {
                    foreach($purpose_data as $purpose)
                    {
                        $result["purpose_list"][$main_key1]["purpose_id"] = (isset($purpose->id) && $purpose->id!=null)?$purpose->id:"";
                        $result["purpose_list"][$main_key1]["purpose"] = (isset($purpose->purpose) && $purpose->purpose!=null)?$purpose->purpose:"";
                        $main_key1++;
                    }
                }
                
                $result["country_list"]= array();
                $main_key2 = 0;
                
                if(isset($country_data) && count($country_data)>0)
                {
                    foreach($country_data as $country)
                    {
                        $result["country_list"][$main_key2]["country_id"] = (isset($country->id) && $country->id!=null)?$country->id:"";
                        $result["country_list"][$main_key2]["country_name"] = (isset($country->name) && $country->name!=null)?$country->name:"";
                        $main_key2++;
                    }
                 }
                }
                //$result['is_last'] = "Y";
                //if($main_key2 == $limit)
                //{
                //    $result['is_last'] = 'N';
                //    unset($result['country_list'][$main_key2-1]);
                //}       
                 
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
                $resultstring = str_replace(null,'""',$resultstring);
                echo $resultstring ;
                die;
            } else {
                $this->setHeader(404);
                echo json_encode(array('code'=>404,'status'=>'error','message'=>'No Charity Found'));
                die;
            }
        } else {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
            die;
        }
    }
    
    //public function actionCountryList() {
    //    
    //       
    //   //echo "<pre>";
    //   //print_r($_POST);
    //   //exit;
    //    if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
    //       isset($_POST['purpose_id']) && $_POST['purpose_id'] != null &&
    //      
    //       isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null){
    //        $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
    //
    //        $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
    //        $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:10;
    //        $charity_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","purpose_id"=>$_POST['purpose_id']])
    //                                        ->orderby("id DESC")->limit($limit+1)->offset($offset)->all();
    //        $main_key = 0;
    //        $result = array();
    //        if(isset($charity_data) && count($charity_data)>0) {
    //           
    //            foreach($charity_data as $charity) {
    //                $result["charity_list"][$main_key]["charity_id"] = (isset($charity->id) && $charity->id!=null)?$charity->id:"";
    //                $result["charity_list"][$main_key]["charity_name"] = (isset($charity->name) && $charity->name!=null)?$charity->name:"";
    //                $result["charity_list"][$main_key]["charity_address"] = (isset($charity->address) && $charity->address!=null)?$charity->address:"";
    //                $result["charity_list"][$main_key]["charity_purpose_id"] = (isset($charity->purpose_id) && $charity->purpose_id!=null)?$charity->purpose_id:"";
    //                $result["charity_list"][$main_key]["charity_logo"] = (isset($charity->logo) && $charity->logo!=null)?$charity->logo:"";
    //                $result["charity_list"][$main_key]["charity_country_id"] = (isset($charity->country_id) && $charity->country_id!=null)?$charity->country_id:"";
    //                $result["charity_list"][$main_key]["charity_state_id"] = (isset($charity->state_id) && $charity->state_id!=null)?$charity->state_id:"";
    //                $result["charity_list"][$main_key]["charity_city_id"] = (isset($charity->city_id) && $charity->city_id!=null)?$charity->city_id:"";
    //                $main_key++;
    //            }
    //            $result['is_last'] = "Y";
    //            if( $main_key > $limit)
    //            {
    //                unset($result['charity_list'][ $main_key-1]);
    //                $result['is_last'] = "N";
    //            }
    //            $this->setHeader(200);
    //            $result['code'] = 200;
    //            $result['status'] = Yii::$app->params['response_text'][$result['code']];
    //            $resultstring = json_encode($result);
    //            $resultstring = str_replace(null,'""',$resultstring);
    //            echo $resultstring ;
    //            die;
    //        } else {
    //            $this->setHeader(404);
    //            echo json_encode(array('code'=>404,'status'=>'error','message'=>'No Charity Found'));
    //            die;
    //        }
    //    } else {
    //        $this->setHeader(400);
    //        echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
    //        die;
    //    }
    //}
    
    public function actionCharityDetails()
    {   
        
       //echo "<pre>";
       //print_r($_POST);
       //exit;
        if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['charity_id']) && $_POST['charity_id'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null){
            
            if(isset($_POST['user_id']))
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            else
            $check_user = Yii::$app->mycomponent->validate_user(0,$_POST['encrypted_data']);
           
            $charity_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","id"=>$_POST['charity_id']])->one();
          
            $main_key = 0;
            $result = array();
            if(isset($charity_data) && count($charity_data)>0) {
               
                
                    $result["charity_id"] = (isset($charity_data->id) && $charity_data->id!=null)?$charity_data->id:"";
                    $result["charity_name"] = (isset($charity_data->name) && $charity_data->name!=null)?$charity_data->name:"";
                    $result["charity_address"] = (isset($charity_data->address) && $charity_data->address!=null)?$charity_data->address:"";
                    $result["charity_logo"] = (isset($charity_data->logo) && $charity_data->logo!=null)?Url::to('@web/img/logos/'.$charity_data->logo,true):"";
                    $result["description"] = $charity_data->description;
                   // $result["charity_purpose_id"] = (isset($charity_data->purpose_id) && $charity_data->purpose_id!=null)?$charity_data->purpose_id:"";
                    $purpose_ids = (isset($charity_data->purpose_id) && $charity_data->purpose_id!=null)?$charity_data->purpose_id:"";
                    
                    if(isset($charity_data->id) && $charity_data->id != '')
                    {
                        $purpose_ids = Purpose :: find()->where(['charity_id'=>$charity_data->id])->all();
                       $list_key=0;
                       
                       if(isset($purpose_ids) && !empty($purpose_ids))
                        { 
                        foreach($purpose_ids as $purpose)
                        {
                            //$purpose_list = Purpose :: find()->where(["id"=>$purpose->purpose_id])->one();
                            
                            
                         
                         //$result["purpose_list"][$list_key]["purpose"]
                         
                        
                            $result["purpose_list"][$list_key]["purpose"] = $purpose->purpose;
                           $result["purpose_list"][$list_key]["motto"] = $purpose->motto;
                          
                           $list_key++;
                          
                       
                           
                          }
                        //echo "<pre>";
                        //print_r($result);
                        }else{
                                $result["purpose_list"] = array();
                        }
                        
                        //exit;
                       
                    }
                    //$result["charity_list"]["charity_country_id"] = (isset($charity_data->country_id) && $charity_data->country_id!=null)?$charity_data->country_id:"";
                    //$result["charity_list"]["charity_state_id"] = (isset($charity_data->state_id) && $charity_data->state_id!=null)?$charity_data->state_id:"";
                    //$result["charity_list"]["charity_city_id"] = (isset($charity_data->city_id) && $charity_data->city_id!=null)?$charity_data->city_id:"";
                   
              
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
                $resultstring = str_replace(null,'""',$resultstring);
                echo $resultstring ;
                die;
            } else {
                $this->setHeader(404);
                echo json_encode(array('code'=>404,'status'=>'error','message'=>'Charity Not Found'));
                die;
            }
        } else {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
            die;
        }
        
    }
    
     public function actionCountryList() {
        if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
                  isset($_POST['purpose_id']) && $_POST['purpose_id'] != null &&
        isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null){
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);

            $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
            $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:10;
            $country_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","purpose_id"=>$_POST['purpose_id']])->orderby("id desc")->limit($limit+1)->offset($offset)->all();
            $main_key = 0;
            $result = array();
            if(isset($country_data) && count($country_data)>0) {
               
                foreach($country_data as $country) {
                    //$result["country_list"][$main_key]["country_id"] = (isset($country->id) && $country->id!=null)?$country->id:"";
                    //$result["country_list"][$main_key]["country_name"] = (isset($country->name) && $country->name!=null)?$country->name:"";

                    $country_list = Country::find()->where(["id"=>$country->id])->orderby("id desc")->all();
                    $list_key = 0;
                    //$result["country_list"]["count"] = count($country_list);
                    $result["country_list"][$main_key]= array();
                    if(isset($country_list) && count($country_list)>0) {
                        foreach($country_list as $list) {
                            if(isset($list->id)){
                                $result["country_list"][$main_key][$list_key]["country_id"] = $list->id;
                                $result["country_list"][$main_key][$list_key]["country_name"] = $list->name;
                                $list_key++;
                            }
                        }
                    } else {
                        $result["country_list"][$main_key]=array();
                    }
                    $main_key++;
                }
                $result['is_last'] = "Y";
                if( $main_key == $limit)
                {
                    unset($result['country_list'][ $main_key-1]);
                    $result['is_last'] = "N";
                }
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
                $resultstring = str_replace(null,'""',$resultstring);
                echo $resultstring ;
                die;
            } else {
                $this->setHeader(404);
                echo json_encode(array('code'=>404,'status'=>'error','message'=>'No Country Exist'));
                die;
            }
        } else {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
            die;
        }
    }
    
    public function actionStateList() {
        if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
                 
                  isset($_POST['country_id']) && $_POST['country_id'] != null &&
        isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null){
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);

            $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
            $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:10;
            if(( isset($_POST['purpose_id']) && $_POST['purpose_id'] != null))
            $state_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","purpose_id"=>$_POST['purpose_id'],"country_id"=>$_POST['country_id']])->orderby("id desc")->limit($limit+1)->offset($offset)->all();
            else
            $state_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","country_id"=>$_POST['country_id']])->orderby("id desc")->limit($limit+1)->offset($offset)->all();
            $main_key = 0;
            $result = array();
                //echo "<pre>";
                //print_r($state_data);
                //exit;
            if(isset($state_data) && count($state_data)>0) {
               
                foreach($state_data as $state) {
                    //$result["country_list"][$main_key]["country_id"] = (isset($country->id) && $country->id!=null)?$country->id:"";
                    //$result["country_list"][$main_key]["country_name"] = (isset($country->name) && $country->name!=null)?$country->name:"";

                    $state_list = State::find()->where(["country_id"=>$state->country_id])->orderby("id asc")->all();
                    $list_key = 0;
                    //$result["country_list"]["count"] = count($country_list);
                    //$result["state_list"][$main_key]= array();
                    if(isset($state_list) && count($state_list)>0) {
                        foreach($state_list as $list) {
                            if(isset($list->id)){
                                $result["state_list"][$list_key]["state_id"] = $list->id;
                                $result["state_list"][$list_key]["state_name"] = $list->name;
                                $list_key++;
                            }
                        }
                    } else {
                        $result["state_list"][$main_key]=array();
                    }
                    $main_key++;
                }
                $result['is_last'] = "Y";
                if( $main_key == $limit)
                {
                    unset($result['state_list'][ $main_key-1]);
                    $result['is_last'] = "N";
                }
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
                $resultstring = str_replace(null,'""',$resultstring);
                echo $resultstring ;
                die;
            } else {
                $this->setHeader(404);
                echo json_encode(array('code'=>404,'status'=>'error','message'=>'No State Found'));
                die;
            }
        } else {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
            die;
        }
    }
    public function actionCityList() {
        
        //echo "<pre>";
        //print_r($_POST);
        //exit;
        if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
                 
                  isset($_POST['state_id']) && $_POST['state_id'] != null &&
        isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null){
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);

            $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
            $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:10;
            $city_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","state_id"=>$_POST['state_id']])->orderby("id desc")->limit($limit+1)->offset($offset)->all();
            $main_key = 0;
            $result = array();
            if(isset($city_data) && count($city_data)>0) {
               
                foreach($city_data as $city) {
                    //$result["country_list"][$main_key]["country_id"] = (isset($country->id) && $country->id!=null)?$country->id:"";
                    //$result["country_list"][$main_key]["country_name"] = (isset($country->name) && $country->name!=null)?$country->name:"";

                    $city_list = City::find()->where(["state_id"=>$city->state_id])->orderby("id asc")->all();
                    $list_key = 0;
                    //$result["country_list"]["count"] = count($country_list);
                    //$result["city_list"][$main_key]= array();
                    if(isset($city_list) && count($city_list)>0) {
                        foreach($city_list as $list) {
                            if(isset($list->id)){
                                $result["city_list"][$list_key]["city_id"] = $list->id;
                                $result["city_list"][$list_key]["city_name"] = $list->name;
                                $list_key++;
                            }
                        }
                    } else {
                        $result["city_list"]=array();
                    }
                    $main_key++;
                }
                $result['is_last'] = "Y";
                if( $main_key == $limit)
                {
                    unset($result['city_list'][ $main_key-1]);
                    $result['is_last'] = "N";
                }
                $this->setHeader(200);
                $result['code'] = 200;
                $result['status'] = Yii::$app->params['response_text'][$result['code']];
                $resultstring = json_encode($result);
                $resultstring = str_replace(null,'""',$resultstring);
                echo $resultstring ;
                die;
            } else {
                $this->setHeader(404);
                echo json_encode(array('code'=>404,'status'=>'error','message'=>'No City Found'));
                die;
            }
        } else {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
            die;
        }
    }
    
     public function actionCms()
      {
        
        

        $this->layout = false;

        if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['type']) && $_POST['type'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {

            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            //check if user exist or not
            $data = User::find()->where('id= :email',[':email'=>$_POST['user_id']])->andwhere(['user_type'=>'u','is_deleted'=>'n'])->one();
            if($data)
            {
              header("Content-Type: application/json");
              $type=$_POST['type'];
              //if faq then give html file
              if($type=='help')
              {
                  $result['code'] =200;
                  $result['status'] ="Success";
                  $result['link'] =Url::to('@web/site/help',true);
                  $resultstring = json_encode($result,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                  //$resultstring = str_replace("null",'""',$resultstring);
                  echo $resultstring;die;
              }
              elseif($type=='term')
              {
                  $result['code'] =200;
                  $result['status'] ="Success";
                  $result['link'] =Url::to('@web/site/term',true);
                  $resultstring = json_encode($result,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                  //$resultstring = str_replace("null",'""',$resultstring);
                  echo $resultstring;die;
              }
              elseif($type=='faq')
              {
                  $result['code'] =200;
                  $result['status'] ="Success";
                  $result['link'] =Url::to('@web/site/faq',true);
                  $resultstring = json_encode($result,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                  //$resultstring = str_replace("null",'""',$resultstring);
                  echo $resultstring;die;
              }
              elseif($type=='privacypolicy')
              {
                  $result['code'] =200;
                  $result['status'] ="Success";
                  $result['link'] =Url::to('@web/site/privacypolicy',true);
                  $resultstring = json_encode($result,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                  //$resultstring = str_replace("null",'""',$resultstring);
                  echo $resultstring;die;
              }
              elseif($type=='about')
              {
                  $result['code'] =200;
                  $result['status'] ="Success";
                  $result['link'] =Url::to('@web/site/about',true);
                  $resultstring = json_encode($result,JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                  //$resultstring = str_replace("null",'""',$resultstring);
                  echo $resultstring;die;
              }
              else
              {
                  $this->setHeader(601);
                  echo json_encode(array('code'=>601,'status'=>'error','message'=>utf8_encode(Yii::$app->params['no_data_found'])));
                  die;
              }

            }
            else
            {
                $this->setHeader(401);
                echo json_encode(array('code'=>401,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                die;
            }

        }
        else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
            die;
        }
      }


   
   
   
}
 ?>
