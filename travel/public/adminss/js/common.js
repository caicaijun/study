//tab
$(document).ready(function(){
	 //tab
	 $(".web_tab li a").click(function(){
	  var liindex = $(".web_tab li a").index(this);
	  $(this).addClass("active").parent().siblings().find("a").removeClass("active");
	  $(".web_tab_cont").eq(liindex).fadeIn(150).siblings(".web_tab_cont").hide();
	 });
});



//×ó²à³¬³öÆÁÄ»¹ö¶¯Ìõ
(function($){
	$(window).load(function(){
		
		$("a[rel='load-content']").click(function(e){
			e.preventDefault();
			var url=$(this).attr("href");
			$.get(url,function(data){
				$(".content .mCSB_container").append(data); //load new content inside .mCSB_container
				//scroll-to appended content 
				$(".content").mCustomScrollbar("scrollTo","h2:last");
			});
		});
		
		$(".content").delegate("a[href='top']","click",function(e){
			e.preventDefault();
			$(".content").mCustomScrollbar("scrollTo",$(this).attr("href"));
		});
		
	});
})(jQuery);


$(function () {
	$("#videoform").validate({
		 //submitHandler: function(){ }
	});
});
