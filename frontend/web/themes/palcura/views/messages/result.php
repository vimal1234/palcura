<?php
use yii\widgets\Pjax;
use yii\helpers\Url;
$userId 		= Yii::$app->user->getId();
	 Pjax::begin(['id' => 'Pjax_SearchResults', 'timeout' => false, 'enablePushState' => false, 'clientOptions' => ['method' => 'GET']]);
?>
<div class="tab-content">
	<div id="home" class="tab-pane fade in active">
		<div class="delBlock">
			<div class="checkbox">
				<label>
					<input type="checkbox" value="" id="checkAll">
				</label>
			</div>
			<ul>
				<li><a href="javascript:void(0)" id="selectAll">Select All </a><span>|</span></li>
				<li><a href="javascript:void(0)" id="remove-conversation">Delete</a></li>
			</ul>
			<div class="flux top">
				<div class="scrolltoLeft selected"> scroll right <i class="fa fa-angle-double-right" aria-hidden="true"></i></div>
				<div class="scrolltoLeft1"><i class="fa fa-angle-double-left" aria-hidden="true"></i> scroll left </div>
			</div>
		</div>
		<div class="customTable">
			<table>
				<colgroup>
				<col width="250px">
				<col width="650px">
				<col width="100px">
				</colgroup>
				<?php
					if(isset($messages) && !empty($messages)) {
						foreach($messages as $m_row) { 
							$cl = '';
							if($m_row['is_read'] == 0) {
								$cl = "unread";
							}
							$chatID = $m_row['chat_id'];
							if($m_row['chat_id'] == 0) {
								$chatID = $m_row['id'];
							}
							
							if($m_row['user_from']	==	$userId) {
								$name = $m_row['uto_fname'].' '.$m_row['uto_lname'];
							} else {
								$name = $m_row['ufrom_fname'].' '.$m_row['ufrom_lname'];
							}
				?>
				<tr>
					<td class="<?= $cl ?>">
						<div class="checkbox">
							<label>
								<input type="checkbox" value="" id="<?= $chatID ?>" class="msgChk">
							</label>
						</div>
						<a href="<?=  Url::home().'messages/user-messaging/'.$chatID ?>">
							<?= $name ?>
						</a>
					</td>
					<td class="<?= $cl ?>">
						<a href="<?=  Url::home().'messages/user-messaging/'.$chatID ?>">
							<?= (isset($m_row['message']) ? Yii::$app->commonmethod->strsublen_complete($m_row['message'],0,60,1) : '') ?>
						</a>
					</td>
					<td class="<?= $cl ?>">
						<?= date("m-d-Y",strtotime($m_row['date_created'])) ?>
					</td>
				</tr>
				<?php
						}
					} else {
						echo '<p>'.NO_RESULT.'</p>';
					}
				?>
			</table>
		</div>
		<div class="flux">
			<div class="scrolltoLeft selected">scroll right <i class="fa fa-angle-double-right" aria-hidden="true"></i></div>
			<div class="scrolltoLeft1"><i class="fa fa-angle-double-left" aria-hidden="true"></i> scroll left </div>
		</div>
	</div>
	<div id="menu1" class="tab-pane fade">
		<p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
	</div>
</div>

<div class="customPagination">
		<?php
			echo yii\widgets\LinkPager::widget([
				'pagination' => $pages,
				'prevPageLabel' => '<i class="fa fa-caret-left" aria-hidden="true"></i>',
				'nextPageLabel' => '<i class="fa fa-caret-right" aria-hidden="true"></i>',
				'activePageCssClass' => 'active',
				'disabledPageCssClass' => 'disabled',
				'prevPageCssClass' => 'enable prev',
				'nextPageCssClass' => 'enable next',
				'hideOnSinglePage' => true
			]);
		?>
</div>

<?php Pjax::end(); ?>
