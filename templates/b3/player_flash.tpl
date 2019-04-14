<script type="text/javascript" src="class/flashls/flashlsAPI.js"></script>
<script type="text/javascript" src="class/flashls/ParsedQueryString.js"></script>
<script type="text/javascript" src="class/flashls/JSLoaderFragment.js"></script>
<script type="text/javascript" src="class/flashls/JSLoaderPlaylist.js"></script>
<script type="text/javascript" src="class/flashls/js2flash.js"></script>
<script type="text/javascript" src="class/flashls/canvas.js"></script>
<script type="text/javascript" src="class/flashls/metrics.js"></script>
<script type="text/javascript" src="class/flashls/jsonpack.js"></script>

<div class="center" id="video">
    <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
     codebase="" id="moviename" width="100%" height="480">
    <param name="movie"  value="class/flashls/bin/flashlsChromeless.swf?inline=1">
    <param name="quality" value="autohigh">
    <param name="swliveconnect" value="true">
    <param name="allowScriptAccess" value="sameDomain">
    <param name="bgcolor" value="#0">
    <param name="allowFullScreen" value="true">
    <param name="wmode" value="window">
    <param name="FlashVars" value="callback=flashlsCallback">
    <param name="autostart" value="true">
    <param name="play" value="true">
    <param name="hls_debug" value="true">

    <embed src="class/flashls/bin/flashlsChromeless.swf?inline=1" width="100%" height="480" name="moviename"
                autostart="true"
                play="true"
                quality="autohigh"
                bgcolor="#0"
                align="middle" allowFullScreen="true"
                allowScriptAccess="sameDomain"
                type="application/x-shockwave-flash"
                swliveconnect="true"
                hls_debug="true"
                wmode="window"
                FlashVars="callback=flashlsCallback"
                pluginspage="http://www.macromedia.com/go/getflashplayer" >
    </embed>
    Flash Player
    </object>
    <br><br>
    <canvas id="buffered_c" width="100%" height="6" onclick="buffered_seek(event);" onkeypress="buffered_seek(event);" tabindex=1></canvas>

    <script type="text/javascript">

      var events;
      var jsPingDate,flashPingDate, jsLoadDate, flashLoadDate;
      var lastSeekingIdx,lastFragPlayingIdx;
      var api;
      var levels;

      $(document).ready(function() {

        if(!hasFlash()){
          $('#showError').show();
          $('#showError').html('<{$smarty.const._MD_TADTV_TAD_NEED_FLASH}>');
        }

        $('#m3u8Test').change(function(){
          tad_tv_reset();
          var prefix=use_proxy();
          loadStream(prefix+$('#m3u8Test').val());
        });


        $('#streamURL').change(function() {
          tad_tv_script($('#streamURL').val());
          var prefix=use_proxy();
          loadStream(prefix+$('#streamURL').val());
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
        loadStream(prefix+manifest);
      });

      function loadStream(url) {
        api.stop();
        load(url);

        $('#streamURL').val(url);
        var hlsLink = document.URL.split('?')[0] +  '?src=' + encodeURIComponent(url);
        var description = 'permalink: ' + "<a href=\"" + hlsLink + "\">" + hlsLink + "</a>";
        // $("#StreamPermalink").html(description);
      }

      function appendLog(txt)
      {
        var d = new Date();
        var msg = '[' + d.toString() + '] ' + txt;
        console.log(msg);
      }

      function load(url) {
        jsLoadDate = new Date();
        flashLoadDate = (jsLoadDate - jsPingDate )+ flashPingDate;
        lastSeekingIdx = lastFragPlayingIdx = undefined;
        levels = null;
        events = { url : url, date : jsLoadDate, load : [], buffer : [], video : []};
        api.load(url);
        appendLog("load URL " + url);
        $('#currentPosition').html(0);
        $('#currentDuration').html(0);
        $('#bufferLength').html(0);
        $('#backBufferLength').html(0);
        $('#sliding').html(0);
        $('#watched').html(0);
        $('#showError').hide();
      }

      function toggleDebugLogs() {
        var state = api.getLogDebug();
        state = !state;
        api.playerSetLogDebug(state);
        $('#debugLogState').html(state.toString());
      }

      function toggleDebug2Logs() {
        var state = api.getLogDebug2();
        state = !state;
        $('#debug2LogState').html(state.toString());
        api.playerSetLogDebug2(state);
      }

      function toggleUseHardwareVideoDecoder() {
        var state = api.getUseHardwareVideoDecoder();
        state = !state;
        $('#hwVideoDecoderState').html(state.toString());
        api.playerSetUseHardwareVideoDecoder(state);
      }

      function toggleFlushLiveURLCache() {
        var state = api.getflushLiveURLCache();
        state = !state;
        $('#flushLiveURLState').html(state.toString());
        api.playerSetflushLiveURLCache(state);
      }

      function toggleJSURLStream() {
        var state = api.getJSURLStream();
        state = !state;
        $('#JSURLStreamState').html(state.toString());
        api.playerSetJSURLStream(state);
      }

      function toggleCapLeveltoStage() {
        var state = api.getCapLeveltoStage();
        state = !state;
        $('#capLeveltoStageState').html(state.toString());
        api.playerCapLeveltoStage(state);
      }

      // Create a single global callback function and pass it's name
      // to the SWF with the name `callback` in the FlashVars parameter.
      window.flashlsCallback = function(eventName, args) {
        flashlsEvents[eventName].apply(null, args);
      };

      var flashlsEvents = {
         ready: function(flashTime) {
          flashPingDate = flashTime;
          jsPingDate = new Date();
          appendLog("onHLSReady()");
          api = new flashlsAPI(getFlashMovieObject("moviename"));
          $('#debugLogState').html(api.getLogDebug().toString());
          $('#debug2LogState').html(api.getLogDebug2().toString());
          $('#flushLiveURLState').html(api.getflushLiveURLCache().toString());
          $('#JSURLStreamState').html(api.getJSURLStream().toString());
          $('#capLeveltoStageState').html(api.getCapLeveltoStage().toString());
          $('#hwVideoDecoderState').html(api.getUseHardwareVideoDecoder().toString());
          loadStream($('#streamURL').val());
        },
        videoSize: function(width, height) {
          var event = {time : new Date() - jsLoadDate, type : "resize", name : width + 'x' + height};
          events.video.push(event);
          // $("#currentResolution").html("video resolution:" + width + 'x' + height);
          var ratio = width / height;
          if (width > $('#video').innerWidth()-30) {
              width = $('#video').innerWidth()-30;
              height = Math.round(width / ratio);
          }
         api.flashObject.width = width;
         api.flashObject.height = height;
         var canvas = $('#buffered_c')[0];
         canvas.width = width;
        },
        complete: function() {
          appendLog("onComplete(), playback completed");
        },
        error: function(code, url, message) {
          appendLog("onError():error code:"+ code + " url:" + url + " message:" + message);
          $('#showError').show().html( url +' 無法撥放！'+ message);
        },
        manifest: function(duration, levels_, loadmetrics) {
          appendLog("manifest loaded, playlist duration:"+ duration.toFixed(2));
          $('#currentDuration').html(duration.toFixed(2));
          showCanvas();
          levels = levels_;
          updateLevelInfo();
          api.play(-1);
          api.volume(10);
          var event = {
                      type : "manifest",
                      name : "",
                      latency : loadmetrics.loading_begin_time - loadmetrics.loading_request_time,
                      load : loadmetrics.loading_end_time - loadmetrics.loading_begin_time,
                      duration : loadmetrics.loading_end_time - loadmetrics.loading_begin_time,
                      bw : Math.round(loadmetrics.bandwidth/1000)
                    };
          event.time = loadmetrics.loading_request_time - flashLoadDate;
          events.load.push(event);
          refreshCanvas();
        },
        audioLevelLoaded: function(loadmetrics) {
          var event = {
                      type : "level audio",
                      id : loadmetrics.level,
                      start : loadmetrics.id,
                      end : loadmetrics.id2,
                      latency : loadmetrics.loading_begin_time - loadmetrics.loading_request_time,
                      load : loadmetrics.loading_end_time - loadmetrics.loading_begin_time,
                      duration : loadmetrics.loading_end_time - loadmetrics.loading_begin_time,
                      bw : Math.round(loadmetrics.bandwidth/1000)
                    };
          event.time = loadmetrics.loading_request_time - flashLoadDate;
          events.load.push(event);
          refreshCanvas();
        },
        levelLoaded: function(loadmetrics) {
          var event = {
                      type : "level",
                      id : loadmetrics.level,
                      start : loadmetrics.id,
                      end : loadmetrics.id2,
                      latency : loadmetrics.loading_begin_time - loadmetrics.loading_request_time,
                      load : loadmetrics.loading_end_time - loadmetrics.loading_begin_time,
                      duration : loadmetrics.loading_end_time - loadmetrics.loading_begin_time,
                      bw : Math.round(loadmetrics.bandwidth/1000)
                    };
          event.time = loadmetrics.loading_request_time - flashLoadDate;
          events.load.push(event);
          refreshCanvas();
        },
        fragmentLoaded: function(loadmetrics) {
          var event = {
                      id : loadmetrics.level,
                      id2 : loadmetrics.id,
                      latency : loadmetrics.loading_begin_time - loadmetrics.loading_request_time,
                      load : loadmetrics.loading_end_time - loadmetrics.loading_begin_time,
                      demux : loadmetrics.parsing_end_time - loadmetrics.loading_end_time,
                      duration : loadmetrics.parsing_end_time - loadmetrics.loading_begin_time,
                      bw : Math.round(loadmetrics.bandwidth/1000)
                    };
          event.time = loadmetrics.loading_request_time - flashLoadDate;
          if(loadmetrics.type == 4) {
            event.type =  'fragment audio'
          } else {
            event.type =  'fragment main'
          }
          events.load.push(event);
          updateLevelInfo();
          document.getElementById("HlsStats").innerHTML = JSON.stringify(sortObject(api.getStats()),null,"\t");
          refreshCanvas();
        },
        fragmentPlaying: function(playmetrics) {

          var currentTime = new Date() - jsLoadDate;
          if(lastFragPlayingIdx) {
            // ensure that last frag duration is not exceeding
            var lastEvent = events.video[lastFragPlayingIdx];
            if(lastEvent.time+lastEvent.duration > currentTime)
              lastEvent.duration = currentTime - lastEvent.time;
          }
          var event = {time : currentTime, type : "playing frag", name : playmetrics.seqnum + '@' + playmetrics.level, duration : playmetrics.duration*1000};
          events.video.push(event);
          lastFragPlayingIdx = events.video.length-1;
          updateLevelInfo();
          document.getElementById("HlsStats").innerHTML = JSON.stringify(sortObject(api.getStats()),null,"\t");
          refreshCanvas();
        },
        position: function(timemetrics) {
          $('#currentPosition').html(timemetrics.position.toFixed(2));
          $('#currentDuration').html(timemetrics.duration.toFixed(2));
          $('#bufferLength').html(timemetrics.buffer.toFixed(2));
          $('#backBufferLength').html(timemetrics.backbuffer.toFixed(2));
          $('#sliding').html(timemetrics.live_sliding_main.toFixed(2));
          $('#watched').html(timemetrics.watched.toFixed(2));
          var event = { time : new Date() - jsLoadDate, buffer : Math.round(1000*timemetrics.buffer), pos: Math.round(1000*timemetrics.position)};
          var bufEvents = events.buffer, bufEventLen = bufEvents.length;
          if(bufEventLen > 1) {
            var event0 = bufEvents[bufEventLen-2],event1 = bufEvents[bufEventLen-1];
            var slopeBuf0 = (event0.buffer - event1.buffer)/(event0.time-event1.time);
            var slopeBuf1 = (event1.buffer - event.buffer)/(event1.time-event.time);

            var slopePos0 = (event0.pos - event1.pos)/(event0.time-event1.time);
            var slopePos1 = (event1.pos - event.pos)/(event1.time-event.time);
            // compute slopes. if less than 30% difference, remove event1
            if((slopeBuf0 === slopeBuf1 || Math.abs(slopeBuf0/slopeBuf1 -1) <= 0.3) &&
               (slopePos0 === slopePos1 || Math.abs(slopePos0/slopePos1 -1) <= 0.3))
             {
              bufEvents.pop();
            }
          }
          events.buffer.push(event);
          updateBufferCanvas(timemetrics.position,timemetrics.duration,timemetrics.buffer, timemetrics.backbuffer);
          refreshCanvas();
        },
        state: function(newState) {
          var event = {time : new Date() - jsLoadDate, type : newState.toLowerCase(), name : ''};
          events.video.push(event);
          $('#currentState').html(newState);
        },
        seekState: function(newState) {
          var currentTime = new Date() - jsLoadDate;
          var event = {time : currentTime, type : newState.toLowerCase(), name : ''};
          events.video.push(event);

          if(event.type === 'seeking') {
            lastSeekingIdx = events.video.length-1;
            if(lastFragPlayingIdx) {
              // ensure that last frag duration is not exceeding
              var lastEvent = events.video[lastFragPlayingIdx];
              if(lastEvent.time+lastEvent.duration > currentTime)
                lastEvent.duration = currentTime - lastEvent.time;
            }
          }
          if(event.type === 'seeked') {
            events.video[lastSeekingIdx].duration = event.time - events.video[lastSeekingIdx].time;
          }
        },
        switch: function(newLevel) {
          var event = {time : new Date() - jsLoadDate, type : "levelSwitch", name : newLevel.toFixed()};
          events.video.push(event);
        },
        audioTracksListChange: function(trackList) {
          var d = document.getElementById('audioControl');
          var html = '';
          appendLog("new track list");
          for (var t in trackList) {
            appendLog("    " + trackList[t].title + " [" + trackList[t].id + "]");
            html += '<button class="btn btn-sm btn-info"  tabindex=2 onkeypress="api.setAudioTrack(' +t+ ')" onclick="api.setAudioTrack(' +t+ ')">' + trackList[t].title + '</button>';
          }
          d.innerHTML = html;
        },
        audioTrackChange: function(trackId) {
          var event = {time : new Date() - jsLoadDate, type : "audioTrackChange", name : trackId.toFixed()};
          events.video.push(event);
        },
        id3Updated: function(ID3Data) {
          var event = {time : new Date() - jsLoadDate, type : "ID3Data", id3data: atob(ID3Data)};
          events.video.push(event);
        },
        fpsDrop : function(level) {
          var event = {time : new Date() - jsLoadDate, type : "fpsDrop", name : level.toFixed()};
          events.video.push(event);
        },
        fpsDropLevelCapping : function(level) {
          var event = {time : new Date() - jsLoadDate, type : "fpsDropLevelCapping", name : level.toFixed()};
          events.video.push(event);
        },
        fpsDropSmoothLevelSwitch : function() {
          var event = {time : new Date() - jsLoadDate, type : "fpsDropSmoothLevelSwitch", name : ''};
          events.video.push(event);
        },
        requestPlaylist: JSLoaderPlaylist.requestPlaylist.bind(JSLoaderPlaylist),
        abortPlaylist: JSLoaderPlaylist.abortPlaylist.bind(JSLoaderPlaylist),
        requestFragment: JSLoaderFragment.requestFragment.bind(JSLoaderFragment),
        abortFragment: JSLoaderFragment.abortFragment.bind(JSLoaderFragment)
      };


      function sortObject(obj) {
          if(typeof obj !== 'object')
              return obj
          var temp = {};
          var keys = [];
          for(var key in obj)
              keys.push(key);
          keys.sort();
          for(var index in keys)
              temp[keys[index]] = sortObject(obj[keys[index]]);
          return temp;
      }

      function showCanvas()  {
          showMetrics();
          document.getElementById('buffered_c').style.display="block";
      }

      function hideCanvas()  {
          hideMetrics();
          document.getElementById('buffered_c').style.display="none";
      }

      function getMetrics() {
        var json = JSON.stringify(events);
        var jsonpacked = jsonpack.pack(json);
        console.log("packing JSON from " + json.length + " to " + jsonpacked.length + " bytes");
        return btoa(jsonpacked);
      }

      function copyMetricsToClipBoard() {
        copyTextToClipboard(getMetrics());
      }

      function copyTextToClipboard(text) {
        var textArea = document.createElement("textarea");
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();

        try {
          var successful = document.execCommand('copy');
          var msg = successful ? 'successful' : 'unsuccessful';
          console.log('Copying text command was ' + msg);
        } catch (err) {
          console.log('Oops, unable to copy');
        }
        document.body.removeChild(textArea);
      }


      function goToMetrics() {
        var url = document.URL;
        url = url.substr(0,url.lastIndexOf("/")+1) + 'metrics.html';
        console.log(url);
        window.open(url,'_blank');
      }

      function goToMetricsPermaLink() {
        var url = document.URL;
        var b64 = getMetrics();
        url = url.substr(0,url.lastIndexOf("/")+1) + 'metrics.html?data=' + b64;
        console.log(url);
        window.open(url,'_blank');
      }

      function buffered_seek(event) {
        var canvas = document.getElementById('buffered_c');
        var position = (event.clientX - canvas.offsetLeft) / canvas.width * api.getDuration();
        api.seek(position);
      }

      function updateBufferCanvas(position,duration,buffer, backbuffer) {
        var canvas = document.getElementById('buffered_c');
        var ctx = canvas.getContext('2d');
        ctx.fillStyle = "black";
        ctx.fillRect(0,0,canvas.width,canvas.height);
        ctx.fillStyle = "gray";
        var start = (position-backbuffer)/duration * canvas.width;
        var end = (position+buffer)/duration * canvas.width;
        ctx.fillRect(start, 3, Math.max(2, end-start), 10);
        ctx.fillStyle = "blue";
        var x = position / duration * canvas.width;
        ctx.fillRect(x, 0, 2, 15);
      }

      function updateLevelInfo() {
        var button_template = '<button type="button" class="btn btn-sm ';
        var button_enabled  = 'btn-primary" ';
        var button_disabled = 'btn-success" ';

        var autoLevel = api.getAutoLevel();
        var autoLevelCapping = api.getAutoLevelCapping();
        var currentLevel = api.getCurrentLevel();
        var nextLevel = api.getNextLevel();
        var loadLevel = api.getLoadLevel();

        var html1 = button_template;
        if(autoLevel) {
          html1 += button_enabled;
        } else {
          html1 += button_disabled;
        }
        html1 += 'onclick="api.setCurrentLevel(-1);updateLevelInfo()">auto</button>';


        var html2 = button_template;
        if(autoLevel) {
          html2 += button_enabled;
        } else {
          html2 += button_disabled;
        }
        html2 += 'onclick="api.setLoadLevel(-1);updateLevelInfo()">auto</button>';

        var html3 = button_template;
        if(autoLevelCapping === -1) {
          html3 += button_enabled;
        } else {
          html3 += button_disabled;
        }
        html3 += 'onclick="api.playerSetAutoLevelCapping(-1);updateLevelInfo()">none</button>';

        var html4 = button_template;
        if(autoLevel) {
          html4 += button_enabled;
        } else {
          html4 += button_disabled;
        }
        html4 += 'onclick="api.setNextLevel(-1);updateLevelInfo()">auto</button>';

        for (var i=0; i < levels.length; i++) {
          html1 += button_template;
          if(currentLevel === i) {
            html1 += button_enabled;
          } else {
            html1 += button_disabled;
          }
          var levelName = i, label = level2label(i);
          if(label) {
            levelName += '(' + level2label(i) + ')';
          }
          html1 += 'onclick="api.setCurrentLevel(' + i + ');updateLevelInfo()">' + levelName + '</button>';


          html2 += button_template;
          if(loadLevel === i) {
            html2 += button_enabled;
          } else {
            html2 += button_disabled;
          }
          html2 += 'onclick="api.setLoadLevel(' + i + ');updateLevelInfo()">' + levelName + '</button>';

          html3 += button_template;
          if(autoLevelCapping === i) {
            html3 += button_enabled;
          } else {
            html3 += button_disabled;
          }
          html3 += 'onclick="api.playerSetAutoLevelCapping(' + i + ');updateLevelInfo()">' + levelName + '</button>';

          html4 += button_template;
          if(nextLevel === i) {
            html4 += button_enabled;
          } else {
            html4 += button_disabled;
          }
          html4 += 'onclick="api.setNextLevel(' + i + ');updateLevelInfo()">' + levelName + '</button>';
        }
        // $("#currentLevelControl").html(html1);
        // $("#loadLevelControl").html(html2);
        // $("#levelCappingControl").html(html3);
        // $("#nextLevelControl").html(html4);
      }

      function level2label(index) {
        if(levels && levels.length-1 >= index) {
          var level = levels[index];
          if (level.name) {
              return level.name;
          } else {
              if (level.height) {
                  return(level.height + 'p / ' + Math.round(level.bitrate / 1024) + 'kb');
              } else {
                  if(level.bitrate) {
                    return(Math.round(level.bitrate / 1024) + 'kb');
                  } else {
                    return null;
                  }
              }
          }
        }
      }
    </script>
</div>