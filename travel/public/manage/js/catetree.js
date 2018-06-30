$("#check").die('click').live('click', function() {
	if($(this).attr('checked')){
		$('.check').attr('checked','checked');
	}else{
		$('.check').removeAttr('checked');
	}
})
function  tree_open(){
	 var tree = $('#list-table tr[id^="2_"], #list-table tr[id^="3_"] ');
	 if(tree.css('display')  == 'table-row'){
		tree.css('display','none');
		$("span[id^='icon_']").removeClass('glyphicon-minus');
		$("span[id^='icon_']").addClass('glyphicon-plus');		
	 }else{
		tree.css('display','table-row');
		$("span[id^='icon_']").addClass('glyphicon-minus');
		$("span[id^='icon_']").removeClass('glyphicon-plus');				
	 }
}
    
// 以下是 bootstrap 自带的  js
function rowClicked(obj)
{
  span = obj;
  obj = obj.parentNode.parentNode;
  var tbl = document.getElementById("list-table");
  var lvl = parseInt(obj.className);
  var fnd = false;
  
  var sub_display = $(span).hasClass('glyphicon-minus') ? 'none' : '' ? 'block' : 'table-row' ;
  if(sub_display == 'none'){
	  $(span).removeClass('glyphicon-minus btn-info');
	  $(span).addClass('glyphicon-plus btn-warning');
  }else{
	  $(span).removeClass('glyphicon-plus btn-info');
	  $(span).addClass('glyphicon-minus btn-warning');
  }

  for (i = 0; i < tbl.rows.length; i++) //循环表格行
  {
      var row = tbl.rows[i];
      
      if (row == obj)
      {
          fnd = true;         
      } else {
          if (fnd == true)
          {
              var cur = parseInt(row.className);
              var icon = 'icon_' + row.id;
              if (cur > lvl)
              {
                  row.style.display = sub_display;
                  if (sub_display != 'none')
                  {
                      var iconimg = document.getElementById(icon);
                      $(iconimg).removeClass('glyphicon-plus btn-info');
                      $(iconimg).addClass('glyphicon-minus btn-warning');
                  }else{
                      $(iconimg).removeClass('glyphicon-minus btn-info');
                      $(iconimg).addClass('glyphicon-plus btn-warning');
                  }
              }
              else
              {
                  fnd = false;
                  break;
              }
          }
      }
  }

  for (i = 0; i < obj.cells[0].childNodes.length; i++){
      var imgObj = obj.cells[0].childNodes[i];
      if (imgObj.tagName == "IMG")
      {
          if($(imgObj).hasClass('glyphicon-plus btn-info')){
        	  $(imgObj).removeClass('glyphicon-plus btn-info');
        	  $(imgObj).addClass('glyphicon-minus btn-warning');
          }else{
        	  $(imgObj).removeClass('glyphicon-minus btn-warning');
        	  $(imgObj).addClass('glyphicon-plus btn-info');
          }
      }
  }

}
