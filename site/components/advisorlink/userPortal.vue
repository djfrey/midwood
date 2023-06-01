<template>
<div>
    <nav class="navbar navbar-expand-lg row navbar-dark" style="background-color: #5a8cc9;">
        <div class="navbar-nav mx-auto">
            <a class="nav-item nav-link" v-on:click.prevent="page = '1'" :class="{'active': page == '1'}" href="#"><i class="fad fa-copy"></i> Marketing, Rates &amp; Forms</a>
            <a class="nav-item nav-link" v-on:click.prevent="page = '2'" :class="{'active': page == '2'}" href="#"><i class="fad fa-user-friends"></i> Your Team</a>            
        </div>
    </nav>

    <div class="row" v-if="$parent.fromPortal">
        <div class="col mt-2">
            <button v-on:click="hideUser()" type="button" class="btn btn-primary"><i class="fad fa-door-open"></i> Employee portal</button>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-12">
            <img v-if="user.logo > ''" class="float-left mr-3 mt-3" style="max-width: 300px; padding-right: 50px" :src="user.logo"></img>            
        </div>

        <div v-show="page == '1'" class="col-lg-9 col-md-12 pt-3">
            <div class="col-12">
                <h4><i class="fad fa-lg fa-fw fa-bullhorn text-primary"></i> Announcements</h4>
            </div>
            <div class="col-12 ml-2">                
                <div v-if="user.message > ''" v-html="user.message"></div>
                <div v-else>There are currently no announcements.</div>
            </div>
        </div>        
    </div>
    
        <div v-show="page == '1'">                            
            <div class="row pt-3 border-top">
                <div class="col-12">
                    <div class="row">
                        <div class="col-lg-4 col-md-12 mb-2">
                            <h4><i class="fad mt-2 fa-lg fa-fw fa-copy text-primary"></i> Marketing, Rates &amp; Forms</h4>
                        </div>
                        <div class="col-lg-8 col-md-12">                            
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text bg-info border-info text-white"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" :minlength="searchMin" :disabled="!nodes.length" v-model="query" class="form-control" placeholder="Enter at least three characters to begin searching">                                                                       
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-danger" :disabled="query.length == 0" v-on:click="query = ''"><i class="fas fa-ban"></i> Clear</button>                                    
                                </div>
                            </div> 
                        </div>
                    </div>
                </div>                
                <div class="col-12 ml-2">                                
                    <p v-if="user.access.length == 0">You do not have access to any documents.</p>
                    <div id="tree-root" v-else>
                        <h3 v-if="loading"><i class="fas fa-circle-notch fa-spin"></i> Loading...</h3>			
                        <div v-else>
                            <h5 v-if="query.trim().length < searchMin">Showing all Documents</h5>
                            <h5 v-if="query.trim().length >= searchMin">Showing Documents matching "<strong>{{query}}</strong>"</h5>

                            <ol>
                                <!-- The nodes, activeNode is set on click -->
                                <node v-show="nodes.length > 0" v-for="(n, i) in nodes" :showall="false" :query="query" :node="n" :user="user" :key="n.id"></node>		
                                <!-- If a node's children collection is empty, show that to the user -->
                                <li v-if="nodes.length == 0">
                                    <div class="empty">		
                                        <span>
                                            <span class="fa-stack">
                                                <i class="fal fa-stack-2x fa-folder-open"></i>									
                                            </span>
                                        This folder is empty</span>
                                    </div>
                                </li>
                            </ol>                                                        
                        </div>                
                    </div>
                    <hr />
                    <div class="alert alert-success mr-3">
                        <i class="fas fa-info-circle fa-lg" aria-hidden="true"></i> <i>Always consult your back office for internal form requirements.</i>
                    </div>
                </div>
            </div>

            
            <div v-if="user.bluerock == 'yes'" class="row bluerock border-top pt-2">
                <div class="col">
                    <p>This website is neither an offer to sell nor a solicitation of an offer to buy the securities. An offering is made only by the applicable offering documents or Prospectus and only in those jurisdictions 
                    where permitted by law. This website must be read in conjunction with the applicable offering documents or Prospectus in order to understand fully all of the implications and risks of the offering of 
                    securities to which it relates and a copy of the offering documents or Prospectus must be available to you in connection with any offering. All information contained in this website is qualified by the terms 
                    of applicable offering documents or Prospectus. Neither the Attorney-General of the State of New York nor any other State regulators have passed on or endorsed the merits of any offering described herein. Any 
                    representation to the contrary is unlawful.  Past performance is no guarantee of future results.</p>
                    <p>Securities offered through Bluerock Capital Markets, LLC | Member FINRA/SIPC |</p>            
                </div>
            </div>
        </div>

        <div v-show="page == '2'">
            <div class="row pt-3 pb-3 border-bottom">
                <div class="col-12 pb-3">
                    <h4><i class="fad fa-lg fa-fw fa-key text-primary"></i> Your account manager<span v-if="user.iam.count > 1">s</span></h4>
                </div>
                <div v-for="i in iam" class="col-12">                                    
                    <img v-if="i.portrait > ''" :src="i.portrait" class="float-left rounded-lg mr-2" />
                    <h5>{{i.name}}</h5> 
                    <h6>Internal Account Manager</h6> 
                    <div><i class="fas fa-phone-alt"></i> {{i.phone}}</div>
                    <div><a :href="'mailto:'+i.email"><i class="fas fa-envelope" aria-hidden="true"></i> {{i.email}}</a></div>
                </div>
            </div>
            <div class="row pt-3 pb-3">
                <div class="col-12 pb-3">
                    <h4><i class="fad fa-lg fa-fw fa-users text-primary"></i> Your sales team</h4>
                </div>
                <div v-for="s in sales" class="col-sm-12 col-md-6 col-xl-4 mb-3 sales">                                                        
                    <img v-if="s.portrait > ''" :src="s.portrait" class="float-left rounded-lg mr-2" />
                    <div :class="{'has-border': s.color > ''}" :style="{'border-left-color': '#'+s.color}">
                        <h5>{{s.name}}</h5> 
                        <div style="max-width: 240px" v-if="s.states.length > 0">{{s.states.join(', ')}}</div> 
                        <div><i class="fas fa-phone-alt"></i> {{s.phone}}</div>
                        <div><a :href="'mailto:'+s.email"><i class="fas fa-envelope" aria-hidden="true"></i> {{s.email}}</a></div>
                    </div>
                </div>
            </div> 

            <div class="row">
                <div class="col-12 mb-3 mt-3 text-center">                    
                    <sales-map v-if="sales" :data="sales"></sales-map>
                </div>
            </div>               
        </div>    
</div>
</template>
<script>
module.exports = {   
    props: {
        user: {
            type: Object,
            required: true
        }
	},
    components: {
        node: httpVueLoader('./node.vue'),
        salesMap:  httpVueLoader('./map.vue')           
    },
    data: function() {
        return {    
            searchMin: 3,  
            iam: [],
            sales: [{name: '', states: []}],
            page: '1',
            query: '',
            fsAdvisorlink: [], //The firestore advisorlink collection
            fsUsers: [],
            fsStaff: [],
            docs: [], //The nested array of firestore objects
            users: [],
            staff: [],
            nodes: [],
            loading: false
        }
    },
    methods: {
        hideUser: function() {
            this.$parent.fromPortal = false;
            this.$parent.user = this.$parent.userCopy;
            this.$parent.role = this.$parent.roleCopy;
        },
        fileClass: function(node) {
			var style = "fas";
			var type = node.mime;
			switch(type) {
				case 'application/pdf':
					out = 'fa-file-pdf';
				break;
				case 'application/vnd.ms-excel':
				case 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet':
					out = 'fa-file-excel';
				break;
				case 'application/msword':
				case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
					out = 'fa-file-word';
				break;
				case 'application/vnd.ms-powerpoint':
				case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
					out = 'fa-file-powerpoint';
				break;
				case 'application/zip':
				case 'application/x-7z-compressed':
				case 'application/x-rar-compressed':
					out = 'fa-file-archive';
				break;
				case 'audio/wav':
				case 'audio/mpeg':				
					out = 'fa-file-audio';
				break;
				case 'image/bmp':				
				case 'image/jpeg':
				case 'image/png':
				case 'image/gif':
					out = 'fa-file-image';
				break;
				default:
					out = 'fa-file';
				break;
			}				
			return style+' '+out;
        },
        getStaff: function(coll) {               
            var out = [];
            for (var i = 0; i < coll.length; i++) {    
                
                var o = this.staff.filter(function(a) {
                    return a.id == coll[i].staffId;
                })[0];                
                             
                if (o.role == 'sales') {
                    o.states = coll[i].states;
                }
                out.push(o);
            }
            return out;
        },
        getData: function() {            
            this.loading = true;
            var _this = this;            
           // var docsPromise = this.$bind('fsAdvisorlink',  db.collection('advisorlink').orderBy('sort', 'asc'), {maxRefDepth: 1});   
            var docsPromise = axios.get('/site/data/firebase.docs.php');    
            var staffPromise = axios.get('/site/data/firebase.staff.php');       
            Promise.all([docsPromise, staffPromise]).then(function(response) {	                
                //Get the firestore collection, create the nested array of objects			
                //var d = response[0].data || [];		                							                	
                var d = response[0].data || [];		                							                	
				for (i = 0; i < d.length; i++) {
                    let x = d[i];
                    //The 'some' variable is set when some of the node's children are assigned
                    x.some = false;					
                    x.children = d.filter(function(y) {
                        return y.parent.id == x.id;
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
                _this.staff = response[1].data || [];                
                _this.iam = _this.getStaff(_this.$parent.user.iam || []);
                _this.sales = _this.getStaff(_this.$parent.user.sales || []);                  
				_this.loading = false;
			});
		},
        bubbleVisibility: function() {
            this.collapsed = false;	
            this.visible = true;	            
		}
    },
    mounted: function() {
        this.getData();        
        eventBus.$on('showThis', function(s, i) {            
            s.$emit('showMe');   
        });           
    }

}
</script>
<style >
.has-border {
    float: left;
    height: 100%;
    padding-left: 10px;
    border-left-width: 10px;
    border-left-style: solid;    
}
.navbar {
    padding: 0px;
}

.navbar .navbar-nav a.nav-item {
    font-size: 1.2rem;
    color: #FFF;
    padding: 15px;
}

.navbar .navbar-nav a.nav-item.active, 
.navbar .navbar-nav a.nav-item.active:focus, 
.navbar .navbar-nav a.nav-item.active:hover {
    background-color: rgba(255,255,255,0.8); 
    color: #37619b;   
}

.navbar .navbar-nav>a:hover {
    color: #5a8cc9 !important;
   	background-color: rgba(255,255,255,0.7);
}

div .bluerock {
    font-size: 12px; 
    line-height: 15px;     
}

.fa-stack {
    top: -3px;
}

.fa-stack, .fa-folder-times {
	font-size: 0.8rem;
		
}

.fa-stack-1x {
	margin-top: 1px;
}

.border-bottom {
    padding-bottom: 3px;
}

ol {
	list-style-type: none;	
	font-size: 16px;
	margin: 0px;
	padding: 0px;
}

#tree-root {
	margin-top: 20px;	
}

#tree-root .node {	
	padding: 5px 0px 0px 15px;	
	position: relative;	
}

#tree-root > div > ol > li > .node {
    padding-left: 0px;
}

.folder {
    cursor: pointer;
}


#tree-root .node .on-clipboard span.name a {	
	color: #999;
	font-style: oblique;
}

#tree-root .node span.name i {	
	margin-right: 5px;
}


#tree-root .folder .fa-stack .fa-stack-2x {
	color: #fde082;
	cursor: pointer;
}

#tree-root .text-success .folder .fa-stack .fa-stack-2x {
	color: inherit;
	cursor: pointer;
}

.fa-stack-1x.fa-plus {
	color: #000;
}

#tree-root .fa-file-word {
	color: #2a5699;
}

#tree-root .fa-file-excel {
	color: #1f7246;
}

#tree-root .fa-file-pdf {
	color: #ff2116;
}

#tree-root .fa-file-powerpoint {
	color: #b7472a;
}

#tree-root .node span.name a i.fa-link {	
    color: #333;
}

#tree-root .node span.name a i.fa-file {
    color: #999;
}

#tree-root ol ol li {
	padding-left: 5px;
}

.fade-enter-active, .fade-leave-active {
  transition: all .3s ease;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
  transform: translateX(100px);
}

.fade-leave, .fade-enter-to {
    opacity: 1;
    transform: translateX(0);
}
</style>