
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="plugin2/development-bundle/themes/base/jquery.ui.all.css">
<title>Ajax Upload and Resize with jQuery and PHP - Demo</title>
<script type="text/javascript" src="js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="js/jquery.form.min.js"></script>
<script type="text/javascript" src="html2canvas.js"></script>
<script src="plugin2/development-bundle/ui/jquery.ui.core.js"></script>
<script src="plugin2/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="plugin2/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="plugin2/development-bundle/ui/jquery.ui.draggable.js"></script>
<script src="plugin2/development-bundle/ui/jquery.ui.resizable.js"></script>
<link rel="stylesheet" href="style/demos.css">
<script type="text/javascript">
$(document).ready(function() { 
	var finish = $("#finish");
	var isItUploaded = false;
	var options = { 
			target:   '#output',   // target element(s) to be updated with server response 
			beforeSubmit:  beforeSubmit,  // pre-submit callback 
			success:       afterSuccess,  // post-submit callback 
			resetForm: true        // reset the form after successful submit 
		}; 
		
	$('#MyUploadForm').submit(function() { 
			$(this).ajaxSubmit(options);  			
			// always return false to prevent standard browser submit and page navigation 
			return false;

		});

	finish.click(function(){

	 	if(isItUploaded){
	 		var width = $("#resizable").width();
	 		var height = $("#resizable").height();
	 		var data = {"width": width, "height": height};
	 		console.log(data);
	 		var url = "resize.php";
        	var xhr = new XMLHttpRequest(); 
        	xhr.open("POST", url, true);
        	xhr.setRequestHeader('Content-Type', 'application/json; charset=UTF-8');


	        //................................ send the collected data as JSON
	        xhr.send(JSON.stringify(data));
	        	html2canvas([document.getElementById('dadycool')], {
   		 onrendered: function (canvas) {
        var data = canvas.toDataURL();
        // AJAX call to send `data` to a PHP file that creates an image from the dataURI string and saves it to a directory on the server
        console.log(data);
        $.ajax({
		  type: "POST",
		  url: "up.php",
		  data: { 
		     imgBase64: data
		  }
		}) 
    		}	
		});

        xhr.onloadend = function (data) {
        };
	 	}
 		return false;
	 });

function afterSuccess()
{
	$('#submit-btn').show(); //hide submit button
	$('#loading-img').hide(); //hide submit button
	// $("#resizable").resizable({ aspectRatio: true }).parent().draggable( {containment: "parent"} );
	$("#resizable").resizable({
    aspectRatio: true,
    handles: 'e, s,se'

}).parent().draggable( {containment: "parent"} );
	isItUploaded = true;
	// $("#resizable").resizable().parent().draggable( {containment: $("td")} );
	$(".ui-resizable-es").addClass('ui-icon ui-icon-gripsmall-diagonal-se');

}

//function to check file size before uploading.
function beforeSubmit(){
    //check whether browser fully supports all File API
   if (window.File && window.FileReader && window.FileList && window.Blob)
	{
		
		if( !$('#imageInput').val()) //check empty input filed
		{
			$("#output").html("Are you kidding me?");
			return false
		}
		
		var fsize = $('#imageInput')[0].files[0].size; //get file size
		var ftype = $('#imageInput')[0].files[0].type; // get file type
		

		//allow only valid image file types 
		switch(ftype)
        {
            case 'image/png': case 'image/gif': case 'image/jpeg': case 'image/pjpeg':
                break;
            default:
                $("#output").html("<b>"+ftype+"</b> Unsupported file type!");
				return false
        }
		
		//Allowed file size is less than 1 MB (1048576)
		if(fsize>1048576) 
		{
			$("#output").html("<b>"+bytesToSize(fsize) +"</b> Too big Image file! <br />Please reduce the size of your photo using an image editor.");
			return false
		}
				
		$('#submit-btn').hide(); //hide submit button
		$('#loading-img').show(); //hide submit button
		$("#output").html("");  
	}
	else
	{
		//Output error to older unsupported browsers that doesn't support HTML5 File API
		$("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
		return false;
	}

}

//function to format bites bit.ly/19yoIPO
function bytesToSize(bytes) {
   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
   if (bytes == 0) return '0 Bytes';
   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}


});

</script>
<link href="style/style.css" rel="stylesheet" type="text/css">
</head>
<body>
<div id="upload-wrapper">
<div align="center">

<h3>Ajax Image Uploader</h3>
<form action="processupload.php" method="post" enctype="multipart/form-data" id="MyUploadForm">
<input id="imageInput" type="file" name="pics" />
<input type="submit"  id="submit-btn" value="Upload" />
<img src="images/ajax-loader.gif" id="loading-img" style="display:none;" alt="Please Wait"/>
<input type="submit" id="finish" value="Finish" onClick="sendfinish()">
</form>
<div id="output"></div>
</div>
</div>

</body>
</html>