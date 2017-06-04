<{if $all_content}>
  <{if $isAdmin}>
    <{$delete_tad_tv_func}>

      <{$tad_tv_jquery_ui}>
      <script type="text/javascript">
        $(document).ready(function(){

            $("#clickAll").click(function() {
            var x = document.getElementById("clickAll").checked;
            if(x){
              $(".m3u8_sn").each(function() {
                    $(this).prop("checked", true);
              });
            }else{
             $(".m3u8_sn").each(function() {
                     $(this).prop("checked", false);
             });
            }
          });

          $("#tad_tv_sort").sortable({ opacity: 0.6, cursor: "move", update: function() {
            var order = $(this).sortable("serialize");
            $.post("<{$xoops_url}>/modules/tad_tv/admin/tad_tv_save_sort.php", order + "&op=update_tad_tv_sort", function(theResponse){
              $("#tad_tv_save_msg").html(theResponse);
            });
          }
          });

          $("#chk_m3u8").click(function(){
            $( ".m3u8_sn" ).each(function( index ) {
              var sn=$(this).val();
              chk_m3u8(sn);
            })
          });
        });

        function chk_m3u8(sn){
          $("#m3u8_" + sn).show();
          $("#m3u8_" + sn).html("<img src='../images/loading.gif' alt='loading...'>");
          $.post("<{$xoops_url}>/modules/tad_tv/admin/tad_tv_chk_url.php", {tad_tv_sn: sn}, function(data){
            if(data){
              $("#m3u8_" + sn).html('<span class="label label-success"><{$smarty.const._MD_TADTV_TAD_TV_OK}></span>');
              $("#del_urls_"+sn).prop("checked",false);
            }else{
              $("#m3u8_" + sn).html('<span class="label label-danger" onclick="chk_m3u8('+sn+');" style="cursor: pointer;"><{$smarty.const._MD_TADTV_TAD_TV_NG}></span>');
              $("#del_urls_"+sn).prop("checked",true);
            }
          });
        }
      </script>



  <{/if}>

  <div id="tad_tv_save_msg"></div>

  <form action="main.php" method="post">
    <table class="table table-striped table-hover">
      <thead>
        <tr>
          <th>
            <!--名稱-->
            <input type="checkbox" id="clickAll" value="1">
            <{$smarty.const._MD_TADTV_TAD_TV_TITLE}>
          </th>
          <th>
            <!--網址-->
            <{$smarty.const._MD_TADTV_TAD_TV_URL}>
          </th>
          <th nowrap="nowrap">
            <!--是否啟用-->
            <{$smarty.const._MD_TADTV_TAD_TV_ENABLE}>
          </th>
          <th nowrap="nowrap">
            <!--人氣-->
            <{$smarty.const._MD_TADTV_TAD_TV_COUNTER}>
          </th>
          <{if $isAdmin}>
            <th><{$smarty.const._TAD_FUNCTION}></th>
          <{/if}>
        </tr>
      </thead>

      <tbody id="tad_tv_sort">
        <{foreach from=$all_content item=data}>
          <tr id="tr_<{$data.tad_tv_sn}>">

            <td nowrap="nowrap">
              <!--名稱-->
              <span id="m3u8_<{$data.tad_tv_sn}>" style="margin-right:20px;display: none;"><img src='../images/loading.gif' alt='loading...'></span>
              <label class="checkbox-inline">
                <input type="checkbox" name="del_urls[<{$data.tad_tv_sn}>]" id="del_urls_<{$data.tad_tv_sn}>" value="<{$data.tad_tv_sn}>" class="m3u8_sn">
                <a href="<{$xoops_url}>/modules/tad_tv/index.php?tad_tv_sn=<{$data.tad_tv_sn}>" target="_blank"><{$data.tad_tv_title}></a>
              </label>
            </td>

            <td style="word-break: break-all;">
              <!--網址-->
              <a href="<{$data.tad_tv_url}>" target="_blank"><{$data.tad_tv_url}></a>
            </td>

            <td nowrap="nowrap" style="text-align: center;">
              <!--是否啟用-->
              <{if $data.tad_tv_enable=='1'}>
                <a href="main.php?op=unable_tv&tad_tv_sn=<{$data.tad_tv_sn}>"><img src="../images/yes.gif" alt="enable"></a>
              <{else}>
                <a href="main.php?op=enable_tv&tad_tv_sn=<{$data.tad_tv_sn}>"><img src="../images/no.gif" alt="enable"></a>
              <{/if}>
            </td>

            <td nowrap="nowrap" style="text-align: center;">
              <!--人氣-->
              <{$data.tad_tv_counter}>
            </td>

            <{if $isAdmin}>
              <td nowrap="nowrap">
                <a href="javascript:delete_tad_tv_func(<{$data.tad_tv_sn}>);" class="btn btn-xs btn-danger"><{$smarty.const._TAD_DEL}></a>
                <a href="<{$xoops_url}>/modules/tad_tv/admin/main.php?op=tad_tv_form&tad_tv_sn=<{$data.tad_tv_sn}>" class="btn btn-xs btn-warning"><{$smarty.const._TAD_EDIT}></a>
                <a href="javascript:chk_m3u8(<{$data.tad_tv_sn}>);" class="btn btn-xs btn-primary"><{$smarty.const._MA_TADTV_CHECK_AGAIN}></a>
                <a href="<{$xoops_url}>/modules/tad_tv/admin/main.php?op=chk_url&tad_tv_url=<{$data.tad_tv_url2}>" class="btn btn-xs btn-info" target="_blank"><{$smarty.const._MA_TADTV_CHK_URL_FROM_SAME}></a>
                <img src="<{$xoops_url}>/modules/tadtools/treeTable/images/updown_s.png" style="cursor: s-resize;margin:0px 4px;" alt="<{$smarty.const._TAD_SORTABLE}>" title="<{$smarty.const._TAD_SORTABLE}>">
              </td>
            <{/if}>
          </tr>
        <{/foreach}>
      </tbody>
    </table>
    <{if $isAdmin}>

      <div class="form-group" style="margin:30px auto;">
        <label class="sr-only control-label">
          <{$smarty.const._MA_TADTV_MOVE_TO}>
        </label>
        <div class="col-sm-3">

          <div class="input-group">
            <select name="to_tad_tv_cate_sn" class="form-control">
              <option value=""><{$smarty.const._MA_TADTV_MOVE_TO}></option>
              <{foreach from=$cates key=tad_tv_cate_sn item=cate}>
                <option value="<{$tad_tv_cate_sn}>"><{$cate.tad_tv_cate_title}></option>
              <{/foreach}>
            </select>
            <span class="input-group-btn">
              <button class="btn btn-primary" name="op" value="move_tv" type="submit"><{$smarty.const._MA_TADTV_MOVE}></button>
            </span>
          </div>



        </div>
        <div class="col-sm-9 text-right">

          <button type="submit" name="op" value="del_urls" class="btn btn-danger"><{$smarty.const._MA_TADTV_DEL_ALL}></button>
          <button type="submit" name="op" value="unable_urls" class="btn btn-warning"><{$smarty.const._MA_TADTV_UNABLE_ALL}></button>
          <a href="<{$xoops_url}>/modules/tad_tv/admin/main.php?op=tad_tv_form&tad_tv_cate_sn=<{$tad_tv_cate_sn}>" class="btn btn-info"><{$smarty.const._TAD_ADD}></a>
        </div>
      </div>


    <{/if}>
  </form>
  <{$bar}>
<{else}>
  <{if $isAdmin}>
    <div class="jumbotron text-center">
      <a href="<{$xoops_url}>/modules/tad_tv/admin/main.php?op=tad_tv_form&tad_tv_cate_sn=<{$tad_tv_cate_sn}>" class="btn btn-info"><{$smarty.const._TAD_ADD}></a>
    </div>
  <{/if}>
<{/if}>
