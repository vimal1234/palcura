<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$this->title 	= Yii::t('yii','Messages');
$attributes 	= Yii::$app->user->identity->getattributes();
$userId 		= Yii::$app->user->getId();
?>
		<div class="row">
			<div class="col-xs-12">
				<h1><?= $this->title ?></h1>
			</div>
		</div>
	</div>
</header>
<section class="contentArea">
	<div class="container">
		<div class="row">
			<?php echo $this->render('//common/sidebar'); ?>
			<div class="col-md-10 col-sm-9 col-xs-12 scrolldiv01">
				<div class="tabHead">
					<ul class="nav nav-tabs">
						<li class="active"><a data-toggle="tab" href="#home">Messages</a></li>
					</ul>
					<div class="searchBox">
						<div class="form-group">
							<input class="form-control" placeholder="Search items..." type="text" id="messageSearch">
							<button type="submit" id="searchBTN" class="btn btn-default">
								<i class="fa fa-search" aria-hidden="true"></i>
							</button>
						</div>
					</div>
					<div class="messageBlk"> <?= (isset($unread_count) && $unread_count > 0 ? $unread_count.' Unread' : '') ?> </div>
				</div>
				<div id="search-result">
				<?php echo $this->render('result.php',['messages' => $messages,'pages' => $pages]); ?>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
	jQuery(function($) {
		$('.customTable').bind('scroll', function() {
			if($(this).scrollLeft() + $(this).innerWidth()>=$(this)[0].scrollWidth) {
				$( ".scrolltoLeft" ).removeClass( "selected" );
				$( ".scrolltoLeft1" ).addClass( "selected" );
			} else {
				$( ".scrolltoLeft1" ).removeClass( "selected" );
				$( ".scrolltoLeft" ).addClass( "selected" );
			}
		})
	});

	var searchPost = {};
	$(document).ready(function(){
		$('#messageSearch').on('change',function(){
			var searchval = $("#messageSearch").val();
			searchPost.search_value = searchval;
			filterSearchResult();
		});

		$('#searchBTN').on('click',function(){
			var searchval = $("#messageSearch").val();
			searchPost.search_value = searchval;
			filterSearchResult();
		});


	});

	function filterSearchResult(postData) {
		$.ajax({
			url:'<?php echo Url::to(['messages/search-messages']);?>',
			type:'post',
			data:{'message':searchPost},
			success:function(response) {
				if(response)	
					$('#search-result').html(response);	
			}
		});
	}
		
</script>
