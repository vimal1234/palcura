window.onload = function(){
    if(window.File && window.FileList && window.FileReader) {
        /*##### multiple images #####*/
        var filesInput = document.getElementById("uploads-upload_images");

        filesInput.addEventListener("change", function(event) {
		    var fileinfo = document.getElementById("uploads-upload_images").value;
		    var file_name = fileinfo.split(/(\\|\/)/g).pop();

            var files = event.target.files;
            var output = document.getElementById("result");
            //$('.client_show').remove();            
            if(files.length > 4) {
				return false;
			}

            for(var i = 0; i< files.length; i++) {
                var file = files[i];
                if(file.size/1000000	>	2) {
					return false;
				}                
                if(!file.type.match('image'))
                  continue;

				
                var picReader = new FileReader();
                picReader.fileName = file.name;
                picReader.addEventListener("load",function(event) {
					var n = $("#removed_images_contr").val();
					var n_val = parseInt(n)+1;
					$("#removed_images_contr").val(n_val);
                    var picFile = event.target;
                    var div = document.createElement("span");
                    div.className = 'client_show';
                    //div.id ='img_'+n;
                    //var trsh_id = 'img_'+n;
                    var trsh_id = event.target.fileName;
                    div.innerHTML = "<img class='' src='" + picFile.result + "'" +
                            "title='" + event.target.fileName + "' height=60' width='60' /> <span id='"+trsh_id+"' class='remove_pict mediaKey glyphicon glyphicon-trash'></span>";
                    output.insertBefore(div,null);   
                    div.children[1].addEventListener("click", function(event){
						//var vl = this.id;
						var newId = this.id;
						//var newId = vl.replace("img_", "");
                        div.parentNode.removeChild(div);
						var remove_val = $("#removed_images").val();
						if(remove_val != '') {
							//$("#removed_images").val(remove_val+",p_"+newId);
							$("#removed_images").val(remove_val+","+newId);
						} else {
							//$("#removed_images").val('p_0');
							$("#removed_images").val(newId);
						}
							
						
                    });
                });
                picReader.readAsDataURL(file);
            }
        });
        
        /*##### multiple files #####*/
        var filesInput = document.getElementById("uploads-upload_documents");
        filesInput.addEventListener("change", function(event){
		    var fileinfo = document.getElementById("uploads-upload_documents").value;
		    var file_name = fileinfo.split(/(\\|\/)/g).pop();
            var files = event.target.files;
            var output = document.getElementById("resultdoc");
            $('.client_show_d').remove();            
            if(files.length > 4) {
				return false;
			}
            for(var i = 0; i< files.length; i++) {
                var file = files[i];
                if(file.size/1000000	>	2) {
					return false;
				}                
                //Only pics
             //   if(!file.type.match('image'))
               //   continue;

                var picReader = new FileReader();
                picReader.fileName = file.name;
                picReader.addEventListener("load",function(event){
					var d_n = $("#removed_documents_contr").val();
					var d_n_val = parseInt(d_n)+1;
					$("#removed_documents_contr").val(d_n_val);					
                    var picFile = event.target;
                    var div = document.createElement("span");
                    div.className = 'client_show_d';
                    //var d_trsh_id = 'doc_'+d_n;
                    var d_trsh_id = event.target.fileName;
                    div.innerHTML =  d_trsh_id + "<span id='"+d_trsh_id+"' class='remove_pict mediaKey glyphicon glyphicon-trash'></span>";
                    output.insertBefore(div,null);   
                    div.children[0].addEventListener("click", function(event) {
						//var d_vl = this.id;
						var d_newId = this.id;
						//var d_newId = d_vl.replace("doc_", "");						
						div.parentNode.removeChild(div);
						var d_remove_val = $("#removed_documents").val();
						if(d_remove_val != '') {
							//$("#removed_documents").val(d_remove_val+",p_"+d_newId);
							$("#removed_documents").val(d_remove_val+","+d_newId);
						} else {
							//$("#removed_documents").val('p_0');
							$("#removed_documents").val(d_newId);
						}						
                    });
                });
                picReader.readAsDataURL(file);
            }
        });
    } else {
        console.log("Your browser does not support File API");
    }
}
