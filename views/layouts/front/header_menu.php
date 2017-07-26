<?php

use yii\helpers\Html;
?>
<div class="page-header-menu">
    <div class="container">
        <div class="hor-menu  ">
            <ul class="nav navbar-nav">
                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown active">                
                <?= Html::a('<span> '.Yii::t('app','Dashboard').'</span>',["/site/index"]) ?>        
                </li>
                 <?php if($isLoggedin){ ?>   
                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                    <a href="javascript:;"> Product
                        <span class="arrow"></span>
                    </a>
                    <ul class="dropdown-menu pull-left">
                        <li aria-haspopup="true" class=" ">
                            <?= Html::a('<span> '.Yii::t('app','View').'</span>',["/product/index"]) ?>
                        </li>
                        
                        <li aria-haspopup="true" class=" ">
                            <?= Html::a('<span> '.Yii::t('app','Add New').'</span>',["/product/create"]) ?>
                        </li>                            
                    </ul>
                </li>
                
                <!-- <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                    <a href="javascript:;"> Student
                        <span class="arrow"></span>
                    </a>
                    <ul class="dropdown-menu pull-left">
                        <li aria-haspopup="true" class=" ">                          
                            <?= Html::a('<span> '.Yii::t('app','View').'</span>',["/student/index"]) ?>
                        </li>
                        <li aria-haspopup="true" class=" ">
                            <?= Html::a('<span> '.Yii::t('app','Add New').'</span>',["/student/create"]) ?>
                        </li>
                             
                        <li aria-haspopup="true" class=" ">
                            <?= Html::a('<span> '.Yii::t('app','Search').'</span>',["/student/find"]) ?>
                        </li>
                    </ul>
                </li> -->
                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                <?= Html::a('<span> '.Yii::t('app','About Us').'</span>',["/site/about"]) ?>                            
                </li>
                <li aria-haspopup="true" class="menu-dropdown classic-menu-dropdown ">
                <?= Html::a('<span> '.Yii::t('app','Contact').'</span>',["/site/contact"]) ?>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
</div>                  
</div>
</div>