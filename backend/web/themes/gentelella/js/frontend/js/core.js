$(document).ready(function(){
  var $content = $(".slidebox").hide();
  $(".toggle").on("click", function(e){
    $(this).toggleClass("expanded");
    $content.slideToggle();
  });
});
/*************ie 9 placeholder******************/
 $('[placeholder]').focus(function() {
  var input = $(this);
  if (input.val() == input.attr('placeholder')) {
    input.val('');
    input.removeClass('placeholder');
  }
}).blur(function() {
  var input = $(this);
  if (input.val() == '' || input.val() == input.attr('placeholder')) {
    input.addClass('placeholder');
    input.val(input.attr('placeholder'));
  }
}).blur().parents('form').submit(function() {
  $(this).find('[placeholder]').each(function() {
    var input = $(this);
    if (input.val() == input.attr('placeholder')) {
      input.val('');
    }
  })
});
/*****************/
$(function() {                       //run when the DOM is ready
  $(".heart").click(function() {  
    $(this).toggleClass("active");      //add the class to the clicked element
  });
});

/*********navigation*************/
$(document).ready(function() {
	     $('.menutop').click(function() {
			if($(this).hasClass('active')){
				$(this).removeClass('active');
				$('.sideTopBar').animate({right:-250},500);	
				$('#outer').animate({right:0},500);
			}
			else{
				$(this).addClass('active');	
				$('.sideTopBar').animate({right:0},500);
				$('#outer').animate({right:250},500);
			}
		});
		

});
/* Bx Slider  */
$(document).ready(function() {
    $('.bxslider').bxSlider({
		 pagerCustom: '#bx-pager',
		 mode:'horizontal' 
		 });
	
});


/************For UI***************/
$(document).ready(function(){
    $('#private').click(function(){
        $(".publicSelectBox").hide();
        $(".privateSelectBox").show();
    });
    
     $('#public').click(function(){
        $(".privateSelectBox").hide();
        $(".publicSelectBox").show();
    });
}); 
 
 
 
 
 
