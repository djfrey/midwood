Vue.use(VueRecaptcha);
Vue.use(Vuefire.firestorePlugin);

firebase.initializeApp({
	apiKey: "AIzaSyCZDVnznP9i3h4on9i6YSF6vaeAml8_6rU",
    authDomain: "midwood-89488.firebaseapp.com",
    databaseURL: "https://midwood-89488.firebaseio.com",
    projectId: "midwood-89488",
    storageBucket: "midwood-89488.appspot.com",
    messagingSenderId: "851247924446",
    appId: "1:851247924446:web:7305536e41d2837f"
});

const db = firebase.firestore();
const storage = firebase.storage().ref();
const auth = firebase.auth();
var isIe = false;
if ('ActiveXObject' in window) {				
	isIe = true;
}

const ie11 = isIe;

const baseURI = window.location.protocol+'//'+window.location.host;

const alertDefault = {
	display: false,
	message: '',
	class: 'alert-primary',
	timeout: false
};


//Global components
httpVueLoader.register(Vue, './components/alert.vue');

//Routes and route components
const router = new VueRouter({
	mode: 'history',
	routes: [
		{ 
			name: 'home',
			path: '/',
			components: {
				default: httpVueLoader('./site/components/home.vue')
			}
        },		
        { 
			name: 'about',
			path: '/about/:anchor?', 			
			components: {
				default: httpVueLoader('./site/components/about.vue')
			}			
        },
        { 
			name: 'training',
			path: '/training', 
			components: {
				default: httpVueLoader('./site/components/training.vue')
            }			            
        },
        { 
			name: 'careers',
			path: '/careers', 
			components: {
				default: httpVueLoader('./site/components/careers.vue')
            }			            
        },
        { 
			name: 'contact',
			path: '/contact', 
			components: {
				default: httpVueLoader('./site/components/contact.vue')
            }			            
        },
        { 
			name: 'advisorlink',
			path: '/advisorlink/', 
			components: {
				default: httpVueLoader('./site/components/advisorlink.vue')
            }			            
		},
		{ 
			name: 'annuitySearch',
			path: '/admin/annuitysearch/',
		},
		{
			name: 'ie',
			path: '/ie-legacy/'
		},
		{ 
			name: 'legalNotice',
			path: '/legal-notice',
			components: {
				default: httpVueLoader('./site/components/legal-notice.vue')
            }			            
		},
		{
			path: '*',
			redirect: '/'
		}		
	]	
});

var eventBus = new Vue();

//Base application
var vue = new Vue({
	router: router,
	el: '#vue',
	data: {	
		alert:  JSON.parse(JSON.stringify(alertDefault)),
		user: {}
	},
	methods: {
		clearAlert: function() {			
			this.alert = JSON.parse(JSON.stringify(alertDefault));
		},
		setAlert: function(display, message, css) {
			this.alert.display = display;
			this.alert.class = css;
			this.alert.message = message;			
		},
		logOut: function() {
			var _this = this;
			firebase.auth().signOut().then(function() {
				_this.user = {};
				localStorage.removeItem('cookie');
				eventBus.$emit('logout');
				_this.setAlert(true, 'You have been logged out successfully', 'alert-success');
			}).catch(function(error) {
				_this.setAlert(true, error.message, 'alert-danger');	
			});
		},
		logIn: function() {			
			if (this.$route.name != 'advisorlink') {
				this.$router.push('advisorlink');
			}
		}		
	}
});
Vue.filter('highlight', function(item, query) {
	if (item == null) {
		return;
	}
	var invalid = /[°"§%()\[\]{}=\\?´`'#<>|,;.:+_]+/g;
	var repl = query.trim().replace(invalid, "");
	var check = new RegExp(repl, "ig");	
	return item.toString().replace(check, function(matchedText,a,b){
		return ('<span class="bg-warning text-white">' + matchedText + '</span>');
	});
});
Vue.directive('tooltip', function(el, binding){
    $(el).tooltip({
  		title: binding.value,
      	placement: binding.arg,
      	trigger: 'hover'             
	});
})