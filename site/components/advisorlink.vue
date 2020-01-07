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
            fsAdvisorlink: [], //The firestore advisorlink collection
            fsUsers: [],
            fsStaff: [],
            docs: [], //The nested array of firestore objects
            users: [],
            staff: [],
            nodes: [],            
            user: {message: '', iam: [], sales: [], access: []},
            role: '', 
            userCopy: {},
            roleCopy: '',
            fromPortal: false,
            docSrc: '#'           
        }
    },
    methods: {
        getData: function() {            
            this.loading = true;
            var _this = this;            
            var docsPromise = this.$bind('fsAdvisorlink',  db.collection('advisorlink').orderBy('sort', 'asc'), {maxRefDepth: 1});            
            var staffPromise = this.$bind('fsStaff',  db.collection('staff').orderBy('name', 'asc'), {maxRefDepth: 1});    
            Promise.all([docsPromise, staffPromise]).then(function(response) {	                
                //Get the firestore collection, create the nested array of objects			
				var d = response[0] || [];														
				for (i = 0; i < d.length; i++) {
                    let x = d[i];
                    //The 'some' variable is set when some of the node's children are assigned
                    x.some = false;					
                    x.children = d.filter(function(y) {
                        if (y.parent > null) {
                            return y.parent.id == x.id;
                        }
					});						
					_this.docs.push(x);
				}			
				//Set the global root element, the array element that will contain everything else 
				_this.root = _this.docs.filter(function(n) {
					return n.id == 'root';
				});			
				//Set the root element 
				_this.rootNode = _this.root[0]; //'root' is the array containing all of our data, use it to recursively find the current folder based on the path								
                _this.nodes = _this.rootNode.children;  //The children of the current folder are displayed	

                //Get staff
                let s = response[1] || [];														
				for (k = 0; k < s.length; k++) {
                    let b = s[k];					                    
					_this.staff.push(b);
                }                
				_this.loading = false;
			});
		},	
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
        this.getData();	
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