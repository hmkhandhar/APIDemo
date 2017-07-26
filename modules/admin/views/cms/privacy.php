<?php

use yii\helpers\Html;
use app\models\Cms;

$data = Cms::find()->where(['title'=>'privacy','is_deleted'=>'N'])->one();
if($data)
{
    echo $data->content;
}
?>