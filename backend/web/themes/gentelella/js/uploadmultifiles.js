

function avatar_refresh_upload() {
    var input = $('input#avatar[type=file]');

    input.replaceWith(input.val('').clone(true));

    $('#selected_file').html('{{ Lang::get('app.profile_avatar_select') }}');
    $('#avatar_refresh_upload').removeAttr('style');
}

$(document).ready(function ($) {

    $('input:file#avatar').change(function () {
        var file_name = $(this).val();
        if (file_name.length > 10) {
            file_name = file_name.substring(0, 10) + '...';
        }
        $('#selected_file').html('File "' + file_name + '" chosen');
        $('#avatar_refresh_upload').css('display', 'inline-block');
    });

    $('#avatar_refresh_upload').on('click', function () {
        avatar_refresh_upload();
    });

    @if ($user->avatar != '')
    $('#remove_avatar').change(function () {

        if ($(this).is(':checked')) {

            avatar_refresh_upload();
            $('#avatar').prop('disabled', true);
            $('#avatar_preview').css('opacity', '0.5');
            $('#avatar_upload_form_area').css('opacity', '0.5');
            $('#remove_avatar_info').show();

        } else {

            $('#avatar').prop('disabled', false);
            $('#avatar_preview').removeAttr('style');
            $('#avatar_upload_form_area').removeAttr('style');
            $('#remove_avatar_info').removeAttr('style');

        }
    });
    @endif

});



$(function () {
    var dropZoneId = "drop-zone";
    var buttonId = "clickHere";
    var mouseOverClass = "mouse-over";

    var dropZone = $("#" + dropZoneId);
    var ooleft = dropZone.offset().left;
    var ooright = dropZone.outerWidth() + ooleft;
    var ootop = dropZone.offset().top;
    var oobottom = dropZone.outerHeight() + ootop;
    var inputFile = dropZone.find("input");
    document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
        e.preventDefault();
        e.stopPropagation();
        dropZone.addClass(mouseOverClass);
        var x = e.pageX;
        var y = e.pageY;

        if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
            inputFile.offset({
                top: y - 15,
                left: x - 100
            });
        } else {
            inputFile.offset({
                top: -400,
                left: -400
            });
        }

    }, true);

    if (buttonId != "") {
        var clickZone = $("#" + buttonId);

        var oleft = clickZone.offset().left;
        var oright = clickZone.outerWidth() + oleft;
        var otop = clickZone.offset().top;
        var obottom = clickZone.outerHeight() + otop;

        $("#" + buttonId).mousemove(function (e) {
            var x = e.pageX;
            var y = e.pageY;
            if (!(x < oleft || x > oright || y < otop || y > obottom)) {
                inputFile.offset({
                    top: y - 15,
                    left: x - 160
                });
            } else {
                inputFile.offset({
                    top: -400,
                    left: -400
                });
            }
        });
    }

    document.getElementById(dropZoneId).addEventListener("drop", function (e) {
        $("#" + dropZoneId).removeClass(mouseOverClass);
    }, true);

    inputFile.on('change', function (e) {
        $('#filename').html("");
        var fileNum = this.files.length,
            initial = 0,
            counter = 0,
            fileNames = "";

        for (initial; initial < fileNum; initial++) {
            counter = counter + 1;
            fileNames += this.files[initial].name + '&nbsp;';
        }
        if(fileNum > 1)
            fileNames = 'Files selected...';
        else
            fileNames = this.files[0].name + '&nbsp;';

        $('#filename').append('<span class="fa-stack fa-lg"><i class="fa fa-file fa-stack-1x "></i><strong class="fa-stack-1x" style="color:#FFF; font-size:12px; margin-top:2px;">'+ fileNum + '</strong></span><span">' + fileNames + '</span>&nbsp;<span class="fa fa-times-circle fa-lg closeBtn" title="remove"></span><br>');

        // add remove event
      $('#filename').find('.closeBtn').click(function(){
          $('#filename').empty();
          inputFile.val('');
      });
      ///End change 
    });

})
	

$(function () {

  var dropZoneId = "drop-zone";
  var buttonId = "clickHere";
  var mouseOverClass = "mouse-over";

  var dropZone = $("#" + dropZoneId);
  var ooleft = dropZone.offset().left;
  var ooright = dropZone.outerWidth() + ooleft;
  var ootop = dropZone.offset().top;
  var oobottom = dropZone.outerHeight() + ootop;
  var inputFile = dropZone.find("input");

  var filesArr = [];

  function showFiles() {
    $('#filename').html("");
    var fileNum = filesArr.length;
    for (var i = 0; i < fileNum; i++) {
      $('#filename').append('<div><span class="fa-stack fa-lg"><i class="fa fa-file fa-stack-1x "></i><strong class="fa-stack-1x" style="color:#FFF; font-size:12px; margin-top:2px;">'+ i + '</strong></span> ' + filesArr[i].name + '&nbsp;&nbsp;<span class="fa fa-times-circle fa-lg closeBtn" title="remove"></span></div>');
      }
  }

  function addFiles(e) {
    var tmp;

    // transfer dropped content to temporary array
    if (e.dataTransfer) {
      tmp = e.dataTransfer.files;
    } else if (e.target) {
      tmp = e.target.files;
    }        

    // Copy the file items into the array 
    for(var i = 0; i < tmp.length; i++) {
      filesArr.push(tmp.item(i));
    }

    // remove all contents from the input elemnent (reset it)
    inputFile.wrap('<form>').closest('form').get(0).reset();
    inputFile.unwrap();

    showFiles();
  }    

  document.getElementById(dropZoneId).addEventListener("dragover", function (e) {
    e.preventDefault();
    e.stopPropagation();
    dropZone.addClass(mouseOverClass);
    var x = e.pageX;
    var y = e.pageY;

    if (!(x < ooleft || x > ooright || y < ootop || y > oobottom)) {
        inputFile.offset({
            top: y - 15,
            left: x - 100
        });
    } else {
        inputFile.offset({
            top: -400,
            left: -400
        });
    }
  }, true);

  if (buttonId != "") {
    var clickZone = $("#" + buttonId);

    var oleft = clickZone.offset().left;
    var oright = clickZone.outerWidth() + oleft;
    var otop = clickZone.offset().top;
    var obottom = clickZone.outerHeight() + otop;

    $("#" + buttonId).mousemove(function (e) {
      var x = e.pageX;
      var y = e.pageY;
      if (!(x < oleft || x > oright || y < otop || y > obottom)) {
          inputFile.offset({
              top: y - 15,
              left: x - 160
          });
      } else {
          inputFile.offset({
              top: -400,
              left: -400
          });
      }
    });
  }
  document.getElementById(dropZoneId).addEventListener("drop", function (e) {
    $("#" + dropZoneId).removeClass(mouseOverClass);
    addFiles(e);
  }, true);

  inputFile.on('change', function(e) {
    addFiles(e);
  });

  $('#filename').on('click', '.closeBtn', function(e) {
    e.preventDefault();
    e.stopPropagation();

    var divElem = $(this).parent();
    var index = $('#filename').find('div').index(divElem);
    if ( index !== -1 ) {
      $('#filename')[0].removeChild(divElem[0]);
      filesArr.slice(index,1);
    }
  });

})
