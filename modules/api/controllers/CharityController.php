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
use app\models\RecurrenceMaster;
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
            $user_data = User::find()->where(['is_deleted'=>'N','id'=>$_POST['user_id']])->one();
            if(!empty($user_data) && count($user_data)>0)
            {
                if(isset($user_data->lang_pref) && $user_data->lang_pref != '' && $user_data->lang_pref == "A")
                Yii::$app->language = "ar";
                $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
                $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:10;
          
                $charity_data = array();
                $query = new \yii\db\Query;
                
                if(isset($_POST['purpose_id']) && $_POST['purpose_id'] != '' || isset($_POST['country_id']) && $_POST['country_id'] != '' || isset($_POST['state_id']) && $_POST['state_id'] != '' || isset($_POST['city_id']) && $_POST['city_id'] != '')
                {
                if(isset($_POST['purpose_id']) && $_POST['purpose_id'] != '')
                {
                   
                  $query->select("c.*,t.name as country_name,s.name as state_name,e.name as city_name")
                  ->from('charity c')
                  ->leftJoin('countries t','t.id = c.country_id')
                  ->leftJoin('states s','s.id = c.state_id')
                  ->leftJoin('cities e','e.id = c.city_id')
                  ->where(['c.is_deleted'=>'N'])
                  ->andWhere(['c.is_active'=>'Y'])
                  ->andWhere(['c.purpose_id'=>$_POST['purpose_id']])
                  ->orderBy('c.id desc')
                  ->limit($limit+1)
                  ->offset($offset);
                  $command = $query->createCommand();
                  $charity_data  = $command->queryAll(); 
                }                         
                if(isset($_POST['country_id']) && $_POST['country_id'] != '')
                {
                   $query = new \yii\db\Query;                          
                   $query->select("c.*,t.name as country_name,s.name as state_name,e.name as city_name")
                  ->from('charity c')
                  ->leftJoin('countries t','t.id = c.country_id')
                  ->leftJoin('states s','s.id = c.state_id')
                  ->leftJoin('cities e','e.id = c.city_id')
                  ->where(['c.is_deleted'=>'N'])
                  ->andWhere(['c.is_active'=>'Y'])
                  ->andWhere(['c.country_id'=>$_POST['country_id']])
                  ->orderBy('c.id desc')
                  ->limit($limit+1)
                  ->offset($offset);
         
                  $command = $query->createCommand();
                  $charity_data  = $command->queryAll(); 
                }
                if(isset($_POST['state_id']) && $_POST['state_id'] != '')
                {
                   $query = new \yii\db\Query;                          
                   $query->select("c.*,t.name as country_name,s.name as state_name,e.name as city_name")
                  ->from('charity c')
                  ->leftJoin('countries t','t.id = c.country_id')
                  ->leftJoin('states s','s.id = c.state_id')
                  ->leftJoin('cities e','e.id = c.city_id')
                  ->where(['c.is_deleted'=>'N'])
                  ->andWhere(['c.is_active'=>'Y'])
                  ->andWhere(['c.state_id'=>$_POST['state_id']])
                  ->orderBy('c.id desc')
                  ->limit($limit+1)
                  ->offset($offset);
                  
                  $command = $query->createCommand();
                  $charity_data  = $command->queryAll();          
                }
                 if(isset($_POST['city_id']) && $_POST['city_id'] != '')
                {                      
                   $query = new \yii\db\Query;                          
                   $query->select("c.*,t.name as country_name,s.name as state_name,e.name as city_name")
                  ->from('charity c')
                  ->leftJoin('countries t','t.id = c.country_id')
                  ->leftJoin('states s','s.id = c.state_id')
                  ->leftJoin('cities e','e.id = c.city_id')
                  ->where(['c.is_deleted'=>'N'])
                  ->andWhere(['c.is_active'=>'Y'])
                  ->andWhere(['c.city_id'=>$_POST['city_id']])
                  ->orderBy('c.id desc')
                  ->limit($limit+1)
                  ->offset($offset);
                  
                  $command = $query->createCommand();
                  $charity_data  = $command->queryAll();
                }
                
                }
                else{               
                  $query = new \yii\db\Query;                          
                   $query->select("c.*,t.name as country_name,s.name as state_name,e.name as city_name")
                  ->from('charity c')
                  ->leftJoin('countries t','t.id = c.country_id')
                  ->leftJoin('states s','s.id = c.state_id')
                  ->leftJoin('cities e','e.id = c.city_id')
                  ->where(['c.is_deleted'=>'N'])
                  ->andWhere(['c.is_active'=>'Y'])
                 
                  ->orderBy('c.id desc')
                  ->limit($limit+1)
                  ->offset($offset);
                  
                   $command = $query->createCommand();
                  $charity_data  = $command->queryAll();
                   
                }
                $main_key = 0;
                $main_key1= 0;
                
                $result = array();
                if(isset($charity_data) && count($charity_data)>0) {
                   
                    foreach($charity_data as $charity) {
                        $result["charity_list"][$main_key]["charity_id"] = (isset($charity['id']) && $charity['id']!=null) ? (int) $charity['id']:"";
                        $result["charity_list"][$main_key]["charity_name"] = (isset($charity['name']) && $charity['name']!=null)?$charity['name']:"";
                        $result["charity_list"][$main_key]["charity_address"] = (isset($charity['address']) && $charity['address']!=null)?$charity['address'].','.$charity['country_name'].','.$charity['state_name'].','.$charity['city_name']:"";
                        
                        $result["charity_list"][$main_key]["charity_logo"] = (isset($charity['logo']) && $charity['logo']!=null)?Url::to('@web/'.$charity['logo'],true):"";
                       
                        $main_key++;
                    }
                   $result['is_last'] = 'Y';
                    if( $main_key > $limit)
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
                   
                    $this->setHeader(200);
                    $result['code'] = 200;
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                    $resultstring = json_encode($result);
                    $resultstring = str_replace(null,'""',$resultstring);
                    echo $resultstring ;
                    die;
                } else {
                    $this->setHeader(404);
                    if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app','No Charity Found')));
                    else
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>'No Charity Found'));
                    die;
                }
            }else{
                   $this->setHeader(404);
                   if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                    else
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
                    die;
            }
        } else {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
            die;
        }
    }
    
   
    
    public function actionCharityDetails()
    {  
        if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['charity_id']) && $_POST['charity_id'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null){
            
            $user_data = User::find()->where(['is_deleted'=>'N','id'=>$_POST['user_id']])->one();
            if(!empty($user_data) && count($user_data)>0 )
            {
                if(isset($user_data->lang_pref) && $user_data->lang_pref != '' && $user_data->lang_pref == "A")
                Yii::$app->language = "ar";
                $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
               
                $charity_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","id"=>$_POST['charity_id']])->one();
              
                $main_key = 0;
                $result = array();
                if(isset($charity_data) && count($charity_data)>0) {
                    
                        $result["charity_id"] = (isset($charity_data->id) && $charity_data->id!=null)?$charity_data->id:"";
                        $result["charity_name"] = (isset($charity_data->name) && $charity_data->name!=null)?$charity_data->name:"";
                        $result["charity_address"] = (isset($charity_data->address) && $charity_data->address!=null)?$charity_data->address:"";
                        $result["charity_logo"] = (isset($charity_data->logo) && $charity_data->logo!=null)?Url::to('@web/'.$charity_data->logo,true):"";
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
                               
                               $result["purpose_list"][$list_key]["purpose"] = $purpose->purpose;
                               $result["purpose_list"][$list_key]["motto"] = $purpose->motto;
                              
                               $list_key++;
                              }
                          
                            }else{
                                    $result["purpose_list"] = array();
                            }
                           
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
                    if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app','Charity Not Found')));
                    else
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>'Charity Not Found'));
                    die;
                }
            }else{
                $this->setHeader(404);
                   if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                    else
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
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
            $user_data = User::find()->where(['is_deleted'=>'N','id'=>$_POST['user_id']])->one();
            if(!empty($user_data) && count($user_data)>0)
            {
                if(isset($user_data->lang_pref) && $user_data->lang_pref != "" && $user_data->lang_pref == "A")
                Yii::$app->language = "ar";
                $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
                $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:10;
                $country_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","purpose_id"=>$_POST['purpose_id']])->orderby("id desc")->limit($limit+1)->offset($offset)->all();
                $main_key = 0;
                $result = array();
                if(isset($country_data) && count($country_data)>0) {
                   
                    foreach($country_data as $country) {
                        
                        $country_list = Country::find()->where(["id"=>$country->id])->orderby("id desc")->all();
                        $list_key = 0;
                        
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
                    if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app','No Country Exist')));
                    else
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>'No Country Exist'));
                    die;
                }
            }else{
                   $this->setHeader(404);
                   if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                    else
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
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
            $user_data = User::find()->where(['is_deleted'=>'N','id'=>$_POST['user_id']])->one();
            if(!empty($user_data) && count($user_data)>0)
            {
                if(isset($user_data->lang_pref) && $user_data->lang_pref != "" && $user_data->lang_pref == "A")
                Yii::$app->language = "ar";
                $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
                $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:10;
                if(( isset($_POST['purpose_id']) && $_POST['purpose_id'] != null))
                $state_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","purpose_id"=>$_POST['purpose_id'],"country_id"=>$_POST['country_id']])->orderby("id desc")->limit($limit+1)->offset($offset)->all();
                else
                $state_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","country_id"=>$_POST['country_id']])->orderby("id desc")->limit($limit+1)->offset($offset)->all();
                $main_key = 0;
                $result = array();
                   
                if(isset($state_data) && count($state_data)>0) {
                    foreach($state_data as $state) {
                        $state_list = State::find()->where(["country_id"=>$state->country_id])->orderby("id asc")->all();
                        $list_key = 0;
                        
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
                    if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app','No State Found')));
                    else
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>'No State Found'));
                    die;
                }
            }else{
                    $this->setHeader(404);
                    if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                    else
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
                    die;
            }
        } else {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['response_text'][400])));
            die;
        }
    }
    
    public function actionCityList() {
       
        if(isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['state_id']) && $_POST['state_id'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null){
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            $user_data = User::find()->where(['is_deleted'=>'N','id'=>$_POST['user_id']])->one();
            if(!empty($user_data) && count($user_data)>0)
            {
                if(isset($user_data->lang_pref) && $user_data->lang_pref != "" && $user_data->lang_pref == "A")
                Yii::$app->language = "ar";
            $offset = (isset($_REQUEST['offset']) && $_REQUEST['offset'] != null)?$_REQUEST['offset']:0;
            $limit = (isset($_REQUEST['limit']) && $_REQUEST['limit'] != null)? $_REQUEST['limit']:10;
            $city_data = Charity::find()->where(["is_active"=>"Y","is_deleted"=>"N","state_id"=>$_POST['state_id']])->orderby("id desc")->limit($limit+1)->offset($offset)->all();
            $main_key = 0;
            $result = array();
            if(isset($city_data) && count($city_data)>0) {
               
                foreach($city_data as $city) {
                    

                    $city_list = City::find()->where(["state_id"=>$city->state_id])->orderby("id asc")->all();
                    $list_key = 0;
                    
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
                if(Yii::$app->language == "ar")
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app','No City Found')));
                else
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app','No City Found')));
                die;
            }
           }else{
                    $this->setHeader(404);
                    if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                    else
                    echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
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
            if(isset($data->lang_pref) && $data->lang_pref != "" && $data->lang_pref == "A")
                Yii::$app->language = "ar";
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
                  if(Yii::$app->language == "ar")
                  echo json_encode(array('code'=>601,'status'=>'error','message'=>Yii::t('app',utf8_encode(Yii::$app->params['no_data_found']))));
                  else
                  echo json_encode(array('code'=>601,'status'=>'error','message'=>utf8_encode(Yii::$app->params['no_data_found'])));
                  die;
              }

            }
            else
            {
                $this->setHeader(404);
                if(Yii::$app->language == "ar")
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                else
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
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

     public function actionRecurring()
    {
      
        $this->layout = false;
        if(
           isset($_POST['purpose_id']) && $_POST['purpose_id'] != null &&
           isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['charity_id']) && $_POST['charity_id'] != null &&
           isset($_POST['amount']) && $_POST['amount'] != null &&
           isset($_POST['isanonymous']) && $_POST['isanonymous'] != null &&
           isset($_POST['isrecurring']) && $_POST['isrecurring'] != null &&
           isset($_POST['device_type']) && $_POST['device_type'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            
                $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
                $user_data = User::find()->where(['is_deleted'=>'N','id'=>$_POST['user_id']])->one();
            if(!empty($user_data) && count($user_data)>0)
            {
                if(isset($user_data->lang_pref) && $user_data->lang_pref != "" && $user_data->lang_pref == "A")
                Yii::$app->language = "ar";
                
                //echo Yii::$app->language = "ar";
                //exit;
                
                $data = new RecurrenceMaster();
             
                $data->user_id = $_POST['user_id'];
                $data->charityid = $_POST['charity_id'];
                $data->purpose_id = $_POST['purpose_id'];
                $data->isanonymous = $_POST['isanonymous'];
                $data->amount = $_POST['amount'];
                $data->i_date = date('Y-m-d H:i:s');;
                $data->u_date = date('Y-m-d H:i:s');
                
                if(isset($_POST['isrecurring']) && $_POST['isrecurring'] == 'Y')
                {
                    $data->recurrance_type = $_POST['recurringtype'];
                    $data->repeat_duration = $_POST['recurringunit'];
                    $data->duplicate_repeat_duration = $_POST['recurringunit'];
                    $data->repeat_on = $_POST['repeatweakday'];
                    $data->end_type = $_POST['endson'];
                    $data->occurance = $_POST['occurance'];
                    $data->end_date = $_POST['enddate'];
                    $data->end_date_timestamp = time($_POST['enddate']);
                }

                if($data->save(false))
                {
                    $this->setHeader(200);
                    $result['code'] = 200;
                    if(Yii::$app->language == "ar")
                    $result['message'] = Yii::t('app',Yii::$app->params['success_donate_msg']);
                    else
                    $result['message'] = Yii::$app->params['success_donate_msg'];
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                
                    $resultstring =  json_encode($result);
                    $resultstring = str_replace("null",'""',$resultstring);
                    echo $resultstring ;
                    die;
                }
                else
                {
                    $this->setHeader(602);
                    if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_in_save'])));
                    else
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['error_in_save']));
                    
                    die;
                }
                }
            else
            {
                $this->setHeader(404);
                if(Yii::$app->language == "ar")
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                else
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
                die;
            }
        }else
        {
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }
    
    public function actionSubscriptionDetails()
    {
        
        $this->layout = false;
        if(
           isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['subscription_id']) && $_POST['subscription_id'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            
            $user_data = User::find()->where(['is_deleted'=>'N','id'=>$_POST['user_id']])->one();
            if(!empty($user_data) && count($user_data)>0)
            {
                if(isset($user_data->lang_pref) && $user_data->lang_pref != "" && $user_data->lang_pref == "A")
                Yii::$app->language = "ar";
            
                $data = RecurrenceMaster::find()->where(['id'=>$_POST['subscription_id']])->one();
                
                 $result['subscription']= array();
                if(!empty($data))
                {
                    
                    $result['subscription']["subscription_id"] = (isset($data->id) && $data->id!=null)?$data->id:"";
                    $result['subscription']["purpose_id"] = (isset($data->purpose_id) && $data->purpose_id!=null)?$data->purpose_id:"";
                    $result['subscription']["isanonymous"] = (isset($data->isanonymous) && $data->isanonymous!=null)?$data->isanonymous:"";
                    $result['subscription']["isrecurring"] = (isset($data->recurrance_type) && $data->recurrance_type!=null)?'Y':"N";
                    $result['subscription']["recurringtype"] = (isset($data->recurrance_type) && $data->recurrance_type!=null)?$data->recurrance_type:"";
                    $result['subscription']["recurringunit"] = (isset($data->repeat_duration) && $data->repeat_duration!=null)?$data->repeat_duration:"";
                    $result['subscription']["repeatweakday"] = (isset($data->repeat_on) && $data->repeat_on!=null)?$data->repeat_on:"";
                    $result['subscription']["endson"] = (isset($data->end_type) && $data->end_type!=null)?$data->end_type:"";
                    $result['subscription']["enddate"] = (isset($data->end_date) && $data->end_date!=null )?$data->end_date:"";
                    $result['subscription']["occurance"] =(isset($data->occurance) && $data->occurance!='')?(string)$data->occurance:'';
                    $result['subscription']["amount"] = (isset($data->amount) && $data->amount!=null)?substr($data->amount,0,4):"";
                    
                    //echo "<pre>";
                    //print_r($result);die;
                    $this->setHeader(200);
                    $result['code'] = 200;
                    //$result['message'] = Yii::$app->params['success_donate_msg'];
                    $result['status'] = Yii::$app->params['response_text'][$result['code']];
                
                    $resultstring =  json_encode($result);
                    $resultstring = str_replace("null",'""',$resultstring);
                    echo $resultstring ;
                    die;
                    
                    
                }else{
                       $this->setHeader(400);
                       if(Yii::$app->language == "ar")
                       echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['no_data_found'])));
                       else
                       echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['no_data_found']));
                       die;
                }
               }
            else
            {
                $this->setHeader(404);
                if(Yii::$app->language == "ar")
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                else
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
                die;
            } 
                
        }else{
        
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }
    
     public function actionEditSubscription()
    {
        $this->layout = false;
        if(
           isset($_POST['user_id']) && $_POST['user_id'] != null &&
           isset($_POST['subscription_id']) && $_POST['subscription_id'] != null &&
           isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null)
        {
            
            $check_user = Yii::$app->mycomponent->validate_user($_POST['user_id'],$_POST['encrypted_data']);
            $user_data = User::find()->where(['is_deleted'=>'N','id'=>$_POST['user_id']])->one();
            if(!empty($user_data) && count($user_data)>0)
            {
                if(isset($user_data->lang_pref) && $user_data->lang_pref != "" && $user_data->lang_pref == "A")
                Yii::$app->language = "ar";
            
                $data = RecurrenceMaster::find()->where(['id'=>$_POST['subscription_id']])->one();
               
                if(!empty($data))
                {
                    $data->user_id = $_POST['user_id'];
                    $data->charityid = $_POST['charity_id'];
                    $data->purpose_id = $_POST['purpose_id'];
                    $data->isanonymous = $_POST['isanonymous'];
                    $data->amount = $_POST['amount'];
                     $data->i_date = date('Y-m-d H:i:s');
                     $data->u_date = date('Y-m-d H:i:s');
                
                        if(isset($_POST['isrecurring']) && $_POST['isrecurring'] == 'Y')
                         {
                            $data->recurrance_type = $_POST['recurringtype'];
                            $data->repeat_duration = $_POST['recurringunit'];
                            $data->duplicate_repeat_duration = $_POST['recurringunit'];
                            $data->repeat_on = $_POST['repeatweakday'];
                            $data->occurance = $_POST['occurance'];
                            $data->end_type = $_POST['endson'];
                            $data->end_date = $_POST['enddate'];
                            $data->end_date_timestamp = time($_POST['enddate']);
                        }

                        if($data->save(false))
                        {
                            $this->setHeader(200);
                            $result['code'] = 200;
                            if(Yii::$app->language == "ar")
                            $result['message'] = Yii::t('app',Yii::$app->params['success_subscription_edit']);
                            else
                            $result['message'] = Yii::$app->params['success_subscription_edit'];
                            
                            $result['status'] = Yii::$app->params['response_text'][$result['code']];
                        
                            $resultstring =  json_encode($result);
                            $resultstring = str_replace("null",'""',$resultstring);
                            echo $resultstring ;
                            die;
                        }else
                        {
                            $this->setHeader(602);
                            if(Yii::$app->language == "ar")
                            echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_in_save'])));
                            else
                            echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['error_in_save']));
                            die;
                        }
                        
                }else{
                    
                    $this->setHeader(602);
                    if(Yii::$app->language == "ar")
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['no_data_found'])));
                    else
                    echo json_encode(array('code'=>602,'status'=>'error','message'=>Yii::$app->params['no_data_found']));
                    die;
                }
            }else
            {
                $this->setHeader(404);
                if(Yii::$app->language == "ar")
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::t('app',Yii::$app->params['error_user_not_found'])));
                else
                echo json_encode(array('code'=>404,'status'=>'error','message'=>Yii::$app->params['error_user_not_found']));
                die;
            } 
        }else{
        
            $this->setHeader(400);
            echo json_encode(array('code'=>400,'status'=>'error','message'=>Yii::$app->params['response_text'][400]));
            die;
        }
    }
    
}
 ?>
