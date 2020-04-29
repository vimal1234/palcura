<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$this->title = Yii::t('yii','Search Results');
?>
<style>
/* Always set the map height explicitly to define the size of the div
* element that contains the map. */
#map_canvas {
height: 500px;
width: 500px;
}
/* Optional: Makes the sample page fill the window. */
html, body {
height: 100%;
margin: 0;
padding: 0;
}
</style>
<body>
		<div class="row">
			<div class="col-xs-12">
				<h1><?= $this->title ?></h1>
			</div>
		</div>
	</div>
</header>
<?php 
	echo $this->render('searchfilterrenter',[
			'searchResult' => $searchResult,
			'pages' => $pages,
			'model' => $model
		]);
?>

<section class="contentAreaResult">
	<div class="container">
		<div class="row" id="search-result">     
		<?php 
			echo $this->render('searchlistingrenter',[
					'searchResult' => $searchResult,
					'pages' => $pages,
					'sort_by' => $sort_by,
					'zipaddress' => $zipaddress,
				 ]); 
		?>
		</div>
		</div>
	</div>
</section>
<script>
//####= Instantiate the Bootstrap carousel
$('.multi-item-carousel').carousel({
  interval: false
});

$('.multi-item-carousel .item').each(function(){
	var next = $(this).next();
	if (!next.length) {
		next = $(this).siblings(':first');
	}
	next.children(':first-child').clone().appendTo($(this));
	if (next.next().length>0) {
		next.next().children(':first-child').clone().appendTo($(this));
	} else {
		$(this).siblings(':first').children(':first-child').clone().appendTo($(this));
	}
});
</script> 
<script>	
$( function() {
	$( "#slider-range" ).slider({
		range: true,
		min: 0,
		max: 500,
		values: [ 0, 300 ],
		slide: function( event, ui ) {
			$( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
		}
	});
	$( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
	" - $" + $( "#slider-range" ).slider( "values", 1 ) );
});
</script>
<script>

function searchWithSorting() {
	$("#palsearchfilter").trigger("click");	
}	
	
$(document).ready(function(){
	$('.searchfilter01').on('change',function(){	
			$("#palsearchfilter").trigger("click");	
	});

	var searchPost = {};        		
	$('#palsearchfilter').on('click',function(){ 
	  searchPost.amount 		= $('#amount').val();
	  searchPost.selected_pal 	= $('#selected_pal').val();
	  searchPost.pet_weight 	= $('#pet_weight').val();
	  searchPost.zip 			= $('#zip').val();
	  searchPost.service_type 	= $('#service_type').val();
	  searchPost.no_of_pals 	= $('#no_of_pals').val();
	  searchPost.date_from 		= $('#date_from').val();
	  searchPost.date_to 		= $('#date_to').val();
	  searchPost.sort_by 		= $('#sort_by').val();
	  filterSearchResult(searchPost);
	});
});

function filterSearchResult(searchPost){
	$.ajax({ 
		url:'<?php echo Url::to(['search/filter-renter']);?>',
		type:'post',
		data:{'filter':searchPost},
		success:function(response){
			if(response)	
				$('#search-result').html(response);	
		}	
	});		
}
</script> 
