<div class="searchleft">
	<div class="filter">
		<h4><?php echo Yii::t('yii','Filter by Availability');?></h4>
		<form class="filterinner">
			<div id="datepicker3" class="input-group date search" data-date-format="mm-dd-yyyy" ng-controller="DatepickerDemoCtrlSearch">
				<input type="text" class="form-control" uib-datepicker-popup="{{format}}" ng-model="dt" is-open="popup1.opened" datepicker-options="dateOptions" ng-required="true" close-text="Close" alt-input-formats="altInputFormats" ng-change="selectDate(dt)" id="filterByDate"/>
				<span class="input-group-addon" ng-click="open1()"><i class="fa fa-calendar" aria-hidden="true"></i> </span>
			</div>
			<select class="time textfeild validate[required]" id="filterByDays">
				<option value='0'><?php echo Yii::t('yii','Days');?></option>
				<?php
				for($i=1;$i<=7;$i++)
					echo "<option value='$i'>$i</option>";
				?>
			</select>
			
<!--
			<select class="time textfeild validate[required]" id="filterByTime">
				<option value='ANY'><?php //echo Yii::t('yii','Any Time');?></option>
				<option value='AM'><?php //echo Yii::t('yii','AM');?></option>
				<option value='PM'><?php //echo Yii::t('yii','PM');?></option>
			</select>
-->
			
		</form>
	</div>

	<div class="filter">
		<h4><?php echo Yii::t('yii','Filter by Price');?></h4>
		<form class="filterinner">
			<select id="filterByPrice" class="textfeild validate[required]">
				<option value="A"><?php echo Yii::t('yii','Low to High');?></option>
				<option value="D"><?php echo Yii::t('yii','High to Low');?></option>
			</select>
		</form>
	</div>
	<div class="filter">
		<h4><?php echo Yii::t('yii','Filter by interest');?></h4>
		<?php 
		$interests = frontend\models\Interests::find()->where(['status'=>'1'])->orderBy('name ASC')->all();
		?>
		<form class="filterinner">
			<select id="filterInterests" class="textfeild validate[required]">
				<option value=""><?php echo Yii::t('yii','Interests');?></option>
				<?php 
				if(!empty($interests)):
				foreach($interests as $interest) {
					echo "<option value='".$interest->id."'>".$interest->name."</option>";	
				}
				endif;
				?>
				
			</select>
		</form>
	</div>
	
	<div class="filter">
		<h4><?php echo Yii::t('yii','Filter by language');?></h4>
		<?php 
		$language  = frontend\models\Languages::find()->orderBy('name ASC')->all();
		//$interests = frontend\models\Interests::find()->where(['status'=>'1'])->all();
		?>
		<form class="filterinner">
			<select id="filterLanguages" class="textfeild validate[required]">
				<option value=""><?php echo Yii::t('yii','Language');?></option>
				<?php 
				if(!empty($language)):
				foreach($language as $lang) {
					echo "<option value='".$lang->short_name."'>".$lang->name."</option>";	
				}
				endif;
				?>
				
			</select>
		</form>
	</div>	
</div>
           
