<?php

namespace app\modules\api\controllers;
use Yii;
use yii\helpers\Url;
use yii\rest\ActiveController;
use app\models\Feelingstation;
use app\models\Users;
use app\models\Product;
use app\models\Service;
use app\models\Facility;
use app\models\Stationreview;
use app\models\Feedback;
use app\models\Notification;
use yii\helpers\ArrayHelper;

class FeelingstationController extends ActiveController
{
    public $modelClass = 'app\models\Feelingstation';
    //public $serializer = [
    //    'class' => 'yii\rest\Serializer',
    //    //'collectionEnvelope' => 'Service',
    //];
    public function behaviors()
    {
        return [
            [
                'class' => \yii\filters\ContentNegotiator::className(),
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                ],
            ],
        ];
    }
    
    /*
     *  API Name : Filter Data
     *  Created By : aadil
     *  Creation Date : 16-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionFilterdata()
    {
        if(isset($_GET['userid']) && $_GET['userid'] != null
           && isset($_GET['encrypted_data']) && $_GET['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_GET['userid'],$_GET['encrypted_data']);
            
            $result['max_km'] = 50;
            
            $result['Facility'] = array();
            $facility = Facility::find()->where(['is_active'=>'Y','is_deleted'=>'N'])->all();
            if($facility != array())
            {
                $i = 0;
                foreach($facility as $list)
                {
                    $result['Facility'][$i]['id'] = $list->id;
                    $result['Facility'][$i]['name'] = $list->name;
                    $result['Facility'][$i]['image'] = $list->full_image;
                    $i++;
                }
            }
            
            $result['Product'] = array();
            $product = Product::find()->where(['is_active'=>'Y','is_deleted'=>'N'])->all();
            if($product != array())
            {
                $i = 0;
                foreach($product as $list)
                {
                    $result['Product'][$i]['id'] = $list->id;
                    $result['Product'][$i]['name'] = $list->name;
                    $result['Product'][$i]['image'] = $list->full_image;
                    $i++;
                }
            }
            
            $result['Service'] = array();
            $service = Service::find()->where(['is_active'=>'Y','is_deleted'=>'N'])->all();
            if($service != array())
            {
                $i = 0;
                foreach($service as $list)
                {
                    $result['Service'][$i]['id'] = $list->id;
                    $result['Service'][$i]['name'] = $list->name;
                    $result['Service'][$i]['image'] = $list->full_image;
                    $i++;
                }
            }
            
            $q = Feelingstation::find()->where(['is_active'=>'Y','is_deleted'=>'N']);
            $data = $q->all();
            $regions = $q->select('region')->groupBy('region')->all();
            $result['Station'] = array();
            $result['Region'] = array();
            if($data != array())
            {
                $j = 0;
                $i = 0;
                foreach($data as $list)
                {
                    $result['Station'][$i]['id'] = $list->id;
                    $result['Station'][$i]['name'] = $list->name;
                    $result['Station'][$i]['region'] = $list->region;
                    $result['Station'][$i]['latitude'] = $list->latitude;
                    $result['Station'][$i]['longitude'] = $list->longitude;
                    $i++;
                    
                }
                
                //$regions = ArrayHelper::map($data,'region','region');
                $result['Region'] = $regions;
            }
            
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    
    /*
     *  API Name : Search
     *  Created By : aadil
     *  Creation Date : 16-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid,latitude,longitude
     *  Output :
     */
    public function actionSearch()
    {
        if(isset($_GET['userid']) && $_GET['userid'] != null
           && isset($_GET['encrypted_data']) && $_GET['encrypted_data'] != null
           && isset($_GET['latitude']) && $_GET['latitude'] != null
           && isset($_GET['longitude']) && $_GET['longitude'] != null
           /*&& isset($_GET['radius']) && $_GET['radius'] != null*/)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_GET['userid'],$_GET['encrypted_data']);
            
            $lat = $_GET['latitude'];
            $lng = $_GET['longitude'];
            
            $result['Station'] = array();
            
            $query = Feelingstation::find()->select("*,( 6371 * acos( cos( radians($lat) ) * cos( radians( latitude) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance")
            ->where(['is_active'=>'Y','is_deleted'=>'N'])
            ->orderBy(['distance' => SORT_ASC]);
            
            if(isset($_GET['radius']) && $_GET['radius'] != null)
            {
                $radius = $_GET['radius'];
                $query->andHaving("distance < $radius");
            }
            
            if(isset($_GET['facility_ids']) && $_GET['facility_ids'] != '')
            {
                $exist = array();
                $faci[] = 'and';
                $arr = explode(',',$_GET['facility_ids']);
                foreach($arr as $w)
                {
                    if(!in_array($w,$exist))
                    {
                        $faci[] = 'FIND_IN_SET("'.$w.'",`facilities`)';
                        $exist[] = $w;
                    }
                }
                $query->andwhere($faci);
            }
            if(isset($_GET['service_ids']) && $_GET['service_ids'] != '')
            {
                $exist = array();
                $serv[] = 'and';
                $arr = explode(',',$_GET['service_ids']);
                foreach($arr as $w)
                {
                    if(!in_array($w,$exist))
                    {
                        $serv[] = 'FIND_IN_SET("'.$w.'",`services`)';
                        $exist[] = $w;
                    }
                }
                $query->andwhere($serv);
            }
            if(isset($_GET['product_ids']) && $_GET['product_ids'] != '')
            {
                $exist = array();
                $prod[] = 'and';
                $arr = explode(',',$_GET['product_ids']);
                foreach($arr as $w)
                {
                    if(!in_array($w,$exist))
                    {
                        $prod[] = 'FIND_IN_SET("'.$w.'",`products`)';
                        $exist[] = $w;
                    }
                }
                $query->andwhere($prod);
            }
            
            if(isset($_GET['station_id']) && $_GET['station_id'] != '')
            {
                $query->andwhere(['id'=>$_GET['station_id']]);
            }
            if(isset($_GET['region']) && $_GET['region'] != '')
            {
                $query->andwhere(['like', 'region', $_GET['region']]);
            }
            if(isset($_GET['keyword']) && $_GET['keyword'] != '')
            {
                $query->andwhere(['or',['like', 'name', $_GET['keyword']],['like', 'info', $_GET['keyword']]]);
            }
            
            $data = $query->all();
            
            if($data != array())
            {
                $i = 0;
                foreach($data as $list)
                {
                    $result['Station'][$i]['id'] = $list->id;
                    $result['Station'][$i]['name'] = $list->name;
                    $result['Station'][$i]['region'] = $list->region;
                    $result['Station'][$i]['address'] = $list->address;
                    $result['Station'][$i]['latitude'] = $list->latitude;
                    $result['Station'][$i]['longitude'] = $list->longitude;
                    $result['Station'][$i]['info'] = $list->info;
                    $result['Station'][$i]['phone_number'] = $list->phone_number;
                    
                    $result['Station'][$i]['working_hours'] = $list->working_hours;
                    $result['Station'][$i]['map_link'] = ($list->map_link) ? $list->map_link : '';
                    
                    $result['Station'][$i]['distance'] = round($list->distance,2);
                    $esti = 0;
                    $start = $lat.",".$lng;
                    $end = $list->latitude.",".$list->longitude;
                    
                    $request = 'https://maps.googleapis.com/maps/api/directions/json?origin='.$start.'&destination='.$end.'&sensor=false&mode=driving&key='.Yii::$app->params['google_api_key'];
                    
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $request);
                    curl_setopt($ch, CURLOPT_HEADER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_FRESH_CONNECT,true);
                    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"); 
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
                    $json = curl_exec($ch);
                    curl_close($ch);
                    $esti_data = json_decode($json);
                    //echo '<pre>';print_r($esti_data);die;
                    
                    //$esti_data = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/directions/json?origin='.$start.'&destination='.$end.'&sensor=false&mode=driving'));
                    if(isset($esti_data->routes[0]->legs[0]->duration->value))
                    $esti = (int)$esti_data->routes[0]->legs[0]->duration->value;
                    
                    //if(isset($esti_data->routes[0]->legs[0]->distance->value))
                    //$result['Station'][$i]['distance'] = round(((int) $esti_data->routes[0]->legs[0]->distance->value)/1000,2);
                    
                    $result['Station'][$i]['estimated_time'] = $esti;
                    
                    $result['Station'][$i]['services'] = $list->getService($list->services);
                    $result['Station'][$i]['products'] = $list->getProduct($list->products);
                    $result['Station'][$i]['facilities'] = $list->getFacility($list->facilities);
                    
                    $result['Station'][$i]['avg_rating'] = $list->avg_rating;
                    $result['Station'][$i]['rating_count'] = $list->rating_count;
                    
                    $i++;
                }
            }
            
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    /*
     *  API Name : Get Reviews
     *  Created By : aadil
     *  Creation Date : 16-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionGetreviews()
    {
        if(isset($_GET['userid']) && $_GET['userid'] != null
           && isset($_GET['encrypted_data']) && $_GET['encrypted_data'] != null
           && isset($_GET['fuel_station_id']) && $_GET['fuel_station_id'] != null
           && isset($_GET['start']) && $_GET['start'] != null
           && isset($_GET['limit']) && $_GET['limit'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_GET['userid'],$_GET['encrypted_data']);
            $start = $_GET['start'];
            $limit = $_GET['limit']+1;
            
            $review = Stationreview::find()->where(['feeling_station_id'=>$_GET['fuel_station_id'],'is_active'=>'Y','is_deleted'=>'N'])
            ->offset($start)->limit($limit)->all();
            
            $result['Review'] = array();
            $result['is_last'] = 'Y';
            if($review != array())
            {
                $i = 0;
                foreach($review as $list)
                {
                    $result['Review'][$i]['id'] = $list->id;
                    $result['Review'][$i]['rate'] = $list->rate;
                    $result['Review'][$i]['review'] = $list->review;
                    $result['Review'][$i]['time_stamp'] = $list->i_date;
                    $i++;
                }
                if($i == $limit)
                {
                    unset($result['Review'][$i-1]);
                    $result['is_last'] = 'N';
                }
            }
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    /*
     *  API Name : My Reviews
     *  Created By : aadil
     *  Creation Date : 16-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionMyreviews()
    {
        if(isset($_GET['userid']) && $_GET['userid'] != null
           && isset($_GET['encrypted_data']) && $_GET['encrypted_data'] != null
           && isset($_GET['start']) && $_GET['start'] != null
           && isset($_GET['limit']) && $_GET['limit'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_GET['userid'],$_GET['encrypted_data']);
            $start = $_GET['start'];
            $limit = $_GET['limit']+1;
            
            $review = Stationreview::find()->where(['user_id'=>$_GET['userid'],'is_active'=>'Y','is_deleted'=>'N'])
            ->offset($start)->limit($limit)->all();
            
            $result['Review'] = array();
            $result['is_last'] = 'Y';
            if($review != array())
            {
                $i = 0;
                foreach($review as $list)
                {
                    $result['Review'][$i]['id'] = $list->id;
                    $result['Review'][$i]['rate'] = $list->rate;
                    $result['Review'][$i]['review'] = $list->review;
                    $result['Review'][$i]['time_stamp'] = $list->i_date;
                    $result['Review'][$i]['title'] = '';
                    $station = Feelingstation::find()->select('name')->where(['id'=>$list->feeling_station_id,'is_deleted'=>'N'])->one();
                    if($station != array())
                    $result['Review'][$i]['title'] = $station->name;
                    $i++;
                }
                if($i == $limit)
                {
                    unset($result['Review'][$i-1]);
                    $result['is_last'] = 'N';
                }
            }
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    /*
     *  API Name : Give Reviews
     *  Created By : aadil
     *  Creation Date : 16-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionGivereview()
    {
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['fuel_station_id']) && $_POST['fuel_station_id'] != null
           && isset($_POST['rate']) && $_POST['rate'] != null)
           /*&& isset($_POST['review']) && $_POST['review'] != null*/
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            
            $review = new Stationreview();
            $review->user_id = $_POST['userid'];
            $review->feeling_station_id = $_POST['fuel_station_id'];
            $review->rate = $_POST['rate'];
            
            if(isset($_POST['review']) && $_POST['review'] != '')
            $review->review = $_POST['review'];
            
            $review->is_active = 'N';
            $review->is_deleted = 'N';
            $review->i_by = $_POST['userid'];
            $review->i_date = time();
            $review->u_by = $_POST['userid'];
            $review->u_date = time();
            $review->save(false);
            
            $result['message'] = Yii::t('app',Yii::$app->params['review_submited']);
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    /*
     *  API Name : Give Feedback
     *  Created By : aadil
     *  Creation Date : 17-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionGivefeedback()
    {
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['fuel_station_id']) && $_POST['fuel_station_id'] != null
           && isset($_POST['subject']) && $_POST['subject'] != null
           && isset($_POST['comment']) && $_POST['comment'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            
            $feedback = new Feedback();
            $feedback->user_id = $_POST['userid'];
            $feedback->feeling_station_id = $_POST['fuel_station_id'];
            $feedback->subject = $_POST['subject'];
            $feedback->comment = $_POST['comment'];
            $feedback->i_by = $_POST['userid'];
            $feedback->i_date = time();
            $feedback->u_by = $_POST['userid'];
            $feedback->u_date = time();
            $feedback->save(false);
            
            $result['message'] = Yii::t('app',Yii::$app->params['feedback_submited']);
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    /*
     *  API Name : Submit Feedback
     *  Created By : aadil
     *  Creation Date : 17-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid
     *  Output :
     */
    public function actionSubmitfeedback()
    {
        if(isset($_POST['userid']) && $_POST['userid'] != null
           && isset($_POST['encrypted_data']) && $_POST['encrypted_data'] != null
           && isset($_POST['subject']) && $_POST['subject'] != null
           && isset($_POST['comment']) && $_POST['comment'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_POST['userid'],$_POST['encrypted_data']);
            
            $feedback = new Feedback();
            $feedback->user_id = $_POST['userid'];
            $feedback->subject = $_POST['subject'];
            $feedback->comment = $_POST['comment'];
            $feedback->i_by = $_POST['userid'];
            $feedback->i_date = time();
            $feedback->u_by = $_POST['userid'];
            $feedback->u_date = time();
            $feedback->save(false);
            
            $result['message'] = Yii::t('app',Yii::$app->params['feedback_submited']);
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    /*
     *  API Name : Detail
     *  Created By : aadil
     *  Creation Date : 23-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid,station_id
     *  Output :
     */
    public function actionDetail()
    {
        if(isset($_GET['userid']) && $_GET['userid'] != null
           && isset($_GET['encrypted_data']) && $_GET['encrypted_data'] != null
           && isset($_GET['fuel_station_id']) && $_GET['fuel_station_id'] != null
           && isset($_GET['latitude']) && $_GET['latitude'] != null
           && isset($_GET['longitude']) && $_GET['longitude'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_GET['userid'],$_GET['encrypted_data']);
            $lat = $_GET['latitude'];
            $lng = $_GET['longitude'];
            $list = Feelingstation::find()->where(['id'=>$_GET['fuel_station_id'],'is_active'=>'Y','is_deleted'=>'N'])->one();
            
            if($list != array())
            {
                $result['Station']['id'] = $list->id;
                $result['Station']['name'] = $list->name;
                $result['Station']['region'] = $list->region;
                $result['Station']['address'] = $list->address;
                $result['Station']['latitude'] = $list->latitude;
                $result['Station']['longitude'] = $list->longitude;
                $result['Station']['info'] = $list->info;
                $result['Station']['phone_number'] = $list->phone_number;
                
                $result['Station']['working_hours'] = $list->working_hours;
                $result['Station']['map_link'] = ($list->map_link) ? $list->map_link : '';
                
                $result['Station']['distance'] = round($list->distance,2);
                $esti = 0;
                $start = $lat.",".$lng;
                $end = $list->latitude.",".$list->longitude;
                
                $request = 'https://maps.googleapis.com/maps/api/directions/json?origin='.$start.'&destination='.$end.'&sensor=false&mode=driving&key='.Yii::$app->params['google_api_key'];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $request);
                curl_setopt($ch, CURLOPT_HEADER, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT,true);
                curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"); 
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
                $json = curl_exec($ch);
                curl_close($ch);
                $esti_data = json_decode($json);
                
                //$esti_data = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/directions/json?origin='.$start.'&destination='.$end.'&sensor=false&mode=driving'));
                if(isset($esti_data->routes[0]->legs[0]->duration->value))
                $esti = (int)$esti_data->routes[0]->legs[0]->duration->value;
                
                //if(isset($esti_data->routes[0]->legs[0]->distance->value))
                //$esti = (int)$esti_data->routes[0]->legs[0]->distance->value;
                
                
                
                $result['Station']['estimated_time'] = $esti;
                
                $result['Station']['services'] = $list->getService($list->services);
                $result['Station']['products'] = $list->getProduct($list->products);
                $result['Station']['facilities'] = $list->getFacility($list->facilities);
                
                $result['Station']['avg_rating'] = $list->avg_rating;
                $result['Station']['rating_count'] = $list->rating_count;
                    
            }
            
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    /*
     *  API Name : Notification List
     *  Created By : aadil
     *  Creation Date : 24-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid,
     *  Output :
     */
    public function actionNotificationlist()
    {
        if(isset($_GET['userid']) && $_GET['userid'] != null
           && isset($_GET['encrypted_data']) && $_GET['encrypted_data'] != null
           && isset($_GET['start']) && $_GET['start'] != null
           && isset($_GET['limit']) && $_GET['limit'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_GET['userid'],$_GET['encrypted_data']);
            $start = $_GET['start'];
            $limit = $_GET['limit']+1;
            $notification = Notification::find()->where(['user_id'=>$_GET['userid'],'is_deleted'=>'N'])
            ->offset($start)->limit($limit)->orderBy('id desc')->all();
            
            $result['Notification'] = array();
            $result['is_last'] = 'Y';
            if($notification != array())
            {
                $i = 0;
                foreach($notification as $list)
                {
                    $result['Notification'][$i]['id'] = $list->id;
                    $result['Notification'][$i]['fuel_station_id'] = $list->feeling_station_id;
                    $result['Notification'][$i]['kilometer'] = $list->kilometer;
                    $result['Notification'][$i]['datetime'] = $list->datetime;
                    $result['Notification'][$i]['type'] = $list->type;
                    $result['Notification'][$i]['station_name'] = '';
                    $station = Feelingstation::find()->where(['id'=>$list->feeling_station_id,'is_deleted'=>'N'])->one();
                    if($station != array())
                    {
                        $result['Notification'][$i]['station_name'] = $station->name;
                    }
                    $i++;
                }
                if($i == $limit)
                {
                    unset($result['Notification'][$i-1]);
                    $result['is_last'] = 'N';
                }
            }
            
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    /*
     *  API Name : Clear Notification
     *  Created By : aadil
     *  Creation Date : 12-05-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid,
     *  Output :
     */
    public function actionClearnotification()
    {
        if(isset($_GET['userid']) && $_GET['userid'] != null
           && isset($_GET['encrypted_data']) && $_GET['encrypted_data'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_GET['userid'],$_GET['encrypted_data']);
            
            $cond['is_deleted'] = 'Y';
            Notification::updateAll($cond,'user_id ='.$_GET['userid']);
            
            $result['message'] = Yii::t('app',Yii::$app->params['notification_clear']);
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
    
    /*
     *  API Name : Check NearBy
     *  Created By : aadil
     *  Creation Date : 24-03-2017
     *  Updated By :
     *  Updated Date :
     *  Input : userid,latitude,longitude
     *  Output :
     */
    public function actionChecknearby()
    {
        if(isset($_GET['userid']) && $_GET['userid'] != null
           && isset($_GET['encrypted_data']) && $_GET['encrypted_data'] != null
           && isset($_GET['latitude']) && $_GET['latitude'] != null
           && isset($_GET['longitude']) && $_GET['longitude'] != null)
        {
            $check_user = Yii::$app->mycomponent->validate_user($_GET['userid'],$_GET['encrypted_data']);
            
            $radius = 1;
            $lat = $_GET['latitude'];
            $lng = $_GET['longitude'];
            
            $query = Feelingstation::find()->select("*,( 6371 * acos( cos( radians($lat) ) * cos( radians( latitude) ) * cos( radians( longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( latitude ) ) ) ) AS distance")
            ->where(['is_active'=>'Y','is_deleted'=>'N'])
            ->andHaving("distance < $radius");
            
            $list = $query->orderBy(['distance' => SORT_ASC])->one();
            $count = $query->count();
            
            if($list != array())
            {
                $user = Users::find()->where(['id'=>$_GET['userid'],'is_active'=>'Y','is_deleted'=>'N','notification'=>'Y'])->andwhere('device_id IS NOT NULL')->one();
                
                //foreach($data as $list)
                {
                    $notification = new Notification();
                    
                    $notification->message = $list->name.' is '.round($list->distance,2).' away';
                    
                    $notification->user_id = $_GET['userid'];
                    $notification->feeling_station_id = $list->id;
                    $notification->kilometer = round($list->distance,2);
                    $notification->datetime = time();
                    $notification->type = 'km_away';
                    $notification->save(false);
                    
                    $message = $notification->message;
                    //echo '<pre>';print_r($user);die;
                    if($user != array())
                    {
                        $badge = 1;
                        $type = $notification->type;
                        
                        if($user->device_type == 'I' && $user->device_id != '' && $user->device_id != null)
                        {
                            $body = array();
                            $body['aps'] = array('alert' =>  $message);
                            $body['aps']['type'] = $type;
                            //$body['aps']['badge'] = $badge;
                            $body['aps']['sound'] = 'default';
                            $body['aps']['feeling_station_id'] = $list->id;
                            $body['aps']['kilometer'] = round($list->distance,2);
                            $body['aps']['count'] = $count;
                            
                            Yii::$app->mycomponent->pushnotification_iphone($user->device_id,$body);
                        }
                        if($user->device_type == 'A' && $user->device_id != '' && $user->device_id != null)
                        {
                            $body = array();
                            $body['data'] = array('text' =>  $message);
                            $body['data']['type'] = $type;
                            $body['data']['feeling_station_id'] = $list->id;
                            $body['data']['kilometer'] = round($list->distance,2);
                            $body['data']['count'] = $count;
                            
                            Yii::$app->mycomponent->pushnotification_android($user->device_id,$body);
                        }
                    }
                    
                }
            }
            $result = '';
            return $result;
        }
        else
        {
            $result['message'] = Yii::t('app',Yii::$app->params['response_text'][400]);
            Yii::$app->getResponse()->setStatusCode(400);
            return $result;
        }
    }
}
