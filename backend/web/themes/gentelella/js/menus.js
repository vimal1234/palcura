/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/** ******  left menu  *********************** **/
$(function () {
    $('#menulinks-type').on('change',function () {
		var type = 0;
		type = $(this).val();
		if(type == 1) {
			$("#pageItems").addClass("pageNone");
			$("#customUrl").removeClass("pageNone");
		} else if(type == 2) {
			$("#customUrl").addClass("pageNone");
			$("#pageItems").removeClass("pageNone");
		} else {
			$("#customUrl").addClass("pageNone");
			$("#pageItems").addClass("pageNone");			
		}
});
});

function countChecked() {
        if (check_state == 'check_all') {
            $(".bulk_action input[name='table_records']").iCheck('check');
        }
        if (check_state == 'uncheck_all') {
            $(".bulk_action input[name='table_records']").iCheck('uncheck');
        }
        var n = $(".bulk_action input[name='table_records']:checked").length;
        if (n > 0) {
            $('.column-title').hide();
            $('.bulk-actions').show();
            $('.action-cnt').html(n + ' Records Selected');
        } else {
            $('.column-title').show();
            $('.bulk-actions').hide();
        }
    }
