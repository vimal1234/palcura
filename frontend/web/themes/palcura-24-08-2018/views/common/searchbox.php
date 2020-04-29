<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
?>
<div class="mainheading"> <!-- nogaparea -->
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 col-sm-12 col-sm-offset-0">
                <?php
                $form = ActiveForm::begin(
                    [ 'id' => 'search-form', 'method' => 'post', 'action' => Url::to(['search/']),
                        'fieldConfig' => [],
                ]);
                ?>
                
                <div class="input-group view">
                    <input type="text" id="search_destination" class="form-control textfeild validate[required]" name="Search[search_destination]" placeholder="Destination" onFocus="geolocate()">
                    <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i> </span>
                </div>   
                <div  class="input-group date" ng-controller="DatepickerDemoCtrlSearch">
                    <input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="dtStart" is-open="popup1.opened" datepicker-options="dateOptions" ng-required="true" close-text="Close" alt-input-formats="altInputFormats" name="Search[searchdate]" readonly/>
                    <span class="input-group-addon" ng-click="open1()"><i class="fa fa-calendar" aria-hidden="true"></i> </span>
                </div>
                <select class="travellers" name="Search[travellers]">
                    <option value="2">2 <?php echo Yii::t('yii', 'Travelers'); ?></option>
                    <option value="3">3 <?php echo Yii::t('yii', 'Travelers'); ?></option>
                    <option value="4">4 <?php echo Yii::t('yii', 'Travelers'); ?></option>
                    <option value="5">5 <?php echo Yii::t('yii', 'Travelers'); ?></option>
                    <option value="6">6 <?php echo Yii::t('yii', 'Travelers'); ?></option>
                    <option value="7">7 <?php echo Yii::t('yii', 'Travelers'); ?></option>
                    <option value="8">8 <?php echo Yii::t('yii', 'Travelers'); ?></option>
                    <option value="9">9 <?php echo Yii::t('yii', 'Travelers'); ?></option>
                    <option value="10+">10+ <?php echo Yii::t('yii', 'Travelers'); ?></option>
                </select>
                <button type="submit" class="btn btn-primary"><?php echo Yii::t('yii', 'Search'); ?></button>

                <input type="hidden" name="Search[city]" id="search_city"/>
                <input type="hidden" name="Search[state]" id="search_state"/>
                <input type="hidden" name="Search[country]" id="search_country"/>
                <input type="hidden" name="Search[country_sortname]" id="country_sortname"/>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <?php
    if(!empty($searchString) ) :
    ?>
        <div class="bordertopwhite">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        <div class="headinginner"> 
                            <?php echo Yii::t('yii','SEARCH RESULTS FOR'); ?>
                            <?= "“" . $searchString . "”"; ?>
                            <?php echo "(".$s_cnt.")"; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php    
    endif;
    ?>
    
    
    <?php
    if(Yii::$app->controller->action->id !== 'search-guide' && Yii::$app->controller->id !== 'cms')###HIDES THE SEARCH BAR SAYING "SEARCH RESULTS"
    {
    ?>
        <div class="bordertopwhite">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12 ">
                        <div class="headinginner">
                            <?= $this->title; ?>			
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php    
    }
    ?>
    
    
    
</div>

<script src="<?php echo Yii::getAlias('@webThemeUrl'); ?>/js/places-autocomplete.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Yii::$app->params['gmap_api_key']; ?>&libraries=places&callback=initAutocomplete" async defer></script>
