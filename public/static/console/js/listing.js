/**
 *
 * 用于列表删除的js
 *
 */
function check_all(o){
    var obj = $("input[type='checkbox'][name='checkid[]']");
    if($(o).is(':checked')){
		obj.each(function(i,obj){
            $(this).prop('checked','true');
        });

    }else{
        obj.each(function(i,obj){
            $(this).prop('checked','');
        });
    }

}

function check_alls(o){
    var obj = $("input[type='checkbox'][name='type_id[]']");
    if($(o).is(':checked')){
        obj.each(function(i,obj){
            $(this).prop('checked','true');
        });

    }else{
        obj.each(function(i,obj){
            $(this).prop('checked','');
        });
    }

}

function check_delete(url,id){
    if(id==0){
    	if($("input[type='checkbox'][name='checkid[]']:checked").size() == 0)
		{
			swal("出错了", "没有选中任何数据", "error");
			return;
		}
    }
	swal({
		title: "确定要删除选中数据?",
		text: "你将无法恢复删除的数据!",
		type: "warning",
		showCancelButton: true,
        confirmButtonText: "确认",
        cancelButtonText: '取消',
        closeOnConfirm: false
	}, function(){
		// 创建Form
	    var form = $('<form></form>');
	    // 设置属性
	    form.attr('action', url);
	    form.attr('method', 'post');
	    // form的target属性决定form在哪个页面提交
	    // _self -> 当前页面 _blank -> 新页面
	    form.attr('target', '_self');

		if(id==0){
			$("input[type='checkbox'][name='checkid[]']:checked").each(function(){
				// 创建Input
			    var my_input = $('<input type="text" name="id[]"/>');
			    my_input.attr('value', $(this).val());
			    // 附加到Form
			    form.append(my_input);
			});
		}
		else{
		    // 创建Input
		    var my_input = $('<input type="text" name="id"/>');
		    my_input.attr('value', id);
		    // 附加到Form
		    form.append(my_input);
		}
		$("#renew_submit").append(form);
		// 提交表单
		form.submit();
		return true;
	});
}

function check_state(url,id,name,val){		if(id==0){    	if($("input[type='checkbox'][name='checkid[]']:checked").size() == 0)		{			swal("出错了", "没有选中任何数据", "error");			return;		}    }	
	swal({
		title: "确定要更新该状态?",
		text: "你将更新数据的状态!",
		type: "warning",
		showCancelButton: true,
        confirmButtonText: "确认",
        cancelButtonText: '取消',
        closeOnConfirm: false
	}, function(){
		// 创建Form
	    var form = $('<form></form>');
	    // 设置属性
	    form.attr('action', url);
	    form.attr('method', 'post');
	    // form的target属性决定form在哪个页面提交
	    // _self -> 当前页面 _blank -> 新页面
	    form.attr('target', '_self');		if(id==0){			$("input[type='checkbox'][name='checkid[]']:checked").each(function(){				// 创建Input			    var my_input = $('<input type="text" name="id[]"/>');			    my_input.attr('value', $(this).val());			    // 附加到Form			    form.append(my_input);			});		}else{						// 创建Input			var my_input = $('<input type="text" name="id"/>');					my_input.attr('value', id);					// 附加到Form					form.append(my_input);		}
	    

	    // 创建Input
	    var my_input2 = $('<input type="text"/>');
	    my_input2.attr('name', name);
	    my_input2.attr('value', val);
	    // 附加到Form
	    form.append(my_input2);
	    $("#renew_submit").append(form);
		// 提交表单
		form.submit();
		return true;
	});

}

function check_field(url,id,name,val){
	swal({
		title: "更新数据",
		text: "新数据将会替换原来的记录",
		type: 'input',
		showCancelButton: true,
		confirmButtonText: "确认",
        cancelButtonText: '取消',
		closeOnConfirm: false,
		animation: "slide-from-top",
		inputPlaceholder: "请输入内容",
		inputValue: val
	},
	function(inputValue){
		if (inputValue === false) return false;

		if (inputValue === "") {
			swal.showInputError("修改内容不能为空!");
			return false;
		}

		if (inputValue == val) {
			swal.showInputError("修改内容无改变!");
			return false;
		}

		//swal("Nice!", "You wrote: " + inputValue, "success");

		// 创建Form
	    var form = $('<form></form>');
	    // 设置属性
	    form.attr('action', url);
	    form.attr('method', 'post');
	    // form的target属性决定form在哪个页面提交
	    // _self -> 当前页面 _blank -> 新页面
	    form.attr('target', '_self');
	    // 创建Input
	    var my_input = $('<input type="text" name="id"/>');
	    my_input.attr('value', id);
	    // 附加到Form
	    form.append(my_input);

	    // 创建Input
	    var my_input2 = $('<input type="text"/>');
	    my_input2.attr('name', name);
	    my_input2.attr('value', inputValue);
	    // 附加到Form
	    form.append(my_input2);
	    $("#renew_submit").append(form);
		// 提交表单
		form.submit();
		return true;

	});

}