<template>
    <div class="row">
        <div class="col-12 text-center">
            <h1>An important message Alan Blank, President of Midwood Financial</h1>
        </div>
        <div class="col-12">
            <video ref="videoPlayer" class="video-js"></video>
        </div>
    </div>
</template>
<script>
//import videojs from 'video.js';
module.exports = {	    
    props: {
        options: {
            type: Object,
            default: function() {
                return {};
            }
        }
    },
    data: function() {
        return {
            player: null
        }
    },
    mounted: function() {
        this.player = videojs(this.$refs.videoPlayer, this.options, function onPlayerReady() {
            
        });        
    },
    beforeDestroy: function() {
        if (this.player) {
            this.player.dispose();
        }
    }
}
</script>
<style>
.vjs_video_3-dimensions {
  width: 100%;
  max-height: 704px;  
}

.vjs_video_3-dimensions {
    width: inherit !important;
}

.video-js .vjs-big-play-button {
  top: 50%;
  left: 50%;
}
</style>

