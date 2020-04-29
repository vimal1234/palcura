$(document).ready(function(){
	/*#### Pagination limit layout ####*/
	if($('ul').hasClass('pagination')) { $(".page_limit_layout").css("margin-top","-50px"); }
	$('.x_title').on('click', '#resetFilter', function(){
		$('.filters').find('input').val('');
		$('.filters').find('select').val('');
		$("#grid-container").yiiGridView("applyFilter");
	});

	$('.x_title').on('click', '#btnfilterApply', function(){
		$("#grid-container").yiiGridView("applyFilter");
	});
	/*#### send messages ####*/
	$("#send-message").click(function(){
		var cnt = 0;
		var checkedVals = $('.msgChk:checkbox:checked').map(function() {
			cnt = 1;
			return this.value;
		}).get();
		if(cnt == 1) {
			$("#sendchkbox").val(checkedVals);	
			$("#form-sendmessage").submit();
		} else {
			alert('Please select any checkbox to send notification.');
		}
	});
});
/*#### common method to update status ####*/
function updateStatus(dis, userid) {
	var post = {'update': {'status': dis.value}};
	if (dis.value && dis.value != '') {
		$.ajax({
			url: homeURL+CtrlName+'/status/' + userid,
			type: 'post',
			data: post,
			success: function (response) {
				window.location.reload();
			}
		});
	}
}

/*#### update payment status ####*/
function updatePaymentStatus(dis, paymentId) {
	var post = {'UpdatePayment': {'status': dis.value}};
	if (dis.value && dis.value != '') {
		$.ajax({
			url: homeURL+CtrlName+'/updatestatus/' + paymentId,
			type: 'post',
			data: post,
			success: function (response) {
				window.location.reload();
			}
		});
	}
}
/*#### common method to update verification status ####*/
function userVerification(dis, userid) {
	var post = {'update': {'verified_by_admin': dis.value}};
	if (dis.value && dis.value != '') {
		$.ajax({
			url: homeURL+CtrlName+'/userverification/' + userid,
			type: 'post',
			data: post,
			success: function (response) {
				window.location.reload();
			}
		});
	}
}

/*#### common method to update verification status ####*/
function userVerificationBadge(dis, userid) {
	var post = {'update': {'verification_badge': dis.value}};
	if (dis.value && dis.value != '') {
		$.ajax({
			url: homeURL+CtrlName+'/userverificationbadge/' + userid,
			type: 'post',
			data: post,
			success: function (response) {
				window.location.reload();
			}
		});
	}
}

/*#### pagination limit ####*/
function changePageLimit() {
	var val = $("#propertiessearch-pagesize").val();
	location.href = homeURL+CtrlName+"?p="+val;	
}
