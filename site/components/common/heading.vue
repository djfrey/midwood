<template>
    <div>
        <div class="top-bar row">
            <div class="col-md-6 col-12">
                <div class="text-md-left text-center">
                    <i class="fas fa-phone-alt"></i> 1-866-959-1010
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="text-md-right text-center">
                    <a href="https://linkedin.com/company/midwood-financial-services" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                    <a href="mailto:info@midwood.com" target="_blank"><i class="fas fa-envelope"></i></a>
                </div>
            </div>
        </div>

        <div class="logo-bar row align-items-center">
            <div class="col-md-7 col-12">
                <div class="text-md-left text-center">
                    <a :href="baseURI"><img src="./assets/images/midwood_logo.png" /></a>
                </div>
            </div>
            <div class="col-md-5 col-12">
                <div class="text-md-right text-center">
                    <button v-if="$root.user.userName > ''" type="button" v-on:click="$root.logOut()" class="pt-2 btn btn-dark"><i class="fad fa-sign-out-alt"></i> Log out of Advisorlink</button>
                    <button v-else type="button" v-on:click="$root.logIn()" class="pt-2 btn btn-dark"><i class="fad fa-sign-in-alt"></i> Investment Professional Login</button>                    
                </div>                            
            </div>
        </div> 
    </div>
</template>
<script>
module.exports = {   
    data: function() {
        return {
            baseURI: baseURI
        }
    },
    methods: {
        isLoggedIn: function() {	            
            var l = JSON.parse(localStorage.getItem('cookie')) || {};            
			if (l.key == 'mw-advisorlink') {                                                     
                this.$root.user = {userName: l.user.userName};
			} else {
                this.$root.user = {};
            }     
        }
    },
    mounted: function() {
        this.isLoggedIn();
    }
   
}
</script>
<style scoped>
.top-bar.row {
    background-color: #5a8cc9;
    color: #FFF;
    padding: 8px 30px;    
}

.top-bar a {
    color: #FFF;
    padding: 0px 5px;
}

.top-bar a:hover {
    opacity: 0.8;
}

.logo-bar.row {
    background-color: #FFF;
    padding: 20px 30px;
}

.logo-bar img {
    max-width: 100%;
    height: auto;
}

.logo-bar button {    
    text-transform: uppercase;
    letter-spacing: 3px;
    font-size: 0.8rem;
}

.logo-bar button:hover, .logo-bar button:active {
    background-color: #59b9e0 !important;
    border-color: #59b9e0 !important;
}
</style>