<?php
use yii\widgets\Pjax;
use yii\helpers\Url;
$userId 		= Yii::$app->user->getId();
if(isset($messages) && !empty($messages)) {
$count = 0;
	foreach($messages as $m_row) {
	$count++;
		if($userId == $m_row['user_from']) {
			$cl = 'yellowBox';
		} else {
			$cl = '';
		}
?>

	<div class="whiteBox <?= $cl ?>">
		<p><?= $m_row['message'] ?></p>
		<ul>
			<li ><a id="newdate<?php echo $count;?>"> <!--?= //date('m-d-Y', strtotime($m_row['date_created'])) ?--> </a><span>|</span></li>
			<li><a id="lblTime<?php echo $count;?>"> <!--?= date('H:i a', strtotime($m_row['date_created'])) ?--></a></li>
		</ul>
		<p class="cleintname"> <?= $m_row['ufrom_fname'].' '.$m_row['ufrom_lname'] ?> </p>
	</div>
<script>
	//var offset = new Date().getTimezoneOffset();
var dateFromDb = "<?php echo strtotime($m_row['date_created']); ?>";
var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];	
 var Messagenow = new Date(dateFromDb * 1000);
 Messagenow.setMinutes(Messagenow.getMinutes());
 var day = Messagenow.getDate();
 var monthIndex = Messagenow.getMonth();
 var year = Messagenow.getFullYear();
 var msgdate =  monthNames[monthIndex] + ' ' + day + ' ' + year;
 $('a#newdate'+"<?php echo $count;?>").text(msgdate);
 DisplayCurrentTime(Messagenow); 
 function DisplayCurrentTime(Msgdate) {
        var date = Msgdate;
        var hours = date.getHours() > 12 ? date.getHours() - 12 : date.getHours();       
        var am_pm = date.getHours() >= 12 ? "PM" : "AM";
        hours = hours < 10 ? "0" + hours : hours;
        var minutes = date.getMinutes() < 10 ? "0" + date.getMinutes() : date.getMinutes();
        var seconds = date.getSeconds() < 10 ? "0" + date.getSeconds() : date.getSeconds();
        time = hours + ":" + minutes + " " + am_pm;        
        $('a#lblTime'+"<?php echo $count;?>").text(time);
       
    };
</script>		
<?php
	}
}
?>
<style>
 .greyBox.messageBox {
  max-height: 350px;
  overflow-y: scroll;
}
</style>

<script>
//var dateFromDb = "<?php echo strtotime($m_row['date_created']); ?>";
//var Messagenow = new Date(dateFromDb * 1000);

//alert(Messagenow);
//Messagenow.setMinutes(Messagenow.getMinutes() + 20);
//alert(Messagenow);
/*function formatDate(date) {
  var monthNames = [
    "January", "February", "March",
    "April", "May", "June", "July",
    "August", "September", "October",
    "November", "December"
  ];

  var day = date.getDate();
  var monthIndex = date.getMonth();
  var year = date.getFullYear();

  return day + ' ' + monthNames[monthIndex] + ' ' + year;
}*/
</script>
