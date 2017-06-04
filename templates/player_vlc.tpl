<iframe id="m3u8_url" title="vlc player" src="vlc.php" style="width: 100%; height:480px; background: #000; border: none;"></iframe>

<script type="text/javascript">
    $(document).ready(function() {

        if(!hasVLC()){
          $('#showError').show();
          $('#showError').html('<{$smarty.const._MD_TADTV_TAD_NEED_VLC}>');
        }

        $('#m3u8Test').change(function(){
          tad_tv_reset();
          $('#m3u8_url').attr('src',$('#m3u8Test').val());
        });

        $('#streamURL').change(function(){
            tad_tv_script($('#streamURL').val());
            $('#m3u8_url').attr('src',$('#streamURL').val());
        });

        var manifest = decodeURIComponent(location.search.split('src=')[1]);
        <{if $def_tad_tv_sn==""}>
          if(manifest === 'undefined') {
            manifest = 'http://208.75.225.28/live/NEXTV/playlist.m3u8';
          }
        <{else}>
          manifest = '<{$the_tv.tad_tv_url}>';
        <{/if}>
        $('#streamURL').val(manifest);
        tad_tv_script(manifest);
    });
</script>