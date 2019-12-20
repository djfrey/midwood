<template>
	<nav class="navbar navbar-expand-lg">
		<span class="d-none d-xl-block navbar-brand">			
			<div><i class="fa-fw fad fa-unlock"></i> Advisorlink Administration</div>
		</span>
	  	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
	  	</button>
	  	<div class="collapse navbar-collapse navbar-dark">
			<ul class="navbar-nav">
				<li><a href="files/" :class="{'router-link-active': $route.name == 'files'}" class="nav-item nav-link"><i class="fas fa-file-alt"></i> Manage files</a></li>
				<li><a href="users/" :class="{'router-link-active': $route.name == 'users'}" class="nav-item nav-link"><i class="fas fa-user"></i> Manage accounts</a></li>
				<li><a href="staff/" :class="{'router-link-active': $route.name == 'staff'}" class="nav-item nav-link"><i class="fas fa-clipboard-user"></i> Manage staff</a></li>
			</ul>
	  	</div>
		<span class="navbar-text">	
			<a class="btn btn-sm btn-primary mr-2" href="/advisorlink"><i class="fad fa-door-open"></i> Employee portal</a>	
			<span><i class="fas fa-user"></i> You are logged in as {{user.name}}</span> 
			<button type="button" v-on:click="logout()" class=" btn-sm ml-2 btn btn-primary"><i class="fad fa-sign-out-alt"></i> Log out</button>
		</span>				
	</nav>	
</template>

<script>
module.exports = {
	props: ['user'],
	methods: {
		logout: function() {
			var _this = this;
			firebase.auth().signOut().then(function() {    			
				localStorage.removeItem('cookie');                
				_this.$parent.isLoggedIn();					
			}).catch(function(error) {
				_this.$parent.setAlert(true, error.message, 'alert-danger');	
			});			
		},
	}
}
</script>
<style>
.navbar .navbar-brand {
	font-family: "Raleway", sans-serif;
	font-size: 1.3rem;
}

.navbar {
    background-color: #036;
	padding: 0 1rem;
	margin-bottom: 20px;
	font-family: "Raleway", sans-serif;
}

.navbar-text {
    color: #FFF;
	padding: 0;
}

.navbar .navbar-nav>li>a {
    color: #FFF;
}

.navbar-dark .navbar-nav .nav-link {
	color: rgba(255, 255, 255, 0.8);
}

.navbar-dark .navbar-nav .nav-link:hover {
	color: rgba(255, 255, 255, 1);
}

.navbar .navbar-nav li a.nav-item.router-link-active, 
.navbar .navbar-nav li a.nav-item.router-link-active:focus, 
.navbar .navbar-nav li a.nav-item.router-link-active:hover {
    color: #036;
    background-color: #FFF;
}

.navbar .navbar-nav>li>a:hover {
    color: #9CF;
    background-color: #369;
}

.navbar-expand-lg .navbar-nav .nav-link {
	padding: 1rem;	
}
</style>