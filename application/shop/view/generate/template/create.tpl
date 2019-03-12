<!-- 模板继承 -->
{extend name="template/create" /}
{block name="content"}
<div class="panel panel-default">
        <div class="panel-heading">
          <div class="panel-btns">
            <a href="" class="panel-close">&times;</a>
            <a href="" class="minimize">&minus;</a>
          </div>
          <h4 class="panel-title">{:$mate_operate}-{:$mate_title}</h4>
        </div>
        <div class="panel-body panel-body-nopadding">

        <form class="form-horizontal form-bordered" id="submitForm" action="" method="post" >
[ROWS]
        <div class="panel-footer">
         <div class="row">
          <div class="col-sm-6 col-sm-offset-3">
            <input type="hidden" name="id" value="{$vo.id ?? ''}">
            <button type="submit" class="btn btn-primary">提交</button>
            <button type="button" class="btn btn-default" onclick="javascript:history.back(-1);">返回</button>
          </div>
         </div>
        </div><!-- panel-footer -->

    </form>
          
</div><!-- panel-body -->
{/block}
[SET_FILEINPUT]
[SET_UEDITOR]
{block name="script"}
<script>
$(document).ready(function() {
[SET_VALUE]

    $('#submitForm').bootstrapValidator({
        message: '这个值是无效的',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        }
    });
    //select2
    jQuery(".select2").select2({
        width: '100%',
        minimumResultsForSearch: -1
    });
});
</script>
{/block}
