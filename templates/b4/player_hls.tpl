<script src="class/hls.min.js"></script>
<video id="video" controls style="width: 100%; height:450px;"></video>

<script type="text/javascript">
    function hls_player(url){
      if(Hls.isSupported()) {
        var video = document.getElementById('video');
        var hls = new Hls();

        hls.attachMedia(video);
        hls.on(Hls.Events.MEDIA_ATTACHED, function () {
          console.log("video and hls.js are now bound together !");
          hls.loadSource(url);
          hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {
            console.log("manifest loaded, found " + data.levels.length + " quality level");
            video.play();
          });
        });

        hls.on(Hls.Events.ERROR, function (event, data) {
          if (data.fatal) {
            switch(data.type) {
            case Hls.ErrorTypes.NETWORK_ERROR:
            // try to recover network error
              console.log("fatal network error encountered, try to recover");
              hls.startLoad();
              break;
            case Hls.ErrorTypes.MEDIA_ERROR:
              console.log("fatal media error encountered, try to recover");
              hls.recoverMediaError();
              break;
            default:
            // cannot recover
              hls.destroy();
              break;
            }
          }
        });
      }
    }

    $(document).ready(function() {


        $('#test_btn').click(function() {
          tad_tv_reset();
          var prefix=use_proxy();
          hls_player(prefix+$('#m3u8Test').val());
        });


        $('#m3u8Test').change(function() {
          tad_tv_reset();
          var prefix=use_proxy();
          hls_player(prefix+$('#m3u8Test').val());
        });

        $('#streamURL').change(function() {
          var prefix=use_proxy();
          tad_tv_script($('#streamURL').val());
          hls_player(prefix+$('#streamURL').val());
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
        var prefix=use_proxy();
        hls_player(prefix+manifest);
    });
</script>
