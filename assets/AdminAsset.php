<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;
/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css =
    [
        'css/bootstrap.min.css',
        'css/font-awesome.min.css',
        'css/ionicons.min.css',
        'css/AdminLTE.min.css',
        'css/custom.css',
        'css/skins/_all-skins.min.css',
        'plugins/bootstrap-fileupload/bootstrap-fileupload.css',
        // 'plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css',
        'plugins/datepicker3.css',
		
		
        

    ];

    public $js =
    [
        'scripts/jquery-2.2.3.min.js',
        'scripts/bootstrap.min.js',
        'scripts/fastclick.js',
		'scripts/app.min.js',
		'scripts/demo.js',
		'scripts/jquery.validate.min.js',
		'plugins/bootstrap-fileupload/bootstrap-fileupload.js',
        'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
    

    ];
	public $jsOptions = array(
	'position' => \yii\web\View::POS_HEAD
	);

    //public $depends = [
    //    'yii\web\YiiAsset',
    //    'yii\bootstrap\BootstrapAsset',
    //];
}
