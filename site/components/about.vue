<template>
<div class="container-fluid">
    <heading></heading>
    <navigation></navigation>
    
    <div class="row intro">
        <div class="col">
            <h1>Our Story</h1>
            <p>Founded in 2000, Midwood Financial Services is the nations largest independently owned distributor of financial products and services to more than
            250 banks in the United States. The company's strength comes from its focus to continuously match it's clients with the best product solutions and 
            distribution strategies available in the financial industry.</p>

            <p>Midwood's achievements are driven by its independence from any one financial product manufacturer. This gives Midwood the freedom to review, 
            analyze and evaluate the widest range of products and services – selecting only those that meet the most stringent criteria for consumer oriented 
            features, benefits and costs.</p>
        </div>
    </div>
    <div class="row p-3">
        <div class="col text-center">
            <h5>Midwood's team of seasoned professionals work effectively to meet your needs.</h5>
            <h5>This effort begins with a world class executive-level team that has a industry-proven track record of success.</h5>
        </div>
    </div>
    <div class="row p-3 border-top">
        <div class="col-12">
            <h2 id="leadership">Leadership</h2>
        </div>
        <leadership></leadership>
    </div>
    <div class="row p-3 border-top">
        <div class="col-12">
            <h2 id="management">Management</h2>
        </div>
        <management></management>
    </div>
    <div class="row p-3 border-top">
        <div class="col-12">
            <h2 id="training">Training</h2>
        </div>
        <training></training>
    </div>
    <div class="row p-3 border-top">
        <div class="col-12">
            <h2 id="sales">Sales</h2>
        </div>
        <sales></sales>
    </div>
    <div class="row p-3 border-top">
        <div class="col-12">
            <h2 id="administration">Administration</h2>
        </div>
        <administration></administration>
    </div>
    <employee-bio :data="employee"></employee-bio>
    <foot></foot>
</div>
</template>
<script>
module.exports = {   
    components: {
        heading: httpVueLoader('./common/heading.vue'),
        navigation: httpVueLoader('./common/navigation.vue'),
        leadership: httpVueLoader('./about/leadership.vue'),
        management: httpVueLoader('./about/management.vue'),
        training: httpVueLoader('./about/training.vue'),
        sales: httpVueLoader('./about/sales.vue'),
        administration: httpVueLoader('./about/administration.vue'),
        employeeBio: httpVueLoader('./about/bio.vue'),
        foot: httpVueLoader('./common/foot.vue')
    },
    data: function() {
        return {
            employee: {}
        }
    },
    methods: {
        scrollTo: function (hashtag) {
            if (typeof hashtag != 'undefined') {
                var element = document.getElementById(hashtag);                
                if (element != null) {
                    setTimeout(function() {  element.scrollIntoView({behavior: 'smooth', block: 'start', inline: 'nearest'}); }, 100);
                }
            } else {
                this.$router.go('/about');                
            }
        }
    },
    watch: {
        '$route.params.anchor': function(anchor) {
            this.scrollTo(anchor);
        }
    },
    mounted: function() {        
        if (this.$route.params.anchor > '') {
            this.scrollTo(this.$route.params.anchor);            
        }
    }

}
</script>
<style scoped>
.row {
    background-color: #FFF;
    padding: 30px auto;
    font-family: 'Raleway', sans-serif;
}

.row h1 {
    margin: 30px auto;
}

.row.intro p {
    margin: 20px 0px;
    padding: 0px;
}


.intro {
    padding: 30px;
    color: #FFF;
    background-image: url('/site/assets/AdobeStock_3634255.jpg');
    background-position: center center;
    background-repeat: no-repeat;
    -webkit-background-size:cover;
    -moz-background-size:cover;
    -o-background-size:cover;
    background-size:cover;
    text-shadow: rgba(0, 0, 0, 0.8) 1px 1px 2px;
}

a, .a {
    cursor: pointer;
    color: #5a8cc9;
}

a, .a {
    transition: all 0.2s;
}

.social {
    font-size: 1.1rem;
}

.social a {
    padding-left: 0.2rem;
}

.social a:hover, .a:hover {
    opacity: 0.8;
}

</style>