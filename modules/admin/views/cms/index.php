<?php

use yii\helpers\Html;
use yii\grid\GridView;
// use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::$app->params['apptitle'].' : CMS Page List';
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
	url:"page",
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
        url:"active",
        data:{id:id1,val:val1},    // multiple data sent using ajax
            success: function (result) {
                $.pjax.reload({container: '#w0-pjax', timeout: 2000});
            }
        });
}

/*
 *for change status active/deactive
*/    
function followdefault(id,value)
{
    var id1= id;
    var val1 = value;
        $.ajax({
        type:"GET",
        url:"followdefault",
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


function reloadcheckbox()
{
    if (!jQuery().uniform) {
        return;
    }
    var test = $("input[type=checkbox]:not(.toggle), input[type=radio]:not(.toggle, .star)");
    if (test.size() > 0) {
        test.each(function () {
                if ($(this).parents(".checker").size() == 0) {
                    $(this).show();
                    $(this).uniform();
                }
            });
    }
}
$( document ).ajaxComplete(function() {
    reloadcheckbox();
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
        //document.getElementById('deactive').href = '<?php echo Yii::$app->request->baseUrl;?>/users/change?str='+strvalue+'&&field=is_active&&val=N';
        //document.getElementById('active').href = '<?php echo Yii::$app->request->baseUrl;?>/users/change?str='+strvalue+'&&field=is_active&&val=Y';
        document.getElementById('delete').href = '<?php echo Yii::$app->request->baseUrl;?>/users/change?str='+strvalue+'&&field=is_deleted&&val=Y';
    }
    else
    {
	//document.getElementById('deactive').href = 'javascript:void(0);';
	document.getElementById('delete').href = 'javascript:void(0);';
	//document.getElementById('active').href = 'javascript:void(0);';
    }
    
}
//for delete signle user
//function del(id,field)
//{
    //var a=confirm("Are you sure want to delete this data?");
    //if (a) {
    //        var id1 = id;
    //        var field1 =field;
    //        $.ajax({type: "GET",
    //        url: "delete",
    //        data: { id: id1,field :field1},
    //        success:function(result){
    //        $.pjax.reload({container: '#w0-pjax', timeout: 2000});
    //        setTimeout(function(){
    //                reloadcheckbox();
    //        },2001);
    //    }});
    //}
//}
</script>

<!-- <div class="content-wrapper"> -->
	<section class="content-header">
    <?php echo Yii::$app->getSession()->getFlash('flash_msg');?>
        <h1>
            CMS
            <small>Control panel</small>
        </h1>
</section>
<section class="content col-lg-11">      
    <div class="box box-primary">
                     
                      <section class="panel">
                          <header class="panel-heading">
                            <?php
                                echo 'CMS Page List';
                            ?>
							
                          </header>
                          <div class="panel-body">
							<?php
								echo Yii::$app->getSession()->getFlash('flash_msg');
							?>
                          <div class="span6">
                            <div id="dynamic-table_length" class="dataTables_length">
                          <label>
                          <?php
                                // $opt1 = Yii::$app->common->paginationarray();
                                $size=\Yii::$app->session->get('user.size');
                                if(isset($size) && $size!=null)
                                $searchModel->id=\Yii::$app->session->get('user.size');
                                else
                                $searchModel->id=5;
                                echo Html::activeDropDownList($searchModel, 'id',
                                    array('class'=>'tbl_top_link','onchange'=>'dopagination(this.value);','value'=>5,'label'=>false,'class'=>'m-wrap x-small va form-control','div'=>false)
                                );
                            ?> &nbsp;&nbsp;
                                <?php //echo Html::a(Yii::t('app', 'Delete All').'<i class="icon-trash"></i>',"javascript:void(0);",["class"=>"btn btn-danger btn-cons  btn-sm",'data-placement'=>'bottom','title'=>'Delete All User ','style'=>'margin-bottom:3px', "id" => "delete", "escape" => false, "onclick" => "submitForm();if(this.href=='javascript:void(0);') { alert('Please Select At least One Record');} else { if(!confirm('Are you sure to delete these records?')) return false;}"]); ?>&nbsp&nbsp
                                <?php //echo Html::a(Yii::t('app', 'Active All'),"javascript:void(0);",["class"=>"btn btn-success btn-cons  btn-sm",'data-placement'=>'bottom','title'=>'Active All User ','style'=>'margin-bottom:3px', "id" => "active", "escape" => false, "onclick" => "submitForm();if(this.href=='javascript:void(0);') { alert('Please Select At least One Record');} else { }"]); ?>&nbsp&nbsp
                                <?php //echo Html::a(Yii::t('app', 'Deactive All'),"javascript:void(0);",["class"=>"btn btn-danger btn-cons  btn-sm",'data-placement'=>'bottom','title'=>'Deactive All User ','style'=>'margin-bottom:3px', "id" => "deactive", "escape" => false, "onclick" => "submitForm();if(this.href=='javascript:void(0);') { alert('Please Select At least One Record');} else { }"]); ?>&nbsp&nbsp
                            </label>
						  <!--<a id="add" class="btn btn-primary btn-cons  btn-sm" href="/adminLte/web/cms/create" title="Add New CMS Page " data-placement="bottom" style="margin-bottom:3px">Add New CMS<i class="icon-plus"></i></a>-->
                            </div>
                          <!--<table class="table table-striped table-advance table-hover">-->
                          <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            // 'filterModel' => $searchModel,                        
                            'summary' => false,
                            'columns' => [
                            [
                                'attribute'=>'id',
                                // 'class' => 'kartik\grid\SerialColumn',
                                // 'width'=>'5%',
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'title',
                                // 'class' => '\kartik\grid\DataColumn',
                                'headerOptions' => ['style' => 'text-align:center'],
                                'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                            ],
                            [
                                // 'width'=>'15%',
                                'attribute'=>'content',
                                // 'class' => '\kartik\grid\DataColumn',
                                'headerOptions' => ['style' => 'text-align:center'],
                                // 'contentOptions' => ['style' => 'text-align:center;vertical-align:middle'],
                                'filter'=>true,
                                'contentOptions' => ['style' => 'max-width:750px;'],
                            ],                    
                            [
                                // 'width'=>'15%',
                                'class' => 'yii\grid\ActionColumn',
                                'contentOptions' => ['style' => 'max-width:150px;'],
                            ],
                        ]
                        ]); ?>







                            	
                          </div>
                      </section>
                  </div>
              </div>

</div>
          </section>
    </div>
