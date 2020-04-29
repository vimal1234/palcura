<?php
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

<?php
	Pjax::begin(['id' => 'Pjax_SearchResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
	?>
	<ul class="personsearch">
	<?php
		$starRatingArray = array();
		if (empty($searchResult))
			echo "<li> No Result Found!</li>";
		else 
		{
			$i= 0;
			foreach ($searchResult as $searchData):
				$i++;
	
				$profile_picture = $searchData['usrProfileImage'];
				$profile_pic = (isset($profile_picture) ? PROFILE_IMAGE_PATH . $profile_picture : NO_IMAGE);
				
				$userCity = common\models\City::findOne(['id'=>$searchData['usrCity']]);
				/* User Interests */
				$interestsIds = explode(',',$searchData['usrInterests']);
				$userInterestsArry = frontend\models\Interests::find()->where(['IN','id',$interestsIds])->asArray()->all();
				$userInterests = '';
				if(!empty($userInterestsArry)) {
					foreach($userInterestsArry as $interest):
						$userInterests .= "<span class='tabing'>".ucfirst($interest['name']).'</span>';
					endforeach;	
					$userInterests = substr($userInterests,0,strlen($userInterests)-2);
				}
				/* User Languages Spoken */
				$languageIds = explode(',',$searchData['usrLanguage']);
				$languageArry = frontend\models\Languages::find()->where(['IN','short_name',$languageIds])->asArray()->all();
				$userLanguages = '';
				if(!empty($languageArry)) {
					foreach($languageArry as $language):
						$userLanguages .= "<span class='tabing'>".ucfirst($language['name']).'</span>';
					endforeach;	
					$userLanguages = substr($userLanguages,0,strlen($userLanguages)-2);
				}
				
				/* Count Reviews*/
				$totalReviews =  backend\models\feedback\feedback::find()->where(['receiver_userid'=>$searchData['id']])->count();
				
				/* Currency */
				$usrCurrencySign	 =	'$';
				if(isset($searchData['usrCurrency']) && !empty($searchData['usrCurrency'])) {
					$currencyArr 	 = common\models\Currency::findOne(['id'=>$searchData['usrCurrency']]);
					$usrCurrencySign = (isset($currencyArr['currency_sign']) ? $currencyArr['currency_sign'] : '');
				}
				
			?> 
				<li>
					<div class="media">
						<div class="media-left pull-left">
							<div class="searchimagewrapper"> <a href="<?php echo Url::to('site/guide-profile').'/'.$searchData['id']; ?>"><img src="<?php echo $profile_pic; ?>" alt="person" onerror="this.onerror=null;this.src='<?php echo NOIMAGE107x114;?>'"></a></div>
						</div>
						<div class="media-body">
							<div class="persondetail">
								<div class="persondetailleft">
									<h4>
										<a href="<?php echo Url::to('site/guide-profile').'/'.$searchData['id']; ?>"><?php echo $searchData['usrFirstname'] . ' ' . $searchData['usrLastname'] . ' '; ?><a>
									</h4>
									<h6><i class="fa fa-map-marker" aria-hidden="true"></i><?php echo (isset($searchData['usrCity'])? $searchData['usrCity'] : '--');?> <?php echo (isset($searchData['usrCountry'])? ', '.$searchData['usrCountry'] : '');?></h6>
								</div>
								<div class="persondetailright"> <?php echo $usrCurrencySign . $searchData['usrDayPrice'] . ' '. Yii::t('yii','per day'). '/' .$usrCurrencySign . $searchData['usrHourPrice'].' '.Yii::t('yii','per hour'); ?></div>
							</div>
							<div class="personinterest">
								<p><span class="tabtitle"><?php echo Yii::t('yii','Languages spoken'); ?>:</span> <span><?php echo $userLanguages; ?></span></p>
								<p><span class="tabtitle"><?php echo Yii::t('yii','interests'); ?>:</span> <span><?php echo $userInterests;?></span></p>
							</div>
							<div class="rating2">
								<?php if($totalReviews==0) {
									echo "<h6><a style='text-decoration:none;'>$totalReviews ".Yii::t('yii','reviews')."</a></h6>"; 
								}else {
								?>
								<h6><a href="<?php echo Url::to('site/guide-profile').'/'.$searchData['id']; ?>?reviews=1" target="_blank"><?=$totalReviews?> <?php echo Yii::t('yii','reviews'); ?></a></h6>
								  <?php } ?>
								<ul class="staricon star<?=$i?>">
								</ul>
							</div>
						</div>
					</div>
				</li>
			<?php
			
			if ($searchData['rating_total'] > 0 && $searchData['rating_outof_total'] > 0)
				$starRatingArray[] = 5 * ($searchData['rating_total'] / $searchData['rating_outof_total']);
			else
					$starRatingArray[] = 0;

			endforeach;
		}
		?>
	</ul>
	
	<nav class="paginationdesign">
		 <?php
		// display pagination
		if($pages !== null):
		echo yii\widgets\LinkPager::widget([
			'pagination' => $pages,
			'prevPageLabel' => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
			'nextPageLabel' => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
			'activePageCssClass' => 'active',
			'disabledPageCssClass' => 'disabled',
			'prevPageCssClass' => 'enable',
			'nextPageCssClass' => 'enable',

		]);
		endif;
		?>
	</nav>
	   <script>
		var starRatingArry = '<?php echo json_encode($starRatingArray); ?>';
	   
		starRatingArry = $.parseJSON(starRatingArry);

		$(document).ready(function () {
			var i = 0;
			$.each(starRatingArry, function (index, val) {
				i++;
				$(".staricon.star" + i).rateYo({
					rating: val,
					readOnly: true,
					starWidth: "14px",
					ratedFill: "#f88e49",
					normalFill: '#a7a6a6',
				});

			});


		});

	</script>  
<?php Pjax::end(); ?>
