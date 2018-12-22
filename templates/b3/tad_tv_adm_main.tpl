<link href="<{$xoops_url}>/modules/tadtools/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<div class="container-fluid">
  <{if $now_op=='chk_url'}>
    <{includeq file="$xoops_rootpath/modules/tad_tv/templates/tad_tv_check_list.tpl"}>
  <{elseif $now_op=='chk_repeat'}>
    <{includeq file="$xoops_rootpath/modules/tad_tv/templates/tad_tv_chk_repeat.tpl"}>
  <{else}>
    <div class="row">
      <div class="col-sm-3">
        <div id="save_msg"></div>

        <{$ztree_code}>

        <a href="main.php?op=tad_tv_cate_form" class="btn btn-info btn-block"><{$smarty.const._TAD_ADD_CATE}></a>

        <h3><{$smarty.const._MA_TADTV_IMPORT_CSV}> <{if $cate.tad_tv_cate_title}>to <{$cate.tad_tv_cate_title}><{/if}></h3>
        <form action="main.php" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
          <div class="form-group">
            <label class="sr-only control-label">
              <{$smarty.const._MA_TADTV_IMPORT_CSV}>
            </label>
            <div class="col-sm-9">
              <input type="file" name="userfile">
              (<a href="../demo.csv"><{$smarty.const._MA_TADTV_CSV_DEMO}></a>)
            </div>
            <div class="col-sm-3">
              <input type="hidden" name="op" value="import_csv">
              <input type="hidden" name="tad_tv_cate_sn" value="<{$tad_tv_cate_sn}>">
              <button type="submit" class="btn btn-primary btn-block"><{$smarty.const._MA_TADTV_IMPORT}></button>
            </div>
          </div>
        </form>
        <h3><{$smarty.const._MA_TADTV_IMPORT_M3U}> <{if $cate.tad_tv_cate_title}>to <{$cate.tad_tv_cate_title}><{/if}></h3>
        <form action="main.php" method="post" enctype="multipart/form-data" class="form-horizontal" role="form">
          <div class="form-group">
            <label class="sr-only control-label">
              <{$smarty.const._MA_TADTV_IMPORT_M3U}>
            </label>
            <div class="col-sm-9">
              <input type="file" name="userfile">
              (<a href="../demo.m3u"><{$smarty.const._MA_TADTV_M3U_DEMO}></a>)
            </div>
            <div class="col-sm-3">
              <input type="hidden" name="tad_tv_cate_sn" value="<{$tad_tv_cate_sn}>">
              <input type="hidden" name="op" value="import_m3u">
              <button type="submit" class="btn btn-primary btn-block"><{$smarty.const._MA_TADTV_IMPORT}></button>
            </div>
          </div>
        </form>
      </div>

      <div class="col-sm-9">
        <{if $now_op=='list_tad_tv' or $now_op==''}>
          <div class="row">
            <div class="col-sm-4">
              <h2>
                <{$cate.tad_tv_cate_title}>
                <small>
                <a href="export_csv.php?tad_tv_cate_sn=<{$tad_tv_cate_sn}>" class="btn btn-success"><{$smarty.const._MA_TADTV_EXPORT_CSV}></a>
                <a href="export_m3u.php?tad_tv_cate_sn=<{$tad_tv_cate_sn}>" class="btn btn-success"><{$smarty.const._MA_TADTV_EXPORT_M3U}></a>
                </small>
              </h2>
            </div>
            <div class="col-sm-8 text-right">
              <div style="margin-top: 10px;">
                <{if $tad_tv_cate_sn > 0}>
                  <a href="javascript:delete_tad_tv_cate_func(<{$cate.tad_tv_cate_sn}>);" class="btn btn-danger <{if $cate_count.$tad_tv_cate_sn > 0}>disabled<{/if}>"><{$smarty.const._TAD_DEL}></a>
                  <a href="main.php?op=tad_tv_cate_form&tad_tv_cate_sn=<{$tad_tv_cate_sn}>" class="btn btn-warning"><{$smarty.const._TAD_EDIT}></a>
                  <a href="main.php?op=tad_tv_form&tad_tv_cate_sn=<{$tad_tv_cate_sn}>" class="btn btn-info"><{$smarty.const._TAD_ADD}></a>
                <{/if}>
                <a href="main.php?op=chk_repeat" class="btn btn-warning"><{$smarty.const._MA_TADTV_TAD_CHK_REPEAT}></a>
                <button type="button" id="chk_m3u8" class="btn btn-primary"><{$smarty.const._MD_TADTV_TAD_CHK_URL}></button>
              </div>
            </div>
          </div>
        <{/if}>

        <{if $now_op=="tad_tv_cate_form"}>
          <{includeq file="$xoops_rootpath/modules/tad_tv/templates/tad_tv_cate_edit_form.tpl"}>
        <{elseif $now_op=="tad_tv_form"}>
          <{includeq file="$xoops_rootpath/modules/tad_tv/templates/tad_tv_edit_form.tpl"}>
        <{elseif $now_op=="list_tad_tv"}>
          <form action="main.php" method="post" class="form-horizontal" role="form">
            <{includeq file="$xoops_rootpath/modules/tad_tv/templates/tad_tv_list.tpl"}>
          </form>
        <{else}>
          <div class="alert alert-danger text-center">
            <h3><{$smarty.const._TAD_EMPTY}></h3>
          </div>
        <{/if}>
      </div>
    </div>
  <{/if}>
</div>