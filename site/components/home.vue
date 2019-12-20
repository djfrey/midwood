<template>
<div class="container-fluid">
    <heading></heading>
    <navigation></navigation>
    <carousel></carousel>
    <div class="row intro">
        <div class="col text-center">
            <h1>The Midwood Advantage</h1>
            <h3>Midwood's services are comprehensive, time-saving, and bring a competitive advantage to your business.  
            In addition to distributing an array of financially sound products, Midwood offers a wide variety of value-added solutions.</h3>
        </div>
    </div>
    <flipboxes></flipboxes>
    <div class="row success">
        <div class="col-12 text-center">
            <h1>At Midwood, your success is our goal.</h1>
        </div>
        <contentboxes></contentboxes>
    </div>
    <foot></foot>
</div>
</template>
<script>
module.exports = {   
    components: {
        heading: httpVueLoader('./common/heading.vue'),
        navigation: httpVueLoader('./common/navigation.vue'),
        carousel: httpVueLoader('./home/carousel.vue'),
        flipboxes: httpVueLoader('./home/flipboxes.vue'),
        contentboxes: httpVueLoader('./home/contentboxes.vue'),
        foot: httpVueLoader('./common/foot.vue')
    }
}
</script>
<style scoped>
.row {
    background-color: #FFF;
    text-align: center;
    padding: 30px auto;
    font-family: 'Raleway', sans-serif;
}

.row h1 {
    margin: 30px auto;
}

.row.intro p {
    margin: 20px;
}

.success {
    color: #FFF;
    background-image: url('../assets/931.jpg');
    background-position: center top;
    background-repeat: no-repeat;
    -webkit-background-size:cover;
    -moz-background-size:cover;
    -o-background-size:cover;
    background-size:cover;
}

.success h1 {
    text-shadow: rgba(0, 0, 0, 0.8) 1px 1px 2px;
}

</style>