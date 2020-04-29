<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use common\models\User;

$this->title 	= Yii::t('yii','Video call');
$siteimage  	= Yii::getAlias('@siteimage');
$sessioncreatedtime = $sessioncreatedat;

$year = date('Y', strtotime($sessioncreatedat));
$month = date('m',strtotime($sessioncreatedat));
$date = date('d',strtotime($sessioncreatedat));
$hours = date('H',strtotime($sessioncreatedat));
$minutes = date('i',strtotime($sessioncreatedat));
$seconds = date('s',strtotime($sessioncreatedat));

?>
 <script src="https://static.opentok.com/v2/js/opentok.min.js"></script>

    <div class="row">
      <div class="col-xs-12">
        <h1><?= $this->title ?></h1>
      </div>
    </div>
  </div>
</header>
<section class="contentArea contentAreaPayments">
  <div class="container">
    <div class="row">
    <div class="alert alert-grey alert-dismissible" style="display:none;" id="paymentrequest">
		<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span>
		</button>
		<i class="fa fa-check"></i>
	</div>
	 <?php echo $this->render('//common/sidebar'); ?>
			  	
		   <div class="col-md-9 col-sm-7 col-xs-12">
		   		<div id="time-elapsed"></div>	  
				<div id="countdowntimer"><span id="future_date"><span></div>
				
		  </div>
		  
		   <div class="col-md-9 col-sm-7 col-xs-12">  
			<div id="videos">
				<div id="subscriber"></div>        
				<div id="publisher"></div>        
			</div>
			<a href="#" id="disconnectsession" class="btn btn-primary right" >Disconnect</a>
		  </div>  
  </div>
     
  </div>
  
</section> 

<!--Boootstrap modal start-->
<div class="container">
  <!--h2>Modal Login Example</h2-->
  <!-- Trigger the modal with a button -->
  <!--button type="button" class="btn btn-default btn-lg" id="myBtn">Login</button-->

  <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
          <h4><span class="glyphicon glyphicon-phone-alt"></span> Extend Your Video Session</h4>
        </div>
        <div class="modal-body" style="padding:40px 50px;">
        
		    <div  id="loader"  align="center" style="display:none;">        
		    <img class="img-responsive" src="<?php echo SITE_URL; ?>common/uploads/loader/giphy.gif" alt="" width="70px" height="70px">
		    </div>
		    
          <form role="form" id="extendsession">
   
            <div class="form-group">
              <label for="usrname"><span class="glyphicon glyphicon-time"></span> Minutes</label>
          
              <select name="temp_extendses_min" class = "form-control customwidth" id="extended_min">
              <option value="">Choose...</option>
              <option value="10">10 minutes</option>
              <option value="20">20 minutes</option>
              <option value="30">30 minutes</option>
              </select>
            
            </div>
            <div class="form-group">
              <label for="amount"><span class="glyphicon glyphicon-usd"></span> Amount</label>
              <input type="number" id="amount" class="form-control customwidth" name="amount" value="">
              
              
            </div>
            <!--div class="checkbox">
              <label><input type="checkbox" value="" checked>Remember me</label>
            </div-->            
              <a  class="btn btn-success btn-block" id="paybywallet"><span class="glyphicon glyphicon-credit-card"></span> Pay By Wallet</a>
          </form>
         
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal" id="cancelModal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
          <!--p>Not a member? <a href="#">Sign Up</a></p>
          <p>Forgot <a href="#">Password?</a></p-->
        </div>
      </div>
      
    </div>
  </div> 
  
  
  <div class="modal fade" id="doctorModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style="padding:35px 50px;">
          <!--button type="button" class="close" data-dismiss="modal">&times;</button-->
          <h4><span class="glyphicon glyphicon-check"></span>Please wait while other user joins the session...</h4>
           <!--button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal" id="declinemycall"><span class="glyphicon glyphicon-remove"></span>Decline</button-->
        </div>
        <!--div class="modal-body" style="padding:40px 50px;">
         
         <img src="<?php echo WEBSITE_IMAGES_PATH ?>phonecall.gif" alt="" height="100px;">
        </div-->
        <!--div class="modal-footer">
          <!--button type="submit" class="btn btn-danger btn-default pull-left" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button-->
         
          <!--p>Not a member? <a href="#">Sign Up</a></p>
          <p>Forgot <a href="#">Password?</a></p>
        </div-->
      </div>
      
    </div>
  </div> 
  
</div>

<!--Bootstrap modal end-->

<!--footer start-->

<!--footer end -->
   <script>  
   var apiKey = "<?php echo $apiKey ?>";
   var sessionId = "<?php echo $sessionId ?>";
   var token = "<?php echo $token ?>";
   var usertype = "<?php echo Yii::$app->user->identity->user_type ?>";
   var timerstatus=0;
	// Handling all of our errors here by alerting them
	function handleError(error) {
	  if (error) {	  
		console.log(error.message);
	  }
	}

	// (optional) add server code here
	if(sessionId === '' || sessionId == null){
	 timerstatus=0;
	 localStorage.clear();
	$("#doctorModal").modal({"backdrop": "static"});
	 var getuserscallstatus = setInterval(getcallacceptstatus, 1000);
	 
	 /* $('#declinemycall').on('click',function(){
	  			clearInterval(getuserscallstatus);	 
			   		$.ajax({
					url:siteUrl+'/video/declinecall',
					type:'post',					
					data:{'vid_id':'<?php echo $_GET["id"]?>'},	
					success:function(response) {
					 $('#doctorModal').modal('hide');
					 window.location.href = '<?php echo SITE_URL ?>'+'video/index';
					}
			      });
			   
			   
			   });*/
			   
	}else{
	
	initializeSession();
	 timerstatus=1;
	}
	
	 function getcallacceptstatus(){
						$.ajax({
							type:'POST',
							url:'<?php echo SITE_URL ?>video/getcallresponse',
							data:{'temp_id':'<?php echo $_GET["id"]?>'},		
								success:function(res){
								if(res=='accepted'){										
								clearInterval(getuserscallstatus);						
								window.location.href = '<?php echo SITE_URL ?>'+'video/talk/'+'<?php echo $_GET["id"]?>';
								}
								if(res=='decline'){
								alert('your call was declined');
								window.location.href = '<?php echo SITE_URL ?>'+'video/index';
								}								
							}

							});
						}
	
  function initializeSession() {
 	var session = OT.initSession(apiKey, sessionId);	
  // Subscribe to a newly created stream
  	session.on('streamCreated', function(event) {
    session.subscribe(event.stream, 'subscriber', {
      insertMode: 'append',
      width: '100%',
      height: '90%'
    }, handleError);
  });

  // Create a publisher
  var publisher = OT.initPublisher('publisher', {
    insertMode: 'append',
    width: '100%',
    height: '100%'
  }, handleError);
  
  // var secondsLabel = document.getElementById("seconds");
	
  // Connect to the session
   session.connect(token, function(error) {
   	//var totalSeconds = "<?php echo $sessioncreatedat ?>";
    // If the connection is successful, initialize a publisher and publish to the session
    if (error) {
      handleError(error);
    } else {
      session.publish(publisher, handleError);
    }  
    
    var disconnectSession = setInterval(disconnectAfter,3000);
    var refreshIntervalId = setInterval(getSessiontime, 1000);    
    
  
    //time elapsed calculation
	/*function setTime()
        {
           ++totalSeconds;
         secondsLabel.innerHTML = pad(totalSeconds);
         // console.log(secondsLabel);
           // minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));
        }  
  
    function pad(val)
        {
            var valString = val + "";
            if(valString.length < 2)
            {
                return "0" + valString;
            }
            else
            {
                return valString;
            }
        }*/
        
       
        
        
        
			function getSessiontime(){
			 var now = new Date();
			 now = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
			var createdDatetime = "<?php echo $sessioncreatedat ?>";
	
			$.ajax({
					type:'POST',
					url:'<?php echo SITE_URL ?>video/comparesession',
					data:{'tempid':"<?php echo $temp_id ?>",'createdDatetime':createdDatetime},		
						success:function(response){
						//alert(response);
						
						if(response==1){
						clearInterval(refreshIntervalId);
						alert('The session disconnected');				
						clearInterval(refreshIntervalId);
						session.disconnect();						
						}else{						
						return true;
						}
						//return response;
						//alert('You are Disconnected');                              
						//window.location.href = '<?php echo SITE_URL ?>'+'message/messages';                   
						}
				});
	
			}
			
			//function to finally disconnect the session
			function disconnectAfter() {	
	
	 		var now = new Date();
			 now = now.getHours() + ':' + now.getMinutes() + ':' + now.getSeconds();
			var createdDatetime = "<?php echo $sessioncreatedat ?>";
	
			$.ajax({
					type:'POST',
					url:'<?php echo SITE_URL ?>video/destroysession',
					data:{'duration':"<?php echo $duration ?>",'createdDatetime':createdDatetime},		
						success:function(response){
				
							if(response==1){					
							clearInterval(disconnectSession);
							session.disconnect();
							return true;										
							}else{							
							return true;
							}
						                 
						}
				});
						
		 	}
        
  	});
   
	session.on("sessionDisconnected", function(event) {
	//alert("The session disconnected. " + event.reason);
		localStorage.clear();
	var session_temp_id = "<?php echo $temp_id ?>";
	var vid_id = "<?php echo $vid_id; ?>";
		$.ajax({
			type:'POST',
			url:'<?php echo SITE_URL ?>video/disconnect',
			data:{'temp_id':session_temp_id,'vid_id':vid_id},		
				success:function(extendsession){									
						window.location.href = '<?php echo SITE_URL ?>'+'video/index'; 				              
				}
		});
		
	});

	$('#disconnectsession').click(function(){	
	
		session.disconnect();
				
	});

}

var startDateTime = new Date(<?php echo $year ?>,<?php echo $month-1 ?>,<?php echo $date; ?>,<?php echo $hours ?>,<?php echo $minutes ?>,<?php echo $seconds ?>,0); // YYYY (M-1) D H m s (start time and date from DB)
//var startDateTime = new Date();
var startStamp = startDateTime.getTime();

var newDate = new Date();

var newStamp = newDate.getTime();

var timer;
var startsec = 01;
var startmin = 10;
//localStorage.clear();

var chkstartsec = localStorage.getItem('startTimesec');

if(chkstartsec != null){
startsec = chkstartsec;
}

var chkstartmin = localStorage.getItem('startTime');
if(chkstartmin != null){
startmin = chkstartmin;
}

function updateClock() {
    newDate = new Date();
    newStamp = newDate.getTime();
    var diff = Math.round((newStamp-startStamp)/1000);
    
    var d = Math.floor(diff/(24*60*60));
    diff = diff-(d*24*60*60);
    var h = Math.floor(diff/(60*60));
    diff = diff-(h*60*60);
    var m = Math.floor(diff/(60));
    diff = diff-(m*60);
    var s = diff;
    
    document.getElementById("time-elapsed").innerHTML = h+":"+m+":"+s+"";
}

function startTimer() {
  var presentTime = document.getElementById('time-elapsed').innerHTML;
  var timeArray = presentTime.split(/[:]+/);
  var m = timeArray[0];
  var s = checkSecond((timeArray[1] - 1));
  
  if(s==59){m=m-1}
  //if(m<0){alert('timer completed')}
 	localStorage.setItem('startTime', m);
	localStorage.setItem('startTimesec', s);
  document.getElementById('time-elapsed').innerHTML = m + ":" + s;
  setTimeout(startTimer, 1000);
}

function checkSecond(sec) {
  if (sec < 10 && sec >= 0) {sec = "0" + sec}; // add zero in front of numbers < 10
  if (sec < 0) {sec = "59"};
  return sec;
}
/*var a= localStorage.getItem('startTime');
var as= localStorage.getItem('startTimesec');

if(!a && !as){
var n = localStorage.setItem('startTime', 10);
var ns = localStorage.setItem('startTimesec', 00);
//var n= jQuery.cookie("timermin");
startmin = 10;
startsec= 00;
}
else{
alert(a + as);
startmin = a;
startsec = as;


}*/

document.getElementById('time-elapsed').innerHTML = startmin +':'+startsec;
//setInterval(updateClock, 1000);

if(timerstatus==1){
startTimer();

}		
   </script>

 <style>
   
  body, html {
   /* background-color: gray;*/
   	height: 100%;
}

#videos {
    position: relative;
    width: 100%;
    height: 600px; margin-bottom:30px; 
    margin-left: auto;
    margin-right: auto;
}

#subscriber {
    position: relative;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    z-index: 10;
}

#publisher {
    position: relative;
    width: 160px;
    height: 140px;
    bottom: 90px;
    left: 10px;
    z-index: 100;
    border: 3px solid white;
    border-radius: 3px;    
} 
#time {
    font-size: 150%;
} 
#disconnectsession.right { background-color: hsl(20, 100%, 64%); border:none; font-family: montserratregular;
  margin-top: -20px;  padding: 10px 20px;
} 
#disconnectsession.right:hover { background-color: #4D4D4D; 
} 
#videos #subscriber {  background-color: hsl(0, 0%, 86%);  margin-top: 10px;
}
   </style>

