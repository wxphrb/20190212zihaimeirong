{extend name="template/index" /}
{block name="content"}
 <div class="panel panel-default">
         <div class="panel-heading" style="border-bottom:none;">
              <div>
              <a class="btn btn-primary" href="{:url('[MODULE]/[NAME]/create')}" style="float:right;">添加</a> 
              </div>
             <h4 class="panel-title">{:$mate_operate}</h4>
        </div>
        <div class="panel-body">
          
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="table1">
              <thead>
                 <tr>
                    [TH]
                    <th>操作</th>
                 </tr>
              </thead>
              <tbody>
                    {volist name="list" id="vo"}
                            <tr>
                                [TD]
                                <td>
                                    {$vo.id|show_operate='update','编 辑'}
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    {$vo.id|show_operate='delete','删 除'}
                                </td>
                            </tr>
                    {/volist}
              </tbody>
           </table>
             <!-- start-deleteArea -->
             <div class="form-inline">
                  <label class="control-label">操作:</label>&nbsp;&nbsp;
                  <div class="checkbox ckbox ckbox-danger"><input type="checkbox" id="checkboxAll" onclick="check_all(this);" />
                     <label for="checkboxAll"></label>
                  </div>  
                  {:show_operate('0','delete','删 除')} <!-- 全选删除   -->                                 
                </div>
              <!-- end-deleteArea -->
          </div><!-- table-responsive -->
        </div>
     </div>
  </div><!-- rightpanel -->

{/block}
{block name="script"}
<script>
  jQuery(document).ready(function() { 
    "use strict";
    jQuery('#table1').DataTable({
      responsive: true,
      "stateSave": true,
      "language": {
        "lengthMenu": "每页 _MENU_ 条记录<font id='loader' style='margin-left:50px;'></font>",
        "zeroRecords": "<div style='font-size:24px;margin:30px;'>没有找到记录</div>",
        "info": "第 _PAGE_ 页 ( 总共 _PAGES_ 页 )",
        "infoEmpty": "无记录",
        "infoFiltered": "(从 _MAX_ 条记录过滤)",
        "sSearch": "搜索",
        "paginate": {
          "previous": "上一页",
          "next": "下一页"
        }
      },
     /* "columnDefs":[{
                 orderable:false,//禁用排序
                 targets:[0]   //指定的列
             }],*/
  });
     // Select2
    jQuery('select').select2({
        minimumResultsForSearch: -1
    });
    
  });
</script>
{/block}
