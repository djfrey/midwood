<template>
    <div>
        <div v-if="!ie11" class="flip-box">
            <div class="flip-box-inner">            
                <div class="flip-box-front" :style="{color: data.color.front.text, backgroundColor: data.color.front.background}">                
                    <span v-if="data.icon" :class="{'fa-stack fa-2x': data.icon.stack}">
                        <i v-if="data.icon.stack" class="fas fa-circle fa-stack-2x fa-inverse"></i>
                        <i :style="[data.icon.color ? {color: data.icon.color} : {color: data.color.front.background}]" :class="[{'fa-stack-1x': data.icon.stack, 'fa-4x': !data.icon.stack}, data.icon.class]"></i>
                    </span>                
                    <h2 class="pt-3">{{data.front}}</h2>
                </div>
                <div class="flip-box-back" :style="{color: data.color.back.text, backgroundColor: data.color.back.background}">    
                    <p>{{data.back}}</p>
                </div>
            </div>        
        </div>
        <div v-else class="ie11-flip-box">     
            <div class="ie11-flip-box-inner">       
                <div class="flip-box-front" :style="{color: data.color.front.text, backgroundColor: data.color.front.background}">                
                    <span v-if="data.icon" :class="{'fa-stack fa-2x': data.icon.stack}">
                        <i v-if="data.icon.stack" class="fas fa-circle fa-stack-2x fa-inverse"></i>
                        <i :style="[data.icon.color ? {color: data.icon.color} : {color: data.color.front.background}]" :class="[{'fa-stack-1x': data.icon.stack, 'fa-4x': !data.icon.stack}, data.icon.class]"></i>
                    </span>                
                    <h2 class="pt-3">{{data.front}}</h2>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
module.exports = {
    props: ['data']
}
</script>
<style scoped>
/* The flip box container - set the width and height to whatever you want. We have added the border property to demonstrate that the flip itself goes out of the box on hover (remove perspective if you don't want the 3D effect */
.flip-box, .ie11-flip-box {
    background-color: transparent;
    width: 100%;
    height: 285px;    
    /*perspective: 1000px; /* Remove this if you don't want the 3D effect */
}

.ie11-flip-box-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
}


/* This container is needed to position the front and back side */
.flip-box-inner {
    position: relative;
    width: 100%;
    height: 100%;
    text-align: center;
    transition: transform 0.3s;
    transform-style: preserve-3d;
}

/* Do an horizontal flip when you move the mouse over the flip box container */
.flip-box:hover .flip-box-inner {
    transform: rotateY(180deg);
}

/* Position the front and back side */
.flip-box-front, .flip-box-back {
    background-color: #DDD;
    color: #333;
    border-radius: 4px;
    position: absolute;
    padding: 20px;
    width: 100%;
    height: 100%;
    backface-visibility: hidden;
}

.flip-box-front {
    padding-top: 40px;
}

/* Style the back side */
.flip-box-back {
    transform: rotateY(180deg);
}

.flip-box-back p {
    font-size: 1.1rem;
}
</style>