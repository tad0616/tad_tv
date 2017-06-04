<script type="text/javascript">
  $(document).ready(function(){
    $("#chk_m3u8").click(function(){
      $( ".m3u8_sn" ).each(function( index ) {
        var sn=$(this).val();
        chk_m3u8(sn);
      });
    })
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

<h2><{$testurl}><{$smarty.const._MA_TADTV_M3U8_FROM_URL}><small> (<{$smarty.const._MA_TADTV_DATA_TOTAL}><{$total}>)</small></h2>
<{if $chkwwwsrv}>
  <div class="alert alert-success">
  <{$testurl}> <{$smarty.const._MA_TADTV_SERVER_OK}>
  </div>
<{else}>
  <div class="alert alert-danger">
  <{$testurl}> <{$smarty.const._MA_TADTV_SERVER_NG}>
  </div>
<{/if}>

  <button type="button" id="chk_m3u8" class="btn btn-primary"><{$smarty.const._MD_TADTV_TAD_CHK_URL}></button>

<form action="main.php" method="post">
  <table class="table table-striped table-hover">
    <thead>
      <tr>
          <th>
            <!--名稱-->
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
      <{foreach from=$chk_url item=data}>
        <tr id="tr_<{$data.tad_tv_sn}>">

            <td nowrap="nowrap">
              <!--名稱-->
              <label class="checkbox-inline">
                <input type="checkbox" name="del_urls[]" id="del_urls_<{$data.tad_tv_sn}>" class="m3u8_sn" value="<{$data.tad_tv_sn}>" <{if !$chkwwwsrv}>checked<{/if}>>
                <a href="<{$xoops_url}>/modules/tad_tv/index.php?tad_tv_sn=<{$data.tad_tv_sn}>" target="_blank"><{$data.tad_tv_title}></a>
              </label>
            </td>

            <td>
              <!--網址-->
              <span id="m3u8_<{$data.tad_tv_sn}>" style="margin-right:20px;display: none;"><img src='../images/loading.gif' alt='loading...'></span>

              <a href="<{$data.tad_tv_url}>" target="_blank"><{$data.tad_tv_url}></a>
            </td>

            <td nowrap="nowrap" style="text-align: center;">
              <!--是否啟用-->
              <{if $data.tad_tv_enable=='1'}>
                <img src="../images/yes.gif" alt="enable">
              <{else}>
                <img src="../images/no.gif" alt="enable">
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
            </td>
          <{/if}>
        </tr>
      <{/foreach}>
    </tbody>
  </table>
  <div class="text-center" style="margin:30px auto;">
    <button type="submit" name="op" value="del_urls" class="btn btn-danger"><{$smarty.const._MA_TADTV_DEL_ALL}></button>
    <button type="submit" name="op" value="unable_urls" class="btn btn-warning"><{$smarty.const._MA_TADTV_UNABLE_ALL}></button>
  </div>
</form>