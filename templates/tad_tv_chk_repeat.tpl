<h2><{$smarty.const._MA_TADTV_TAD_CHK_REPEAT}></h2>
<{if $all_content}>
  <form action="main.php" method="post">
    <table class="table table-striped table-hover table-bordered">
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
        <{assign var="i" value=1}>
        <{foreach from=$all_content item=data}>
          <tr>
            <td nowrap="nowrap">
              <!--名稱-->
              <label class="checkbox-inline">
                <input type="checkbox" name="del_urls[]" id="del_urls_<{$data.tad_tv_sn}>" class="m3u8_sn" value="<{$data.tad_tv_sn}>"><{$data.cate.tad_tv_cate_title}> / <{$data.tad_tv_title}>
              </label>
            </td>

            <{if $i==1}>
              <td rowspan="<{$data.rowspan}>" style="vertical-align: middle;">
                <!--網址-->
                <a href="<{$xoops_url}>/modules/tad_tv/index.php?tad_tv_sn=<{$data.tad_tv_sn}>" target="_blank"><{$data.tad_tv_url}></a>
              </td>
            <{/if}>
            <{if $i < $data.rowspan}>
              <{assign var="i" value=$i+1}>
            <{else}>
              <{assign var="i" value=1}>
            <{/if}>

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
<{else}>
  <div class="jumbotron text-center">
    <h1><{$smarty.const._MA_TADTV_TAD_NO_REPEAT}></h1>
  </div>
<{/if}>