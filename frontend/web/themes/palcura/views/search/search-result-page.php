<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('yii','Search Results');

$searchpost = Yii::$app->session->get('searchpost');
$s_cnt = count($searchResult);
?>
<!-- mainheading  search box-->
<?php echo Yii::$app->view->renderFile(__DIR__ . '../../common/searchbox.php', ['searchString' => $searchString,'s_cnt' => $s_cnt]); ?>

<div class="searchresult">
    <div class="container">
        <div class="row">
		<?php if (Yii::$app->session->getFlash('item')): ?>
			  <div class="col-xs-12">
				 <div class="alert alert-grey alert-dismissible">
					   <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
					   </button>
					   <i class="glyphicon glyphicon-ok"></i><?php echo Yii::$app->session->getFlash('item'); ?>
				 </div>
			  </div>																
		<?php endif; ?>
            <div class="col-md-3 col-sm-4 col-xs-12">
                <?php echo $this->render('left_filter.php', ['searchString' => $searchString]); ?>
             </div>
            <div class="col-xs-12 col-sm-8 col-md-9" id="search-result">
			<?php echo $this->render('result.php', 
			['searchResult' => $searchResult,'pages' => $pages]); ?>
                
            </div>
        </div>
    </div>
</div>


<!--</div>
</section>-->


<link rel="stylesheet" href="<?php echo Yii::getAlias('@webThemeUrl'); ?>/css/jquery.rateyo.css"/>
<script src="<?php echo Yii::getAlias('@webThemeUrl'); ?>/js/jquery.rateyo.js"></script>

<script>
var searchdate = "<?php echo (isset($searchpost['searchdate'])?date('Y-m-d',strtotime($searchpost['searchdate'])):date('Y-m-d')); ?>";

var searchPost = {};
  
    $(document).ready(function(){
	var date_arr = searchdate.split('-');
        var sdate = new Date(date_arr[0],date_arr[1]-1,date_arr[2]);	
        var ctrlEle = document.querySelector('[ng-controller=DatepickerDemoCtrlSearch]');
        var ctrlScope = angular.element(ctrlEle).scope();
        ctrlScope.dtStart = sdate;
        setTimeout(function(){
            ctrlScope.$apply(function () {
                ctrlScope.dt = new Date(searchdate);
            });

            var scope = angular.element(document.getElementById("datepicker3")).scope();
            scope.$apply(function () {
                    scope.dt = new Date(searchdate);
            });

            searchPost.date = $('#filterByDate').val();

        },200);
		
		
		$('#filterByDate').on('change',function(){
	
			searchPost.date = $(this).val();
			filterSearchResult();
			//{'price':$(this).val()}
		});
		
		$('#filterByDays').on('change',function(){
			searchPost.days = $(this).val();
			filterSearchResult();
		});
		
		$('#filterByTime').on('change',function(){
			searchPost.time = $(this).val();
			filterSearchResult();
		});
		
		$('#filterByPrice').on('change',function(){
			searchPost.price = $(this).val();
			filterSearchResult();
			//{'price':$(this).val()}
		});
		
		$('#filterInterests').on('change',function(){
			var interestVal = $(this).val();
			searchPost.interest = interestVal;
			filterSearchResult();
			/*if(interestVal !='')
			{
				filterSearchResult({'interest':interestVal});
				
			}*/
			
		});
		
		$('#filterLanguages').on('change',function(){
			var langtVal = $(this).val();
			searchPost.language = langtVal;
			filterSearchResult();
			
		});		
		
		
	});
	
function filterSearchResult(postData){
	$.ajax({
				url:'<?php echo Url::to(['search/filter']);?>',
				type:'post',
				data:{'filter':searchPost},
				success:function(response){
					if(response)	
						$('#search-result').html(response);	
				}	
	});		
}
	
/*    
var appEle = document.querySelector('[ng-app=ui.bootstrap.demo]');
var appScope = angular.element(appEle).scope();
var controllerScope = appScope.$$DatepickerDemoCtrl;
*/

</script>
