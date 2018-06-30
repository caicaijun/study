function delfuncs(obj){
	layer.confirm('确认删除？', {
		  btn: ['确定','取消']
		}, function(){
			$.ajax({
				type : 'post',
				url : $(obj).attr('data-url'),
				data : {id:$(obj).attr('data-id')},
				dataType : 'json',
				success : function(json){
					var d = json;
					if(d["status"]==1){
						layer.msg(d["msg"], {icon: 1,time: 2000});
						$(obj).parent().parent().remove();
					}else{
						layer.msg(d["msg"], {icon: 2,time: 2000});
					}
					//layer.closeAll();
				}
			})
		}, function(index){
			layer.close(index);
			return false;// 取消
		}
	);
}


function delfunc_all(obj){
	var level=$(obj).attr('dir');
	layer.confirm('确认删除？', {
		  btn: ['确定','取消']
		}, function(){
			$.ajax({
				type : 'post',
				url : $(obj).attr('data-url'),
				//url : 'http://127.0.0.1/new_shop/index.php/Yxadmin/Article/listdel',
				data : {id:$(obj).attr('data-id')},
				dataType : 'json',
				success : function(data){
					var d = data;
					if(d["status"]==1){
						if(level==1){
							var x_level=$(obj).parent().parent().next("tr").attr("dir");
							var xx_level=$(obj).parent().parent().next("tr").next("tr").attr("dir");
							if(xx_level==3){
								$(obj).parent().parent().next("tr").next("tr").remove();
							}
							if(x_level==2){
								$(obj).parent().parent().next("tr").remove();
							}
						}
						if(level==2){
							$(obj).parent().parent().next("tr").remove();
						}
						$(obj).parent().parent().remove();
						layer.msg(d["msg"], {icon: 1,time: 2000});
					}else{
						layer.msg(d["msg"], {icon: 2,time: 2000});
					}
					//layer.closeAll();
				}
			})
		}, function(index){
			layer.close(index);
			return false;// 取消
		}
	);
}


function updateSort(obj){		       
	var value = $(obj).val();
	$.ajax({
		url : $(obj).attr('data-url'),
		data : {id:$(obj).attr('data-id'),new_sort:value},
		dataType : 'json',
		success: function(data){
			var d = data;
			if(d["status"]==1){
				layer.msg('更新成功', {icon: 1});  
			}else{
				layer.msg('更新失败', {icon: 2});  
			}
		}
	});		
}




function changeTableVal(table,id_name,id_value,field,obj)
{
		var src = "";
		 if($(obj).attr('src').indexOf("cancel.png") > 0 )
		 {          
			src = '/Public/manage/images/yes.png';
			var value = 1;
		 }else{                    
				src = '/Public/manage/images/cancel.png';
				var value = 0;
		 }                                                 
		$.ajax({
				url : $(obj).attr('data-url'),
				data : {table:table,id_name:id_name,id_value:id_value,field:field,value:value},
				success: function(data){								
					$(obj).attr('src',src);           
				}
		});		
}