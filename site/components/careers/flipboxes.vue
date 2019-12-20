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
                    icon: {stack: false, class: 'fas fa-medal', color: 'rgb(33, 51, 73)'},
                    color: {back: {background: 'rgb(33, 51, 73)', text: 'rgb(255,255,255)'}, front: {}},
                    front: 'We focus on quality',
                    back: 'From the products we support to the team that we engage, Midwood focuses on best-in-class solutions for our partners.'
                },
                {                    
                    icon: {stack: false, class: 'fas fa-road', color: 'rgb(43, 80, 124)'},
                    color: {back: {background: 'rgb(43, 80, 124)', text: 'rgb(255,255,255)'}, front: {}},
                    front: 'We honor our history',
                    back: 'Founded in 2000, Midwood has stood the test of time, adjusting to changing in market trends to help our partners stay ahead of the curve in an evolving financial landscape.'
                },
                {
                    icon: {stack: false, class: 'fas fa-users', color: 'rgb(79, 122, 175)'},
                    color: {back: {background: 'rgb(79, 122, 175)', text: 'rgb(255,255,255)'}, front: {}},
                    front: 'We value our people',
                    back: 'Over half our staff has been with us for more than ten years and we treat them like family. Taking great care of our employees is what reinforces our commitment to relationships and is evident in our partnerships.'
                },
                {
                    icon: {stack: false, class: 'fas fa-comment-alt-check', color: 'rgb(96, 149, 214)'},
                    color: {back: {background: 'rgb(96, 149, 214)', text: 'rgb(255,255,255)'}, front: {}},
                    front: 'We reward success',
                    back: 'We provide the resources, tools and support our employees need to do their best and succeed in their careers.'
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