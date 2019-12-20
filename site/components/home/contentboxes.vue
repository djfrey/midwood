<template>
    <div class="col row">
        <div class="col-sm-12 col-md-4" v-for="d in data">
           <contentbox :data="d"></contentbox>
        </div>
    </div>
</template>
<script>
module.exports = {
    components: {
        contentbox: httpVueLoader('../common/contentbox.vue')
    },
    data: function() {
        return {
            data: [
                {
                    icon: 'fas fa-road',
                    color: {background: 'rgb(33, 51, 73)', text: 'rgb(255,255,255)'},
                    header: 'PLAN',
                    body: 'Growth strategies and financial planning support'
                },
                {                    
                    icon: 'fas fa-shield',
                    color: {background: 'rgb(43, 80, 124)', text: 'rgb(255,255,255)'},
                    header: 'PROTECT',
                    body: 'Annuity and Wealth Transfer Solutions'
                },
                {
                    icon: 'fas fa-leaf',
                    color: {background: 'rgb(79, 122, 175)', text: 'rgb(255,255,255)'},
                    header: 'PROVIDE',
                    body: 'Income Planning Strategies'
                },                           
            ]
        }
    }
}
</script>
<style scoped>
.row {
    padding: 30px 30px 50px 30px;
    background-color: transparent;
}
</style>
