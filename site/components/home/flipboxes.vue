<template>
    <div class="row">
        <div class="col-lg-3 col-md-6 col-12 mt-3" v-for="d in data">
            <flipbox :data="d"></flipbox>
        </div>
    </div>
</template>
<script>
module.exports = {
    components: {
        flipbox: httpVueLoader('../common/flipbox.vue')
    },
    data: function() {
        return {
            data: [
                {
                    icon: {stack: true, class: 'fas fa-map-marker-check'},
                    color: {front: {background: 'rgb(33, 51, 73)', text: 'rgb(255,255,255)'}, back: {}},
                    front: 'National Wholesaling Footprint',
                    back: 'A national team of Regional Sales Directors provide training, product support, and sales solutions from multiple product manufacturers.'
                },
                {                    
                    icon: {stack: true, class: 'fas fa-file-certificate'},
                    color: {front: {background: 'rgb(43, 80, 124)', text: 'rgb(255,255,255)'}, back: {}},
                    front: 'Seasoned and Licensed Sales Team',
                    back: 'Our entire sales and management staff is Series 7, Life and Variable Annuity licensed.'
                },
                {
                    icon: {stack: true, class: 'fas fa-handshake'},
                    color: {front: {background: 'rgb(79, 122, 175)', text: 'rgb(255,255,255)'}, back: {}},
                    front: 'Account Dedicated Management',
                    back: 'Dedicated Internal Account Managers deliver comprehensive support for all of Midwood’s product providers. With only one call, all needs are met.'
                },
                {
                    icon: {stack: true, class: 'fas fa-diploma'},
                    color: {front: {background: 'rgb(96, 149, 214)', text: 'rgb(255,255,255)'}, back: {}},
                    front: 'Industry Recognized Training',
                    back: 'Our training and curriculum is developed and created in-house, and is customized for the proprietary needs of our partner firms.'
                }                
            ]
        }
    }
}
</script>
<style scoped>
.row {
    padding: 30px 30px 50px 30px;
}
</style>
