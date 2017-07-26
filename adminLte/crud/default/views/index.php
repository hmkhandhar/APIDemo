<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */
//$viewname = StringHelper::basename($generator->viewPath);
$viewname= strtolower(Inflector::camel2words(StringHelper::basename($generator->modelClass)));
$title = Inflector::camel2words(StringHelper::basename($generator->viewPath));
$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\helpers\Url;
use <?= $generator->indexWidgetType === 'grid' ? "kartik\\grid\\GridView" : "yii\\widgets\\ListView" ?>;

/* @var $this yii\web\View */
<?= !empty($generator->searchModelClass) ? "/* @var \$searchModel " . ltrim($generator->searchModelClass, '\\') . " */\n" : '' ?>
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->viewPath)))) ?>;
$this->params['breadcrumbs'][] = $this->title;
?>

<script>
//function for change pagination
function dopagination(record)
{
    if(record != '')
    {
	$.ajax({
	type:"GET",
	url:"<?='<?php'?> echo Yii::$app->request->baseUrl;?>/admin/<?php echo $viewname?>/page",
	data:{size:record},    // multiple data sent using ajax
            success: function (result) {
                $.pjax.reload({container: '#w0-pjax', timeout: 2000});
                //setTimeout(function(){
                //    //reloadcheckbox();
                //},2001);
            }
	});
    }
}
$('body').on('click','.reload',function(){
    $.pjax.reload({container: '#w0-pjax'});
});
/*
 *for change status active/deactive
*/
function state(id,field,action)
{
    var id1= id;
    var val1 = field;
        $.ajax({
        type:"GET",
        url:"<?='<?php'?> echo Yii::$app->request->baseUrl;?>/admin/<?php echo $viewname?>/active",
        data:{id:id1,val:val1},    // multiple data sent using ajax
            success: function (result) {
                $.pjax.reload({container: '#w0-pjax', timeout: 2000});
            }
        });
}


$('body').on('click','input[type="checkbox"]',function(){
        if($(this).hasClass('select-on-check-all'))
        {
                //console.log(2);
                if ($(this).is(":checked")) {
                        $('input[type="checkbox"]').each(function(){
                            $(this).closest('span').addClass('checked');
                        });
                }
                else{
                        $('input[type="checkbox"]').each(function(){
                            $(this).closest('span').removeClass('checked');
                        });
                }
        }
        else{
                var chkcount = 0;
                var totalcount = 0;
                $('input[type="checkbox"]').each(function(){

                        totalcount++;
                        if($(this).attr("class") != "select-on-check-all"){

                                if($(this).is(":checked"))
                                chkcount ++;
                        }

                });
                if (totalcount-1==chkcount) {
                        $('.select-on-check-all').closest('th').find('div span').addClass('checked');
                        $('.select-on-check-all').prop('checked', true);
                }
                else{
                        $('.select-on-check-all').closest('th').find('div span').removeClass('checked');
                        $('.select-on-check-all').prop('checked', false);
                }
        }
});


/*function for activate,deactivate,delete all the selected recoreds*/
function submitForm()
{
    var strvalue = "";
    $('input[name="selection[]"]:checked').each(function() {
        if(strvalue!="")
            strvalue = strvalue + ","+this.value;
        else
            strvalue = this.value;
    });

    if(strvalue!="")
    {
        //document.getElementById('deactive').href = '<?php echo Yii::$app->request->baseUrl;?>/admin/<?php echo $viewname?>/change?str='+strvalue+'&&field=is_active&&val=N';
        //document.getElementById('active').href = '<?php echo Yii::$app->request->baseUrl;?>/admin/<?php echo $viewname?>/change?str='+strvalue+'&&field=is_active&&val=Y';
        document.getElementById('delete').href = '<?='<?php'?> echo Yii::$app->request->baseUrl;?>/admin/<?php echo $viewname?>/change?str='+strvalue+'&&field=is_deleted&&val=Y';
    }
    else
    {
		//document.getElementById('deactive').href = 'javascript:void(0);';
		document.getElementById('delete').href = 'javascript:void(0);';
		//document.getElementById('active').href = 'javascript:void(0);';
    }

}
//for delete signle user
function del(id,field)
{
    var a=confirm("Are you sure want to delete this data?");
    if (a) {
            var id1 = id;
            var field1 =field;
            $.ajax({type: "GET",
            url: "delete",
            data: { id: id1,field :field1},
            success:function(result){
            $.pjax.reload({container: '#w0-pjax', timeout: 2000});
            setTimeout(function(){
                    reloadcheckbox();

            },2001);
        }});
    }
}
</script>
<div class="content-wrapper">
    <section class="content-header">
        <h1><?= $title . " List" ?></h1>
				<ol class="breadcrumb">
					<li><i class="fa fa-dashboard"></i> <?="<?="?> Html::a("Home",["default/index"]) ?></li>
					<li><?="<?="?> Html::a('<?= $title ?>',["index"]) ?></li>
				    <li>List</li>
				</ol>
    </section>
    <section class="content">
            <div class="box box-primary">
                <!-- /.box-header -->
                <div class="box-body">

                    <div class="box-tools">
                       
                       <?="<?="?> Html::a(Yii::t('app', 'Create <?=$title?>'), ['create'], ['class' => 'addlink btn btn-primary pull-right margin-r-5']) ?>
                    </div>

                    <div class="actions pull-left filter-bottom-margin">
                        <div id="dynamic-table_length" class="dataTables_length">
				 
						<?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
								<label>
									<?="<?php"?>

										  $opt1 = Yii::$app->mycomponent->paginationarray();
										  $size=\Yii::$app->session->get('user.size');
										  if(isset($size) && $size!=null)
										  $searchModel->id=\Yii::$app->session->get('user.size');
										  else
										  $searchModel->id=5;
										  echo Html::activeDropDownList($searchModel, 'id',$opt1,
											  array('class'=>'tbl_top_link','onchange'=>'dopagination(this.value);','value'=>5,'label'=>false,'class'=>'m-wrap x-small va form-control','div'=>false)
										  );
									  ?> &nbsp;&nbsp;
										  <?="<?php"?> echo Html::a(Yii::t('app', 'Delete All').'<i class="icon-trash"></i>',"javascript:void(0);",["class"=>"btn btn-danger btn-cons  btn-sm",'data-placement'=>'bottom','title'=>'Delete All Records ','style'=>'margin-bottom:3px', "id" => "delete", "escape" => false, "onclick" => "submitForm();if(this.href=='javascript:void(0);') { alert('Please Select At least One Record');} else { if(!confirm('Are you sure to delete these records?')) return false;}"]); ?>&nbsp&nbsp;
	                            </label>
								
                            </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="table-responsive">

							
							<?php if ($generator->indexWidgetType === 'grid'): ?>
								<?= "<?= " ?>GridView::widget([
									'dataProvider' => $dataProvider,
									<?= !empty($generator->searchModelClass) ? "'filterModel' => \$searchModel,\n  'showPageSummary' => false, \n 'summary' => false, \n 'pjax'=>true,\n 'columns' => [\n" : "'columns' => [\n"; ?>
										[
											   'class' => '\kartik\grid\CheckboxColumn',
											   'width'=>'5%',
										],
										<?php
										$count = 0;
										if (($tableSchema = $generator->getTableSchema()) === false) {
											foreach ($generator->getColumnNames() as $viewname) {
												if (++$count < 6) {
													echo "            '" . $viewname . "',\n";
												} else {
													echo "            // '" . $viewname . "',\n";
												}
											}
										} else {
											foreach ($tableSchema->columns as $column) {
												$format = $generator->generateColumnFormat($column);
												if (++$count < 6) {
													echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
												} else {
													echo "            // '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
												}
											}
										}
										?>
										[
											'width'=>'20%',
											'attribute'=>'is_active',
											'label'=>'Status',
											'class' => '\kartik\grid\DataColumn',
											'headerOptions' => ['style' => 'text-align:center'],
											'contentOptions' => ['style' => 'text-align:center'],
											'pageSummary' => false,
											'format' => 'raw',
											'filter'=>['Y'=>Yii::t('app', 'Yes'),'N'=>Yii::t('app', 'No')],
											'value' => function($data)
											{
												return ($data->is_active=="Y")?'<a class="label label-success"  id="'.$data->id.'" field="N" onclick="state('.$data->id.',\'N\''.')">Active</a>':
													'<a class="label label-danger" id="'.$data->id.'" field="Y" onclick="state('.$data->id.',\'Y\''.')">Inactive</a>';

											},
										],
										[
											'class' => '\kartik\grid\ActionColumn',
											'contentOptions' => ['style' => ''],
											'headerOptions' => ['style' => 'text-align:center'],
											'template' => '{update}&nbsp;{delete}', //{view}&nbsp;
											'buttons' =>
													[
														'view' => function ($url, $model)
														{
															return Html::a('<button class="btn btn-default btn-sm btn-icon-only ajaxupdate"><i class="fa fa-eye"></i></button>', $url, [
																'title' => Yii::t('app', 'Profile'),'data-pjax' => true
															]);

															
                                                       
														},
														'update' => function ($url, $model)
														{
															return Html::a('<button class="btn btn-default btn-sm btn-icon-only ajaxupdate"><i class="fa fa-pencil"></i></button>', $url, [
															'title' => Yii::t('app', 'update'),'data-pjax' => true
															]);

															

														},
														'delete' => function ($url, $model)
														{
															return '<button class="btn btn-default btn-sm btn-icon-only delete" id="'.$model->id.'" field="Y" onclick="del('.$model->id.',\'Y\''.')"><i class="fa fa-trash-o"></i></button>';

															
														}
													],
													'urlCreator' => function ($action, $model, $key, $index) {
														if ($action === 'view') {
																$url =Yii::$app->request->baseurl.'/admin/<?php echo $viewname?>/view?id='.$model->id;
																return $url;
														}
														if ($action === 'update') {
																$url = Yii::$app->request->baseurl.'/admin/<?php echo $viewname?>/update?id='.$model->id;
																return $url;
														}
														if ($action === 'delete') {
																$url ='delete?id='.$model->id;
																return $url;
														}
													}
										],
									],
								]); ?>
							<?php else: ?>
								<?= "<?= " ?>ListView::widget([
									'dataProvider' => $dataProvider,
									'itemOptions' => ['class' => 'item'],
									'itemView' => function ($model, $key, $index, $widget) {
										return Html::a(Html::encode($model-><?= $nameAttribute ?>), ['view', <?= $urlParams ?>]);
									},
								]) ?>
							<?php endif; ?>
                            
                    </div>

                </div>
            </div>
    </section>
</div>


