//鐧诲綍
$(function () {
  $("#addeditform").validate({
	submitHandler: function(){
		$('#addeditform').submit();
	}
  });  
  $("#addeditform_no").validate({
	//submitHandler: function(){}
  }); 
})


