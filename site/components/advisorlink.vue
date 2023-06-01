<template>
<div class="container-fluid">         
    <div v-if="!loggedIn">
        <login></login>
    </div>
    <div v-if="loggedIn">
        <div v-if="!loading">
            <heading></heading>                              
            <user-portal v-if="role == 'user' || fromPortal" :user="user"></user-portal>
            <employee-portal v-if="role != 'user'" :role="role" :user="user"></employee-portal>
            <foot></foot>
        </div>
    </div>
</div>
</template>
<script>
module.exports = {   
    components: {
        heading: httpVueLoader('./common/heading.vue'),
        navigation: httpVueLoader('./common/navigation.vue'),              
        login: httpVueLoader('./advisorlink/login.vue'),          
        userPortal:  httpVueLoader('./advisorlink/userPortal.vue'),
        employeePortal:  httpVueLoader('./advisorlink/employeePortal.vue'),
        foot: httpVueLoader('./common/foot.vue'),
    },
    data: function() {
        return {      
            loggedIn: false, 
            loading: false,                             
            user: {message: '', iam: [], sales: [], access: []},
            role: '', 
            userCopy: {},
            roleCopy: '',
            fromPortal: false,
            docSrc: '#'           
        }
    },
    methods: {
        isLoggedIn: function() {			            
            var l = JSON.parse(localStorage.getItem('cookie')) || {};            
			if (l.key == 'mw-advisorlink') {                                         
                this.user = l.user;
                this.$root.user = {userName: this.user.userName};
                this.role = l.role;
                this.loggedIn = true;	                
			} else {
                this.user = {};
                this.$root.user = {};
                this.role = '';
				this.loggedIn = false;
            }                 
        }
    },
    mounted: function() {
        this.isLoggedIn();                
        var _this = this;        
        eventBus.$on('logout', function() {            
            _this.user = {};
            _this.$root.user = {};
            _this.role = '';
			_this.loggedIn = false;
        });     
    }

}
Vue.filter('userHighlight', function(item, query) {
	if (item == null) {
		return;
    }
    if (query.trim().length < 3) {
        return item;
    }
    var invalid = /[°"§%()\[\]{}=\\?´`'#<>|,;.:+_]+/g;	
	var repl = query.trim().replace(invalid, "");
    var check = new RegExp(repl, "ig");
	return item.toString().replace(check, function(matchedText,a,b){
		return ('<span class="bg-warning text-white">' + matchedText + '</span>');
	});
});
</script>
<style>
.row {
    background-color: #FFF;
    padding: 30px auto;
    font-family: 'Raleway', sans-serif;
}

.row h1 {
    margin: 30px auto;
}

</style>