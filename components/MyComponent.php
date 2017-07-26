<?php

namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\Session;
use app\models\User;
use app\models\Users;
use app\models\Product;

class MyComponent extends Component
{
	public function createThumbnail($srcFile, $destFile, $width, $quality = 90){

          $thumbnail = '';
          if (file_exists($srcFile)  && isset($destFile))
          {
                  $size        = getimagesize($srcFile);
                  $w           = number_format($width, 0, ',', '');
                  $h           = number_format(($size[1] / $size[0]) * $width, 0, ',', '');

                  $thumbnail =  $this->copyImage($srcFile, $destFile, $w, $h, $quality);
          }

          // return the thumbnail file name on sucess or blank on fail
          return basename($thumbnail);
    }

	public function uploadUserImage($image, $uploadDir, $w, $thumbnail_width){
            $imagePath = '';
            $thumbnailPath = '';

            if (trim($image['tmp_name']) != '')
            {
                    $ext = substr(strrchr($image['name'], "."), 1);

                    $imagePath = 'thumb-'.md5(rand() * time()) . ".$ext";

                    list($width, $height, $type, $attr) = getimagesize($image['tmp_name']);

                    $this->createThumbnail($image['tmp_name'], $uploadDir . $imagePath, $w,$height);
            }
            $arr['image'] = $imagePath;
            return $arr;
    }

    function copyImage($srcFile, $destFile, $w, $h, $quality = 75){

        $tmpSrc     = pathinfo(strtolower($srcFile));
        $tmpDest    = pathinfo(strtolower($destFile));
        $size       = getimagesize($srcFile);

        if ($tmpDest['extension'] == "gif" || $tmpDest['extension'] == "jpg")
        {
                $destFile  = substr_replace($destFile, 'jpg', -3);
                $dest      = imagecreatetruecolor($w, $h);

              // imageantialias($dest, TRUE);
        }
        elseif ($tmpDest['extension'] == "png" || $tmpDest['extension'] == "jpeg")
        {
               $dest = imagecreatetruecolor($w, $h);
               //imageantialias($dest, TRUE);
        }
        else
        {
              return false;
        }

        switch($size[2])
        {
           case 1:       //GIF
               $src = imagecreatefromgif($srcFile);
               break;
           case 2:       //JPEG
               $src = imagecreatefromjpeg($srcFile);
               break;
           case 3:       //PNG
               $src = imagecreatefrompng($srcFile);
               break;
           default:
               return false;
               break;
        }

        imagecolortransparent($dest, imagecolorallocatealpha($dest, 0, 0, 0, 127));
        imagealphablending($dest, false);
        imagesavealpha($dest, true);
        imagecopyresampled($dest, $src, 0, 0, 0, 0, $w, $h, $size[0], $size[1]);

        switch($size[2])
        {
           case 1:
           case 2:
               imagejpeg($dest,$destFile, $quality);
               break;
           case 3:
               imagepng($dest,$destFile);
        }
        return $destFile;
    }

    private function setHeader($status)
    {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . Yii::$app->params['response_text'][$status];
        $content_type="application/json; charset=utf-8";
        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "Crossfit");
    }

  public function validate_user($value,$encrypted_data)
  {
        $secretkey = Yii::$app->params['encryption_key'];
        $user = hash_hmac('sha256', $value, $secretkey);
        if($user != $encrypted_data)
        {
            $this->setHeader(403);
            echo json_encode(array('code'=>403,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_user_have_not_access'])));
            die;
        }
        //$exist = User::find()->where(['id'=>$value,'is_deleted'=>'N','user_type'=>'U'])->one();
        //if($exist == array())
        //{
        //    $this->setHeader(403);
        //    echo json_encode(array('code'=>403,'status'=>'error','message'=>utf8_encode(Yii::$app->params['error_user_have_not_access'])));
        //    die;
        //}
        
        //return $user;
  }

   public function authenticate($controller,$action)
    {

        if(Yii::$app->user->id == 1)
        {
            return true;
        }
        else{

            $uer_type = User::find()->where(['id'=>Yii::$app->user->id])->one();

           /* echo "<pre>";
            print_r(Yii::$app->user->id);
            echo "-----";
            print_r($uer_type);
            print_r($controller);
            print_r($action);
            exit;*/
          
          if(isset($uer_type) ){
          if($uer_type->user_type != 'A')
          {
            $d=Authitem::find()->where(["controller"=>$controller,'action'=>$action,'is_deleted' =>'N'])->one();
            if(isset($d) && count($d)>0)
            {

               if(isset($uer_type))
               {
                    $permission=Permission::find()->where(["user_id"=>$uer_type->id])->one();
                        if(isset($permission))
                            $item=explode(',',$permission->permission);

                        if(isset($permission) && in_array($d->id,$item))
                        {
                              /*echo " permit";
                              exit;*/
                        return true;
                        }
                        else
                        {     /*echo "not permit";
                              exit;*/
                            return false;
                        }
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
          }
            else
            {
                 return true;
            }

         }else
            {
                return false;
            }


        }
    }


    public function userResponse($id)
    {
        $data = Users::find()->where(['is_deleted'=>'N','id'=>$id])->one();
        $result['User']['id'] = $data->id;
        $result['User']['first_name'] = $data->first_name;
        $result['User']['last_name'] = $data->last_name;
        $result['User']['email'] = $data->email;        


        $result['Token']['token'] = $data->access_token;
        $result['Token']['type'] = 'Bearer';
        return $result;
    }





}
?>