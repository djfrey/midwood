Vue.use(Vuefire.firestorePlugin);

/*
//DEV
firebase.initializeApp({
    apiKey: "AIzaSyBA0HSdr_i2roBOCTr7LTQmIcQ4aMafpuo",
    authDomain: "midwood-8adb7.firebaseapp.com",
    databaseURL: "https://midwood-8adb7.firebaseio.com",
    projectId: "midwood-8adb7",
    storageBucket: "midwood-8adb7.appspot.com",
    messagingSenderId: "1090089763875",
    appId: "1:1090089763875:web:aa0cf28921f5c924"
});
*/

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

const alertDefault = {
	display: false,
	message: '',
	class: 'alert-primary',
	timeout: false
};

const baseURI = window.location.protocol+'//'+window.location.host+'/admin/';
const basePath = '/admin/advisorlink'

//Global components
httpVueLoader.register(Vue, './components/alert.vue');

//Routes and route components
const router = new VueRouter({
	mode: 'history',
	routes: [
		{ 
			name: 'files',
			path: '/files/:path(.*)', 			
			components: {
				default: httpVueLoader('./files.vue'),
				navigation: httpVueLoader('./components/navigation.vue')
			},
			pathToRegexpOptions: { strict: false },
			props: {
				default: true,
				navigation: true
			}
		},
		{
			name: 'users',
			path: '/users',
			components: {
				default: httpVueLoader('./users.vue'),
				navigation: httpVueLoader('./components/navigation.vue')
			},
			props: {
				default: true,
				navigation: true
			}
		},
		{
			name: 'staff',
			path: '/staff',
			components: {
				default: httpVueLoader('./staff.vue'),
				navigation: httpVueLoader('./components/navigation.vue')
			},
			props: {
				default: true,
				navigation: true
			}
		},
		{
			path: '*',
			redirect: '/files/'
		}		
	]
});

const eventBus = new Vue();

//Base application
new Vue({ 	
	router,
	el: '#vue',
	data: {
		user: {},
		alert:  JSON.parse(JSON.stringify(alertDefault)),
		baseURI: baseURI,
		fsDocs: [],
		documents: []
	},
	methods: {
		isLoggedIn: function() {
			var l = JSON.parse(localStorage.getItem('cookie')) || {};			
			if (l.key == 'mw-advisorlink') {      				
                this.user = l.user;
				this.role = l.role;
				this.loggedIn = true;	   				  
				if (this.user.isAdmin != 'yes') { //Non-admins should be redirected to the portal
					window.location.replace(window.location.protocol+'//'+window.location.host+'/advisorlink/');	
				}                           
			} else {
                this.user = {};
				localStorage.removeItem('cookie');
                this.role = '';
				this.loggedIn = false;
				window.location.replace(window.location.protocol+'//'+window.location.host+'/advisorlink/');
            }
		},		
		clearAlert: function() {			
			this.alert = JSON.parse(JSON.stringify(alertDefault));
		},
		setAlert: function(display, message, css) {
			this.alert.display = display;
			this.alert.class = css;
			this.alert.message = message;			
		},
		getDocuments: function() {
			var docsPromise = this.$bind('fsDocs',  db.collection('advisorlink').orderBy('sort', 'asc'), {maxRefDepth: 1});

			var _this = this;
			Promise.all([docsPromise]).then(function(response) {				
				var docs = response[0];							

				for (i = 0; i < docs.length; i++) {
					let f = docs[i];					
					f.children = docs.filter(e => {
						return e.parent.id == f.id;							
					});
					_this.documents.push(f);
				}
			});				
		}
	},
	created() {	
		this.isLoggedIn();
	}
	
});
Vue.filter('highlight', function(item, query) {
	var invalid = /[°"§%()\[\]{}=\\?´`'#<>|,;.:+_-]+/g;
	var repl = query.replace(invalid, "");
	var check = new RegExp(repl, "ig");
	return item.toString().replace(check, function(matchedText,a,b) {
		return ('<span class="bg-warning">' + matchedText + '</span>');
	});
});
Vue.filter('currency', function(value, dec) {
	dec = dec || 0;
	return value.toFixed(dec).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");	
});
	