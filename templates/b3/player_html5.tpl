<link href="class/video-js/video-js.css" rel="stylesheet">
<script src="class/video-js/video.js"></script>
<script src="class/video-js/videojs-contrib-hls.js"></script>
<script>
  videojs.options.flash.swf = "class/video-js/video-js.swf"
</script>


<video id="my-video" autoplay="1" class="video-js vjs-default-skin" controls preload="auto" style="width: 100%; height:450px;" data-setup="{}">
  <source id="m3u8_url" src="http://208.75.225.28/live/NEXTV/playlist.m3u8" type="application/x-mpegURL" >
</video>

<script type="text/javascript">
    var player = videojs('my-video');
    player.play();

    var prefix='<{$xoops_url}>/modules/tad_tv/proxy.php?url=';
    // var prefix='';

    $(document).ready(function() {

        $('#test_btn').click(function() {
          tad_tv_reset();
          var prefix=use_proxy();
          $('source').attr('src', prefix + $('#m3u8Test').val());
        });


        $('#m3u8Test').change(function(){
          tad_tv_reset();
          var prefix=use_proxy();
          $('source').attr('src', prefix + $('#m3u8Test').val());
        });

        $('#streamURL').change(function(){
          var prefix=use_proxy();
          tad_tv_script($('#streamURL').val());
          $('source').attr('src', prefix + $('#streamURL').val());
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
        $('source').attr('src', prefix + manifest);
    });
</script>
