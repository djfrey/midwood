<template>
    <div class="col row">
        <div class="mt-3 mb-3 col-sm-12 col-lg-4" v-for="d in data">
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
                    icon: 'fas fa-users-cog',
                    color: {background: 'transparent', text: '#000'},
                    header: 'Advisor Development',
                    img: './site/assets/training/development.jpg'
                },
                {
                    icon: 'fas fa-hands-helping',
                    color: {background: 'transparent', text: '#000'},
                    header: 'Leadership & Coaching',
                    img: './site/assets/training/coaching.jpg'
                },
                {
                    icon: 'fas fa-comments-alt',
                    color: {background: 'transparent', text: '#000'},
                    header: 'Advisor Forums',
                    img: './site/assets/training/forum.jpg'
                }
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
