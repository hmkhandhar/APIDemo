<?php

use yii\helpers\Html;
use app\models\Cms;

$data = Cms::find()->where(['title'=>'help','is_deleted'=>'N'])->one();
if($data)
{
    echo $data->content;
}
?>