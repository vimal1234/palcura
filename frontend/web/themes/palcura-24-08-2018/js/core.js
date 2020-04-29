


$(document).ready(function() {
	$('.item01').click(function() {
		$('#item01').addClass('showBlock');
		$('#item02').removeClass('showBlock');
		$('#item03').removeClass('showBlock');
	});
	$('.item02').click(function() {
		$('#item01').removeClass('showBlock');
		$('#item02').addClass('showBlock');
		$('#item03').removeClass('showBlock');
	});
	$('.item03').click(function() {
		$('#item01').removeClass('showBlock');
		$('#item02').removeClass('showBlock');
		$('#item03').addClass('showBlock');
	});
});


// onclick remove class
$(document).ready(function() {
	$(".pagination li").click(function(){
		$("li").removeClass("active");
	});


// active pal type

	$('.threeCol .act').on('click', function(e) {
	  e.preventDefault(); 
		  $(this).closest('.col').addClass('active') // 
				 .siblings('.col').removeClass('active');
	});
	
	
	$( ".mobileside" ).click(function() {
  $( ".leftSidebarNav" ).slideToggle( "slow", function() {
    // Animation complete.
  });
});
});
// add class onhover
$(document).ready(function() {
	// part 1
	var selector = '.detailTitle';
    $('.title-1').hover(function(){     
        $('.listOne li').addClass('blue');    
    },     
    function(){    
        $('.listOne li').removeClass('blue');     
    });

	// part 2 
	var selector = '.detailTitle';
    $('.title-2').hover(function(){     
        $('.listTwo li').addClass('blue-2');    
    },     
    function(){    
        $('.listTwo li').removeClass('blue-2');     
    });
	
	// part 3 
	var selector = '.detailTitle';
    $('.title-3').hover(function(){     
        $('.listThree li').addClass('blue-3');    
    },     
    function(){    
        $('.listThree li').removeClass('blue-3');     
    });

	// part 4 
	var selector = '.detailTitle';
    $('.title-4').hover(function(){     
        $('.listFour li').addClass('blue-4');    
    },     
    function(){    
        $('.listFour li').removeClass('blue-4');     
    });

	// part 5 
	var selector = '.detailTitle';
    $('.title-5').hover(function(){     
        $('.listFive li').addClass('blue-5');    
    }, 
    function(){    
        $('.listFive li').removeClass('blue-5');     
    });
});
//calender code removed from here
/*******calendar*****/
var $window = $(window);
$(window).load(function() {
    $(function() {
		$(".datepicker" ).datepicker({
			numberOfMonths: 1,
			changeYear: true,
			showButtonPanel: true,
yearRange: "-100:+0",
			minDate: '-100Y',
			maxDate: '-1D',			
		});
	});
});

$(document).ready(function() {
	$('.responsive-tabs').responsiveTabs({
	  	accordionOn: ['xs'] // xs, sm, md, lg 
	});
});



$(document).ready(function() {
      var owl = $(".owl-carousel");
    owl.owlCarousel({ 
	items:3,
			responsiveClass:true,
			responsive:{
				
				0:{
				items:2, 
				},
				480:{
				items:3, 
				},
				
				767:{
				items:3, 
				},
				1200:{
				items:3, 
				}
			},
			navigation : false,
			autoplay: false,
			autoplayTimeout: 1000,
			autoplayHoverPause: false, 
		 	loop:true,
			nav: true,
			margin:30,
			navigationText: [
				"<i class='fa fa-caret-left'></i>",
				"<i class='fa fa-caret-left'></i>"],
      }); 
	  
	 
	   
    });

	
	
	
	
	
	
$( document ).ready(function() {
	/*********tabs***********/
	// tabbed content
    // http://www.entheosweb.com/tutorials/css/tabs.asp
    $(".tab_content").hide();
    $(".tab_content:first").show();

	/* if in tab mode */
	$("ul.tabs li").click(function() {
		$(".tab_content").hide();
		var activeTab = $(this).attr("rel"); 
		$("#"+activeTab).fadeIn();		

		$("ul.tabs li").removeClass("active");
		$(this).addClass("active");

		$(".tab_drawer_heading").removeClass("d_active");
		$(".tab_drawer_heading[rel^='"+activeTab+"']").addClass("d_active");
	});
	/* if in drawer mode */

	$(".tab_drawer_heading").click(function() {
		$(".tab_content").hide();
		var d_activeTab = $(this).attr("rel"); 
		$("#"+d_activeTab).fadeIn();

		$(".tab_drawer_heading").removeClass("d_active");
		$(this).addClass("d_active");

		$("ul.tabs li").removeClass("active");
		$("ul.tabs li[rel^='"+d_activeTab+"']").addClass("active");
	});
	
	/* Extra class "tab_last" 
	to add border to right side
	of last tab */
	$('ul.tabs li').last().addClass("tab_last");
	$( ".dropArrow" ).click(function() {
		$( ".leftnav" ).slideToggle( "slow", function() {
		// Animation complete.
		});
	});
});


/**************show hide*****************/

$( document ).ready(function() {

$(function() {
    $('.block01').addClass('hidden').hide();
    $('button.orangebtn').click(function() {
        if ($('.block01').hasClass('hidden')) {
            $('.block01').removeClass('hidden').fadeIn(3000);
        }
        else {
            $('.block01').addClass('hidden').fadeOut(3000);
        }
    });
});


if ($(".scrolldiv01").length > 0) {
var sz = $(window).width(); 
	if(sz <= 768) {
		$('html, body').animate({
			scrollTop: $(".scrolldiv01").offset().top
		}, 2000);	
	}
}




});


/**********scroll***********/
$("button.orangebtn").click(function() {
    $('html, body').animate({
        scrollTop: $("#selectPaltype").offset().top
    }, 1200);
});

$("button.yellowbtn").click(function() {
    $('html, body').animate({
        scrollTop: $("#selectPaltype").offset().top
    }, 1200);
});


$(".item01").click(function() {
    $('html, body').animate({
        scrollTop: $(".one").offset().top
    }, 1200);
});

$(".item02").click(function() {
    $('html, body').animate({
        scrollTop: $(".two").offset().top
    }, 1200);
});

$(".item03").click(function() {
    $('html, body').animate({
        scrollTop: $(".three").offset().top
    }, 1200);
});



$("a.changepal").click(function() {
    $('html, body').animate({
        scrollTop: $(".selectPaltype").offset().top
    }, 1200);
});

function goBack() {
    window.history.back();
}

if ($(".scrolldiv01").length > 0) {
var sz = $(window).width(); 
	if(sz <= 768) {
		$('html, body').animate({
			scrollTop: $(".scrolldiv01").offset().top
		}, 2000);	
	}
}

// according to left col section height

if($(window).width() > 768){ function adjusting_height(){ 
	var height = $('.leftColorCol').css('height'); 
		$('.rightColorCol').css('height',height); 
	} 
	$(document).ready(function(){ 
		adjusting_height(); $(window).resize(function(){
	adjusting_height(); 
	});
	}); 
};


