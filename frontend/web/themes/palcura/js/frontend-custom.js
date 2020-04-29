$(document).ready(function(){
    $("#checkAll").click(function () {
        if ($("#checkAll").is(':checked')) {
			$('#checkAll').prop("checked", true);
			$('.msgChk').prop("checked", true);
        } else {
			$('#checkAll').prop("checked", false);
			$('.msgChk').prop("checked", false);
        }
    });

	$('#selectAll').click(function(){ 
		$('#checkAll').prop("checked", true);
		$('.msgChk').prop("checked", true);
	});

	/*#### remove-conversation ####*/
	var postData = {};
	$('#remove-conversation').click(function() {
		var vale = $(this).val();
		var cnt = 0;
		var idArr = $(".msgChk:checkbox:checked").map(function(i, el) { 
			cnt = 1;
			return $(el).attr("id"); 
		}).get();

		if(cnt == 1) {
			//$("#mcmbVal").val(vale);
			if(!confirm('Are you sure you want to delete seleted items?')){
				return false;
			}
			postData = idArr;
			removeConversation(postData);
			//location.reload(); 
			//$("#multichkbox").val(idArr);
			//$("#form-multi-actions").submit();
		} else {
			alert('Please select any checkbox to delete items from list.');
			//$("#cmbActions_two").val("");
			return false;			
		}
	});
	
	
	/*$('#selectpet1').click(function(){ 
		$('#selectedpal').val('dog');
	});
	
	$('#selectpet2').click(function(){ 
		$('#selectedpal').val('cat');
	});
	
	$('#selectpet3').click(function(){ 
		$('#selectedpal').val('other');
	});*/
	/*if(userLogIn == "1"){
	var userid = currentuser;
	
	$.ajax({
		url:siteUrl+'/video/chektodayssession',
		type:'post',
		data:{'myvid':userid},
		success:function(response) {
		if(response == true){
		showcaller(userid);
		var getuserscallstatus = setInterval(showcaller, 3000);
		
		function showcaller(userid){	
			$.ajax({
			url:siteUrl+'/video/chektodayactivessession',
			type:'post',
			data:{'myvid':userid},
			success:function(response) {
			
			if(response > 0){
			var videoid = response;
			clearInterval(getuserscallstatus);
			   $("#viModal").modal({"backdrop": "static"});
			   			   
			   //accept call
			   $('#talktocaller').on('click',function(){			  							
				window.location.href = siteUrl+'/video/talk/'+videoid;			   
			   });
			   
			   //decline current call
			   $('#declinecaller').on('click',function(){			 
			   		$.ajax({
					url:siteUrl+'/video/declinecall',
					type:'post',
					data:{'vid_id':videoid},
					success:function(response) {
					 $('#viModal').modal('hide');
					}
			      });
			   			   
			   });
			   
			   //checkcallacceptstatus()
			   //check declined status start
			    setInterval(checkcallacceptstatus, 3000);
			    function checkcallacceptstatus(){
						$.ajax({
							type:'POST',
							url:siteUrl+'/video/getcallresponse',
							data:{'temp_id':videoid},		
								success:function(res){								
								if(res=='decline'){	
								clearInterval(checkcallacceptstatus);							
								$('#viModal').modal('hide');
								}
								if(res=='accept'){	
								clearInterval(checkcallacceptstatus);							
								}
																
							}

							});
						}
			    //check declined status end			   
			   }
			}
			});
		
		}
		
			
		}else{		
		return false;
		}
			
		}
	});
	
	
	}*/
	
});

function removeConversation(postData) {
		$.ajax({
		url:siteUrl+'/messages/remove-conversation',
		type:'post',
		data:{'chatIds':postData},
		success:function(response) {
			location.reload();
		//	if(response)	
			//	alert('d');
		}
	});
}



