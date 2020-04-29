<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Menulinks */

$this->title = 'Update Menulinks: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Menulinks', 'url' => ['menulinks/links/'.$menuID]];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

 <!-- page content -->
	<div class="right_col" role="main">

		<div class="">
			<div class="page-title">
				<div class="title_left">
					<h3>
		   <?= Html::encode($this->title) ?>
		</h3>
		 </div>
		   </div>
			<div class="clearfix"></div>

			<div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					 
					<div class="x_panel">
						<div class="x_title">
						<?php if(isset($data['respmesg'])) {?>	
						<div class="alert <?=$data['class']?> alert-dismissible fade in" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
							</button>
							<?php echo $data['respmesg'];?>
						</div>
						 <?php } ?>  
						   
						</div>
						<div class="x_content">
							  <p>Please fill out the following fields:</p>
						 
						  <?php $form = ActiveForm::begin(
						  [ 'id' => 'form-createuser',
							'options'=>['class'=>'form-horizontal form-label-left'],
							 'fieldConfig'=>[
								'template'=>"<div class=\"item form-group\">\n{label}\n<div class=\"col-md-6 col-sm-6 col-xs-12\">
											{input}<div class=\"col-lg-10\">
											{error}</div></div></div>",
								'labelOptions'=>['class'=>'control-label col-md-3'],
							],
						  ]); ?>
					
						  <?= $form->field($model, 'menu_id')->hiddenInput(['value'=>$menuID])->label(false); ?>						 
							<?php echo $form->field($model, 'title',['inputOptions'=>[
									'placeholder'=>'title(s) e.g Home',
									'class'=>"form-control col-md-7 col-xs-12",					
									]])->textInput(['autofocus' => true])->label('Name <span class="required">*</span>'); ?>
							<?php echo $form->field($model, 'Type',['inputOptions'=>[
									'class'=>"form-control col-md-7 col-xs-12 yt",
									
									]])->dropDownList(['' => 'Select Page Type' , '1' => 'Custom URL', '2' => 'Page'])->label('Type <span class="required">*</span>'); ?>										
								<div id="pageItems" class="pageNone">
									
								<?= $form->field($model, 'Page',['inputOptions'=>[
									'class'=>"form-control col-md-7 col-xs-12",
									]])->dropDownList( $list , ['class'=>'form-control'])->label('Page <span class="required">*</span>'); ?>
									 
								<?= $form->field($model, 'customPage',['inputOptions'=>[
									'class'=>"form-control col-md-7 col-xs-12",
									]])->dropDownList( $plist , ['prompt'=>'Select Parent']  , ['class'=>'form-control'])->label('Parent') ?> 
								</div>
								
							<div id="customUrl" class="pageNone">

								<?= $form->field($model, 'Custom',['inputOptions'=>[
									'class'=>"form-control col-md-7 col-xs-12",
									'placeholder'=>'Url(s) e.g http//.www.google.com',
									]])->textInput(['class'=>'form-control'])->label('Custom Url <span class="required">*</span>') ?>
								<?= $form->field($model, 'customURL',['inputOptions'=>[
									'class'=>"form-control col-md-7 col-xs-12",
									
									]])->dropDownList( $plist , ['prompt'=>'Select Parent']  , ['class'=>'form-control'])->label('Parent') ?> 
							</div>

							<div class="form-group">
								<div class="col-md-6 col-md-offset-3">
									<?= Html::submitButton( $model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary'] ) ?>
								</div>
							</div>
							

						<?php ActiveForm::end(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	  <!-- footer content -->
		<?php echo $this->render('../includes/footer'); ?>
	  <!-- /footer content -->
	   
	</div>
	<!-- /page content -->

 <!-- daterangepicker -->
	<script type="text/javascript" src="<?php echo Url::Home(); ?>/themes/gentelella/js/moment.min2.js"></script>
	<script type="text/javascript" src="<?php echo Url::Home(); ?>/themes/gentelella/js/datepicker/daterangepicker.js"></script>
        
     <!-- form validation -->
    <script src="../themes/gentelella/js/validator/validator.js"></script>
    <script type="text/javascript">
		
		 $(document).ready(function () {
			
					$('.date-picker').daterangepicker({
						singleDatePicker: true,
						calender_style: "picker_4"
					});
				
		});
              
              
        // initialize the validator function
        validator.message['date'] = 'not a real date';
      
        // validate a field on "blur" event, a 'select' on 'change' event & a '.reuired' classed multifield on 'keyup':
        $('form')
            .on('blur', 'input[required], input.optional, select.required', validator.checkField)
            .on('change', 'select.required', validator.checkField)
            .on('keypress', 'input[required][pattern]', validator.keypress);

        $('.multi.required')
            .on('keyup blur', 'input', function () {
                validator.checkField.apply($(this).siblings().last()[0]);
            });

        // bind the validation to the form submit event
        //$('#send').click('submit');//.prop('disabled', true);
//~ 
        //~ $('form').submit(function (e) {
            //~ e.preventDefault();
            //~ var submit = true;
            //~ // evaluate the form using generic validaing
            //~ if (!validator.checkAll($(this))) {
                //~ submit = false;
            //~ }
//~ 
            //~ if (submit){
				//~ $('#AddUser-submit').attr('disabled','disabled');
				//~ this.submit();
			//~ }
            //~ return false;
        //~ });

        /* FOR DEMO ONLY */
        $('#vfields').change(function () {
            $('form').toggleClass('mode2');
        }).prop('checked', false);

        $('#alerts').change(function () {
            validator.defaults.alerts = (this.checked) ? false : true;
            if (this.checked)
                $('form .alert').remove();
        }).prop('checked', false);
        
	           
    </script>
    
