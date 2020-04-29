<?php
use yii\helpers\Url;
use yii\helpers\Html;
?> 

            <div class="col-md-3 left_col">
                <div class="left_col"><!--scroll-view-->

                    <div class="navbar nav_title" style="border: 0;">
                        <a href="<?php echo Url::home();?>" class="site_title"><i class="fa fa-paw"></i> <span>Yii Admin!</span></a>
                    </div>
                    <div class="clearfix"></div>

                    <!-- menu prile quick info -->
                    <div class="profile">
                        <div class="profile_pic">
                            <img src="<?php echo Url::home();?>themes/gentelella/images/img.jpg" alt="..." class="img-circle profile_img">
                        </div>
                        <div class="profile_info">
                            <span>Welcome,</span>
                            <h2><?php echo Yii::$app->user->identity->username;?></h2>
                        </div>
                    </div>
                    <!-- /menu prile quick info -->

                    <br />

                    <!-- sidebar menu -->
                    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

                        <div class="menu_section">
                            <h3>General</h3>
                            <ul class="nav side-menu">
                               
                                <!--  Users -->
                                <li><?php echo Html::a('<i class="fa fa-users"></i> Users',Url::home().'users',['users']);?> 
                                </li>
                                
                                <!--  Page -->
                                <li><?php echo Html::a('<i class="fa fa-laptop"></i> Pages',Url::home().'page',['page']);?></li>

                            
								<!--  Menus -->
								<li><?php echo Html::a('<i class="fa fa-users"></i> Menus',Url::home().'menus',['menus']);?> 
                                </li>
                                
                                <!--  Coupon -->
								<li><?php echo Html::a('<i class="fa fa-users"></i> Coupons',Url::home().'coupon',['coupon']);?> 
                                </li>
                                
                                <!--  Category -->
								<li><?php echo Html::a('<i class="fa fa-windows"></i> Category',Url::home().'category',['category']);?>
								</li>                                   
                                
                                 <li><a style="cursor:pointer"><i class="fa fa-edit"></i> Main Menu <span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu" style="display: none">
                                        <li><a href="javascript:void(0)">Sub Menu1</a>
                                        </li>
                                        <li><a href="javascript:void(0)">Sub Menu2</a>
                                        </li>
                                        <li><a href="javascript:void(0)">Sub Menu3</a>
                                        </li>
                                        
                                    </ul>
                                </li>
                            
                            </ul>
                        </div>

                    </div>
                    <!-- /sidebar menu -->

                    <!-- /menu footer buttons -->
                    <!--<div class="sidebar-footer hidden-small">
                        <a data-toggle="tooltip" data-placement="top" title="Settings">
                            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="FullScreen">
                            <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Lock">
                            <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
                        </a>
                        <a data-toggle="tooltip" data-placement="top" title="Logout">
                            <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
                        </a>
                    </div> -->
                    <!-- /menu footer buttons -->
                </div>
            </div>
