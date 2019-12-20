<template>
    <div class="content-box" :style="{color: data.color.text || '#000000', backgroundColor: data.color.background || 'transparent'}">
        <div class="hover">
            <span v-if="data.icon" class="fa-stack fa-2x">
                <i class="fas fa-circle fa-stack-2x"></i>
                <i :class="data.icon" :style="{color: data.color.icon || '#FFFFFF'}" class="fa-stack-1x"></i>
            </span>                
            <div>
                <h2>{{data.header}}</h2>
            </div>
        </div>
        <div>
           <p v-if="data.body">{{data.body}}</p>
           <img class="img-fluid" :src="data.img" v-if="data.img"></img>
        </div>
        
    </div>
</template>
<script>
module.exports = {
    props: ['data']
}
</script>
<style scoped>
.content-box {
    text-align: center;    
    min-height: 240px;
}
.content-box .fa-circle {
    color: #333;
}

.content-box .fa-stack {    
    top: -15px;
}

.content-box p {
    padding: 0px 20px;
}

.content-box .hover, .content-box .hover .fa-circle {
    transition: all .3s;
}

.content-box .hover:hover, .content-box .hover:hover .fa-circle {
    color: #59b9e0;
}

.content-box h2 {
    /*text-transform: uppercase;*/
}
</style>