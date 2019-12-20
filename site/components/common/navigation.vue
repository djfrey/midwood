<template>
    <nav class="navbar row navbar-expand-md sticky-top">		
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="mx-auto navbar-nav">
                <li><router-link tag="a" exact class="nav-item nav-link" :to="{name: 'home', params: {}}">Home</router-link></li>
                <li class="dropdown"><router-link tag="a" class="nav-item nav-link dropdown-toggle" id="aboutDropdown" :to="{name: 'about', params: {}}">About us</router-link>
                    <div class="dropdown-menu animate fadeIn" aria-labelledby="navbarDropdown">
                        <router-link tag="a" class="dropdown-item" :to="{name: 'about', params: {anchor: 'leadership'}}">Leadership</router-link>
                        <router-link tag="a" class="dropdown-item" :to="{name: 'about', params: {anchor: 'management'}}">Management</router-link>
                        <router-link tag="a" class="dropdown-item" :to="{name: 'about', params: {anchor: 'training'}}">Training</router-link>
                        <router-link tag="a" class="dropdown-item" :to="{name: 'about', params: {anchor: 'sales'}}">Sales</router-link>
                        <router-link tag="a" class="dropdown-item" :to="{name: 'about', params: {anchor: 'administration'}}">Administration</router-link>
                    </div>
                </li>
                <li><router-link tag="a" class="nav-item nav-link" :to="{name: 'training', params: {}}">Training</router-link></li>
                <li><router-link tag="a" class="nav-item nav-link" :to="{name: 'careers', params: {}}">Careers</router-link></li>
                <li><router-link tag="a" class="nav-item nav-link" :to="{name: 'contact', params: {}}">Contact us</router-link></li>
                <li><router-link tag="a" class="nav-item nav-link" :to="{name: 'advisorlink', params: {}}"><i class="fas fa-link"></i> Advisorlink</router-link></li>
            </ul>
        </div>
    </nav>
</template>
<style scoped>
nav {        
    border-top: 1px solid #DDD;
    font-size: 1.0rem;
    box-shadow: rgba(0,0,0,.118) 0 4px 3px -3px;
}

.row {
    background-color: rgba(255 ,255, 255);
}

.navbar {    
    padding: 0 1rem;   
}


.navbar .navbar-nav>li>a {
    text-transform: uppercase;
	letter-spacing: 3px;
    color: #333;
    transition: all .4s;
}

.navbar .navbar-nav li a.nav-item.router-link-active, 
.navbar .navbar-nav li a.nav-item.router-link-active:focus, 
.navbar .navbar-nav li a.nav-item.router-link-active:hover,
.dropdown-menu a:hover,
.dropdown-menu a:focus {
    color: #369;
    background-color: #EEE;
}

.navbar .navbar-nav>li>a:hover {
   	color: #369;
    background-color: #EEE;
}

.navbar .navbar-text {
	margin-top: 8px;
    margin-bottom: 8px;
    padding: 0;
}

.navbar-expand-md .navbar-nav .nav-link {
	padding: 1rem 1.7rem;	
}

.dropdown:hover>.dropdown-menu {
    display: block;    
}

.dropdown-menu {   
    border-radius: 0;
    border: none;    
    margin: 0;  
    padding: 0;
}

.dropdown-menu a {
    text-transform: uppercase;
    font-size: 0.9rem;	
    padding: 0.6rem 1rem;
    transition: all .4s;
}

@media (min-width: 992px) {
    .animate {
        animation-duration: 0.3s;
        -webkit-animation-duration: 0.3s;
        animation-fill-mode: both;
        -webkit-animation-fill-mode: both;
    }
}

.fadeIn {
    animation-name: fadeIn;
}

@keyframes fadeIn {
    0% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
    0% {
        opacity: 0;
    }
}
</style>