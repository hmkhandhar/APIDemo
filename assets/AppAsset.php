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
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'global/css/components-md.css',
        'global/css/components-md.min.css',
        'global/css/components-rounded.css',
        'global/css/components-rounded.min.css',
        'global/css/components.css',
        'global/css/components.min.css',
        'global/css/plugins-md.css',
        'global/css/plugins-md.min.css',
        'global/css/plugins.css',
        'global/css/plugins.min.css',
        'global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
        'global/plugins/font-awesome/css/font-awesome.min.css',
        'global/plugins/simple-line-icons/simple-line-icons.min.css',
        'global/plugins/bootstrap/css/bootstrap.min.css',
        'global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
        'global/plugins/morris/morris.css',
        'global/plugins/fullcalendar/fullcalendar.min.css',
        'global/plugins/jqvmap/jqvmap/jqvmap.css',
        'layouts/layout3/css/layout.min.css',
        'layouts/layout3/css/themes/default.min.css',
        'layouts/layout3/css/custom.min.css',
        'plugins/bootstrap-fileupload/bootstrap-fileupload.css',
        'plugins/datepicker3.css',
                
    ];
    public $js = [
        // 'https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js',
        // 'global/plugins/jquery.min.js',
        'scripts/jquery.validate.min.js',
        'global/plugins/jquery.blockui.min.js',
        'global/plugins/bootstrap/js/bootstrap.min.js',
        'global/plugins/js.cookie.min.js',
        'global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',        
        'global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',
        // 'global/plugins/moment.min.js',
        // 'global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
        'global/plugins/morris/morris.min.js',
        'global/plugins/morris/raphael-min.js',
        'global/plugins/counterup/jquery.waypoints.min.js',
        'global/plugins/counterup/jquery.counterup.min.js',
        // 'global/plugins/fullcalendar/fullcalendar.min.js',
        'global/plugins/flot/jquery.flot.min.js',
        'global/plugins/flot/jquery.flot.resize.min.js',
        'global/plugins/flot/jquery.flot.categories.min.js',
        'global/plugins/jquery-easypiechart/jquery.easypiechart.min.js',
        'global/plugins/jquery.sparkline.min.js',
        'global/plugins/jqvmap/jqvmap/jquery.vmap.js',
        'global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js',
        'global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js',
        'global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js',
        'global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js',
        'global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js',
        'global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js',
        'global/scripts/app.min.js',
        'pages/scripts/dashboard.min.js',
        'layouts/layout3/scripts/layout.min.js',
        'layouts/layout3/scripts/demo.min.js',
        'layouts/global/scripts/quick-sidebar.min.js',
        'layouts/global/scripts/quick-nav.min.js',
        // 'pages/scripts/login.min.js',
        'plugins/bootstrap-fileupload/bootstrap-fileupload.js',
        'plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',



    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
