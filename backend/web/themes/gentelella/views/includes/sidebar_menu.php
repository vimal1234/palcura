<?php
use yii\helpers\Url;
use yii\helpers\Html;
$adminArr = Yii::$app->user->identity;
?>
<div class="col-md-3 left_col">
    <div class="left_col"><!--scroll-view-->
        <div class="navbar nav_title" style="border: 0;">
            <a href="<?php echo Url::home(); ?>" class="site_title"><i class="fa fa-paw"></i> <span>PalCura</span></a>
        </div>
        <div class="clearfix"></div>
        <!-- menu prile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="<?php echo Url::home(); ?>themes/gentelella/images/logo.png" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                <h2><?php echo Yii::$app->user->identity->username; ?></h2>
            </div>
        </div>
        <!-- /menu prile quick info -->
        <br />
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>General</h3>
                <ul class="nav side-menu">
                    <!--  manage banner -->
<!--
                    <li><?php //echo Html::a('<i class="fa fa-picture-o"></i>Banner', Url::home() . 'banner', ['banner']); ?></li>
-->
					<!--  manage Admin setting -->
                    <li>
                        <a style="cursor:pointer"><i class="fa fa-cogs" aria-hidden="true"></i> Admin Settings <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu" style="display: none">
                           <!-- <li>
                                <?php //echo Html::a('<i class="fa fa-share-alt-square"></i>Social Icons', Url::home() . 'socialicons', ['socialicons']); ?>
                            </li> -->
                            <li>
                                <?php echo Html::a('<i class="fa fa-cog"></i>Basic settings', Url::home() . 'settings', ['settings']); ?>
                            </li>
                            <li>
                                <?php echo Html::a('<i class="fa fa-cog"></i>Export Database', Url::home() . 'settings/exportdatabase', ['settings']); ?>
                            </li>                                                                                                         
                        </ul>
                    </li>                    
                    <!--  manage Users -->
                    <li>
                        <a style="cursor:pointer"><i class="fa fa-users" aria-hidden="true"></i> Users <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu" style="display: none">
                            <li>
                                <?php echo Html::a('<i class="fa fa-user"></i>Owners', Url::home() . 'owners', ['owners']); ?>
                            </li>
                            <li>
                                <?php echo Html::a('<i class="fa fa-user"></i>Sitters', Url::home() . 'sitters', ['sitters']); ?>
                            </li>     
                            <li>
                                <?php echo Html::a('<i class="fa fa-user"></i>Renters', Url::home() . 'renters', ['renters']); ?>
                            </li> 
							<li>
                                <?php echo Html::a('<i class="fa fa-user"></i>File Subscribers', Url::home() . 'file-subscriber', ['file-subscriber']); ?>
                            </li>     							
                        </ul>
                    </li>
                    <!--  manage booking -->
                    <li><?php echo Html::a('<i class="fa fa-book"></i>Bookings', Url::home() . 'booking', ['booking']); ?></li>
                    <!--  manage booking -->
                    <li><?php echo Html::a('<i class="fa fa-book"></i>Coupons', Url::home() . 'coupon', ['coupon']); ?></li>
                    <!--  manage dispute -->
                    <li><?php echo Html::a('<i class="fa fa-gavel"></i>Dispute Resolution', Url::home() . 'dispute', ['dispute']); ?></li>                                        
                    <!--  messages Messages -->
                    <li><?php echo Html::a('<i class="fa fa-bar-chart"></i>Messages', Url::home() . 'messages', ['messages']); ?></li>
                    <!--  manage payments -->
                    <li><?php echo Html::a('<i class="fa fa-money"></i>Payments', Url::home() . 'payments', ['payments']); ?></li>                                         
                    <!--  manage payments disbursements -->
                    <li><?php echo Html::a('<i class="fa fa-exchange"></i>Payments Disbursements', Url::home() . 'disbursements', ['disbursements']); ?></li>                                         
                    <!--  manage services -->
                    <li><?php echo Html::a('<i class="fa fa-quote-left"></i>Services', Url::home() . 'services', ['services']); ?></li>
                    <!--  messages teaser -->
<!--
                    <li><?php //echo Html::a('<i class="fa fa-inbox"></i>Teaser', Url::home() . 'teaser', ['teaser']); ?></li>    
-->
                    <!--  manage Website Queries -->
                    <li><?php echo Html::a('<i class="fa fa-question-circle"></i>Website Queries', Url::home() . 'website-queries', ['website-queries']); ?></li>                                                        
					 <li><?php echo Html::a('<i class="fa fa-question-circle"></i>Services Inquiry', Url::home() . 'inquiry-services', ['inquiry-services']); ?></li>                                                        
				   <li><?php echo Html::a('<i class="fa fa-files-o"></i>Pages', Url::home() . 'page', ['page']); ?></li>
                </ul>
            </div>
        </div>
    </div>
</div>
