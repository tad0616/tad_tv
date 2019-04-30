<h2 class="text-center"><{$smarty.const._MA_TADTV_FORM}></h2>

<form action="<{$action}>" method="post" id="myForm" enctype="multipart/form-data" role="form">


    <!--名稱-->
    <div class="form-group row">
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TADTV_TAD_TV_TITLE}>
      </label>
      <div class="col-md-10">
        <input type="text" name="tad_tv_title" id="tad_tv_title" class="form-control validate[required]" value="<{$tad_tv_title}>" placeholder="<{$smarty.const._MD_TADTV_TAD_TV_TITLE}>">
      </div>
    </div>

    <!--網址-->
    <div class="form-group row">
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TADTV_TAD_TV_URL}>
      </label>
      <div class="col-md-10">
        <input type="text" name="tad_tv_url" id="tad_tv_url" class="form-control " value="<{$tad_tv_url}>" placeholder="<{$smarty.const._MD_TADTV_TAD_TV_URL}>">
      </div>
    </div>

    <!--順序-->
    <div class="form-group row">
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TADTV_TAD_TV_SORT}>
      </label>
       <div class="col-md-2">
        <input type="text" name="tad_tv_sort" id="tad_tv_sort" class="form-control " value="<{$tad_tv_sort}>" placeholder="<{$smarty.const._MD_TADTV_TAD_TV_SORT}>">
      </div>
    </div>

    <!--是否啟用-->
    <div class="form-group row">
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TADTV_TAD_TV_ENABLE}>
      </label>
      <div class="col-md-10">
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="tad_tv_enable" id="tad_tv_enable_1" value="1" <{if $tad_tv_enable == "1"}>checked="checked"<{/if}>>
          <label class="form-check-label" for="tad_tv_enable_1"><{$smarty.const._YES}></label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" type="radio" name="tad_tv_enable" id="tad_tv_enable_0" value="0" <{if $tad_tv_enable != "1"}>checked="checked"<{/if}>>
          <label class="form-check-label" for="tad_tv_enable_0"><{$smarty.const._NO}></label>
        </div>
      </div>
    </div>

    <!--所屬類別-->
    <div class="form-group row">
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TADTV_TAD_TV_CATE_SN}>
      </label>
      <div class="col-md-5">
        <select name="tad_tv_cate_sn" class="form-control " size=1>
          <option value=""></option>
          <{foreach from=$tad_tv_cate_sn_options item=opt}>
            <option value="<{$opt.tad_tv_cate_sn}>" <{if $tad_tv_cate_sn==$opt.tad_tv_cate_sn}>selected<{/if}>><{$opt.tad_tv_cate_title}></option>
          <{/foreach}>
        </select>
      </div>
    </div>

    <!--說明-->
    <div class="form-group row">
      <label class="col-md-2 col-form-label text-sm-right">
        <{$smarty.const._MD_TADTV_TAD_TV_CONTENT}>
      </label>
      <div class="col-md-10">
        <textarea name="tad_tv_content" rows=8 id="tad_tv_content" class="form-control " placeholder="<{$smarty.const._MD_TADTV_TAD_TV_CONTENT}>"><{$tad_tv_content}></textarea>
      </div>
    </div>

  <div class="text-center">

        <!--編號-->
        <input type='hidden' name="tad_tv_sn" value="<{$tad_tv_sn}>">

    <{$token_form}>

    <input type="hidden" name="op" value="<{$next_op}>">
    <button type="submit" class="btn btn-primary"><{$smarty.const._TAD_SAVE}></button>
  </div>
</form>
