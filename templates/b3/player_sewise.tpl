<div id="player" style="width: 100%; height: 450px; "></div>

<script type="text/javascript" src="class/sewise-player/sewise.player.min.js"></script>
<script type="text/javascript">
    SewisePlayer.setup({
        server: "vod",
        type: "m3u8",
        autostart: "true",
        poster: "http://jackzhang1204.github.io/materials/poster.png",
        videourl: "http://208.75.225.28/live/NEXTV/playlist.m3u8",
        skin: "vodWhite",
        title: "",
        claritybutton: "disable",
        lang: 'zh_CN'
    }, "player");

    SewisePlayer.playerReady(function(id){
        $('#showError').hide();
    });

    $(document).ready(function(){

        if(!hasFlash()){
          $('#showError').show();
          $('#showError').html('<{$smarty.const._MD_TADTV_TAD_NEED_FLASH}>');
        }

        $('#test_btn').click(function() {
          tad_tv_reset();
          var prefix=use_proxy();
          SewisePlayer.toPlay(prefix+$('#m3u8Test').val(), '', 0, true);
        });


        $('#m3u8Test').change(function(){
          tad_tv_reset();
          var prefix=use_proxy();
          SewisePlayer.toPlay(prefix+$('#m3u8Test').val(), '', 0, true);
        });

        $('#streamURL').change(function(){
          tad_tv_script($('#streamURL').val());
          var prefix=use_proxy();
          SewisePlayer.toPlay(prefix+$('#streamURL').val(), '', 0, true);
        });

        var manifest = decodeURIComponent(location.search.split('src=')[1]);
        <{if $def_tad_tv_sn==""}>
          if(manifest === 'undefined') {
            manifest = 'http://208.75.225.28/live/NEXTV/playlist.m3u8';
          }
        <{else}>
          manifest = '<{$the_tv.tad_tv_url}>';
        <{/if}>

        var prefix=use_proxy();
        $('#streamURL').val(prefix+manifest);
        tad_tv_script(prefix+manifest);
    });
</script>