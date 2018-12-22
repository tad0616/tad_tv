<{$toolbar}>

<script type="text/javascript">

  function tad_tv_script(url){
    var tv_sn=$("#streamURL option:selected").data('sn');
    var tv_title=$("#streamURL option:selected").data('title');
    var tv_cate=$("#streamURL option:selected").data('cate');
    if(tv_title){
      $('#tv_del').show();
      $('#tv_del').html('<{$smarty.const._TAD_DEL}> ' + tv_title);
      $('#tv_del').attr('href','javascript:delete_tad_tv_func(' + tv_sn + ');');

      $('#tv_update').show();
      $('#tv_update').html('<{$smarty.const._MD_TADTV_UNABLE}> ' + tv_title);
      $('#tv_update').attr('href','index.php?op=unable_tv&tad_tv_sn=' + tv_sn + '');

    }else{
      $('#tv_del').hide();
      $('#tv_update').hide();
    }

    $('#tv_tools').removeClass('btn-info').addClass('btn-warning').html('<{$smarty.const._TAD_EDIT}>');
    $('#m3u8Test').val(url);
    $('#tv_title').html(tv_title);
    $('#tad_tv_title').val(tv_title);
    $('#tad_tv_cate').val(tv_cate);
    $('#op').val('update_tad_tv');
    $('#tad_tv_sn').val(tv_sn);
  }

  function tad_tv_reset(){
    $('#tv_tools').removeClass('btn-warning').addClass('btn-info').html('<{$smarty.const._MD_TADTV_ADD}>');
    $('#tad_tv_title').val('');
    $('#tad_tv_cate').val('');
    $('#op').val('insert_tad_tv');
    $('#tad_tv_sn').val(0);
    $('#showError').html('').hide();
  }

  function use_proxy(){
    if($('#tad_tv_proxy').prop('checked')){
      var prefix = '<{$xoops_url}>/modules/tad_tv/proxy.php?url=';
    } else {
      var prefix = '';
    }
    console.log('prefix='+prefix);
    return prefix;
  }

  function hasFlash(){

    var hasFlash = false;
    try {
      var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
      if (fo) {
        hasFlash = true;
      }
    } catch (e) {
      if (navigator.mimeTypes
            && navigator.mimeTypes['application/x-shockwave-flash'] != undefined
            && navigator.mimeTypes['application/x-shockwave-flash'].enabledPlugin) {
        hasFlash = true;
      }
    }
    return hasFlash;
  }

  function hasVLC(){
    var name = "VLC";
    if (navigator.plugins && (navigator.plugins.length > 0)) {
        for(var i=0;i<navigator.plugins.length;++i)
            if (navigator.plugins[i].name.indexOf(name) != -1)
              return true;
    }
    else {
        try {
            new ActiveXObject("VideoLAN.VLCPlugin.2");
            return true;
        } catch (err) {}
    }
    return false;
  }


  $(document).ready(function() {
    <{if !$live}>
      $('#showError').html('<{$smarty.const._MD_TADTV_DOWNLOAD_FAIL}>').show();
    <{/if}>
    $('#tv_tools').click(function(){
      $('#save_form').toggle();
    });
  });
</script>

<!--列出所有資料-->
<div class="row">
  <div class="col-md-3">
    <select id="streamURL" size=30 class="form-control">
    <{foreach from=$all_content key=tad_tv_cate_title item=data}>
      <optgroup label="<{$tad_tv_cate_title}>">
        <{foreach from=$data key=i item=tv}>
          <option value="<{$tv.tad_tv_url}>" title="<{$tv.tad_tv_cate_title}>" data-sn="<{$tv.tad_tv_sn}>" data-cate="<{$tv.tad_tv_cate_sn}>" data-title="<{$tv.tad_tv_title}>" <{if $def_tad_tv_sn==$tv.tad_tv_sn}>selected<{/if}>>[<{$i}>] <{$tv.tad_tv_title}></option>
        <{/foreach}>
      </optgroup>
    <{/foreach}>
    </select>
  </div>
  <div class="col-md-9">
    <{if $player=="html5"}>
      <{includeq file="$xoops_rootpath/modules/tad_tv/templates/player_html5.tpl"}>
    <{elseif $player=="vlc"}>
      <{includeq file="$xoops_rootpath/modules/tad_tv/templates/player_vlc.tpl"}>
    <{elseif $player=="flash"}>
      <{includeq file="$xoops_rootpath/modules/tad_tv/templates/player_flash.tpl"}>
    <{elseif $player=="sewise"}>
      <{includeq file="$xoops_rootpath/modules/tad_tv/templates/player_sewise.tpl"}>
    <{elseif $player=="hls"}>
      <{includeq file="$xoops_rootpath/modules/tad_tv/templates/player_hls.tpl"}>
    <{else}>
      <{includeq file="$xoops_rootpath/modules/tad_tv/templates/player_flash.tpl"}>
    <{/if}>


      <div class="alert alert-info">
        <form action="index.php" method="post" id="myForm" enctype="multipart/form-data" role="form">
          <{if $tad_tv_groupperm_1}>
          <div class="form-group row">
            <label id="tv_title" class="col-md-2 col-form-label text-sm-right">
              <{$smarty.const._MD_TADTV_TAD_TV_URL}>
            </label>
            <div class="col-md-7">
              <input type="text" name="tad_tv_url" id="m3u8Test" value="" placeholder="" class="form-control">
            </div>
            <div class="col-md-3">
              <div class="btn-group">
                <button type="button" id="test_btn" class="btn btn-success"><{$smarty.const._MD_TADTV_TEST}></button>
                <{if $isAdmin}><button type="button" id="tv_tools" class="btn btn-info"><{$smarty.const._MD_TADTV_ADD}></button><{/if}>
              </div>
            </div>
          </div>
          <{/if}>

          <div class="form-group row">
            <div class="col-md-2"></div>
            <label class="col-md-2 col-form-label text-sm-right sr-only">
              <{$smarty.const._MD_TADTV_PROXY}>
            </label>
            <div class="col-md-10">
              <label class="checkbox-inline" for="tad_tv_proxy">
                <input type="checkbox" name="tad_tv_proxy" id="tad_tv_proxy" value="1" ><{$smarty.const._MD_TADTV_PROXY_DESC}>
              </label>
            </div>
          </div>

          <{if $isAdmin}>
            <div id="save_form" style="display: none;">
              <div class="form-group row">
                <label class="col-md-2 col-form-label text-sm-right">
                  <{$smarty.const._MD_TADTV_TAD_TV_CATE_SN}>
                </label>
                <div class="col-md-4">
                  <select name="tad_tv_cate_sn"  id="tad_tv_cate" class="form-control " size=1>
                    <option value=""></option>
                    <{foreach from=$tad_tv_cate_sn_options item=opt}>
                      <option value="<{$opt.tad_tv_cate_sn}>" <{if $tad_tv_cate_parent_sn==$opt.tad_tv_cate_sn}>selected<{/if}>><{$opt.tad_tv_cate_title}></option>
                    <{/foreach}>
                  </select>
                </div>
                <div class="col-md-4">
                  <input type="text" name="tad_tv_title" id="tad_tv_title" value="" placeholder="<{$smarty.const._MD_TADTV_INPUT_TITLE}>" class="form-control">
                </div>
                <div class="col-md-2">
                  <{$token_form}>
                  <input type="hidden" name="op" id="op" value="insert_tad_tv">
                  <input type="hidden" name="player" value="<{$player}>">
                  <input type="hidden" name="tad_tv_sn" id="tad_tv_sn" value="<{$def_tad_tv_sn}>">
                  <button type="text" class="btn btn-primary btn-block"><{$smarty.const._TAD_SAVE}></button>
                </div>
              </div>

              <a id="tv_del" href="#" class="btn btn-danger" style="display: none;"><{$smarty.const._TAD_DEL}></a>
              <a id="tv_update" href="#" class="btn btn-warning" style="display: none;"><{$smarty.const._MD_TADTV_UNABLE}></a>

            </div>
          <{/if}>
        </form>
      </div>
      <div class="alert alert-danger" id="showError" style="display: none;"></div>
  </div>
</div>
