<?php if (!defined('THINK_PATH')) exit();?><div style="position: relative;left: 0px;top: 0px;">
    <div style="position: absolute;left:10px;top: 10px;">类目安排 >>
        <font style="font-weight: bold"> 时间管理</font> </div>
    <div style="position: absolute;left: 50px;top: 30px;"><table id="admin_schedule_box" style="width: 900px;"></table>
</div>
<div id="admin_schedule_tb" style="">
    <div style="">
        <table style="width: 890px">
            <tr><td style="width: 450px;text-align: left;">
        <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="obj_window_schedule.add();">添加</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="obj_window_schedule.edit();">修改</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="obj_admin_schedule.remove();">删除</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-save" plain="true" style="display:none;" id="save" onclick="obj_admin_schedule.save();">保存</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-redo" plain="true" style="display:none;" id="redo" onclick="obj_admin_schedule.redo();">取消编辑</a>
            </td>
         </tr> </table>
    </div>
</div>
 
 <div id="window_add_schedule" class="easyui-window" title=" ">
</div>


   
    </div>
    </div>

</div>
<script type="text/javascript">

// 添加
$(function () {

     $('#window_add_schedule').window({
            width : 660,
            height:480,
            modal : true,
            closed:true,
            minimizable : false,
            maximizable : false,
            title:'时间安排',
            iconCls:'icon-man',
            collapsible:false
        });
     obj_window_schedule = {
        add:function(){
                    $('#window_add_schedule').window("refresh","./index.php/home/admin/add_schedule?123");
                    $('#window_add_schedule').window("open");
                },
        edit:function(){
                    $('#window_add_schedule').window("refresh","./index.php/home/admin/edit_schedule?456");
                    $('#window_add_schedule').window("open");
                },

     };
     


// 修改


    obj_admin_schedule = {
        editRow : undefined,
        search : function () {
            $('#admin_schedule_box').datagrid('load', {
                schedule_name : $.trim($('input[name="schedule_name"]').val()),
            });
        },
        save : function () {
  
            $('#admin_schedule_box').datagrid('endEdit', this.editRow);
        },
        redo : function () {
            $('#save,#redo').hide();
            this.editRow = undefined;
            $('#admin_schedule_box').datagrid('rejectChanges');
        },
       
        remove : function () {
            var rows = $('#admin_schedule_box').datagrid('getSelections');
            if (rows.length > 0) {
                $.messager.confirm('确定操作', '您正在要删除所选的记录吗？', function (flag) {
                    if (flag) {
                        var ids = [];
                        for (var i = 0; i < rows.length; i ++) {
                            ids.push(rows[i].schedule_id);
                        }
                        $.ajax({
                            type : 'POST',
                            url : '<?php echo U("deleteschedule_info");?>',
                            data : {
                                ids : ids.join(','),
                            },
                            beforeSend : function () {
                                $('#admin_schedule_box').datagrid('loading');
                            },
                            success : function (data) {
                                if (data) {
                                    $('#admin_schedule_box').datagrid('loaded');
                                    $('#admin_schedule_box').datagrid('load');
                                    $('#admin_schedule_box').datagrid('unselectAll');
                                    $.messager.show({
                                        title : '提示',
                                        msg : data+'学生被删除成功！',
                                    });
                                }
                            },
                        });
                    }
                });
            } else {
                $.messager.alert('提示', '请选择要删除的记录！', 'info');
            }
        },
    };

    $('#admin_schedule_box').datagrid({
        width : 900,
        url : '<?php echo U("readallschedule");?>',
        title : '<center>时间管理</center>',
        iconCls : 'icon-search',
        striped : true,
        nowrap : true,
        rownumbers : true,
        fitColumns : true,
        columns : [[
            {
                field : 'schedule_weekend',
                title : '星期',
                sortable : true,
                width : 150,
                formatter : function(value) {  
                    if (value==1) {  
                        return "星期一";  
                    }else if(value==2){  
                        return "星期二";  
                    }else if(value==3){  
                        return "星期三";  
                    }else if(value==4){  
                        return "星期四";  
                    }else if(value==5){  
                        return "星期五";  
                    }else if(value==6){  
                        return "星期六";  
                    }else{  
                        return "星期二";  
                    }
                }            
            },
            {
                field : 'schedule_time',
                title : '时间',
                sortable : true,
                width : 150,

            },
             {
                field : 'items_name',
                title : '类目',
                sortable : true,
                width : 150,

            },
            // 隐藏域
              {
                field : 'schedule_id',
                title : 'aa',
                hidden:'true',
                width : 0

            },
             {
                field : 'read_all_teacher',
                title : 'aa',
                hidden:'true',
                width : 0

            },
        ]],
        toolbar : '#admin_schedule_tb',
        pagination : true,
        pageSize : 10,
        pageList : [10, 20, 30],
        pageNumber : 1,
        sortName : 'schedule_weekend',//排序的字段
       // sortOrder : 'DESC',  修改默认为升序
        onDblClickRow : function (rowIndex, rowData) {

            if (obj_admin_schedule.editRow != undefined) {
                $('#admin_schedule_box').datagrid('endEdit', obj_admin_schedule.editRow);
            }
            else{
                if (obj_admin_schedule.editRow == undefined) {
                $('#save,#redo').show();
                 obj_admin_schedule.editRow = rowIndex;
                $('#admin_schedule_box').datagrid('beginEdit', rowIndex);
                    obj_admin_advisor.editRow = rowIndex;

                 }
            }

        },
      
    });

    });


</script>