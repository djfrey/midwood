<template>    
<div>
    <nav class="navbar navbar-expand-lg row navbar-dark" style="background-color: #5a8cc9;">
        <div class="navbar-nav mx-auto">
            <a class="nav-item nav-link" v-on:click.prevent="page = '1'" :class="{'active': page == '1'}" href="#"><i class="fad fa-link"></i> Links</a>
            <a class="nav-item nav-link" v-on:click.prevent="page = '2'" :class="{'active': page == '2'}" href="#"><i class="fad fa-copy"></i> Documents</a>            
            <a class="nav-item nav-link" v-on:click.prevent="page = '3'" :class="{'active': page == '3'}" href="#"><i class="fad fa-calendar"></i> Calendar</a>
        </div>
    </nav>
    <div class="row">
        <div class="col">
            <h1 class="border-bottom pb-2"><i class="fad fa-tools"></i> Employee Portal</h1>
        </div>
    </div>           
    <div v-show="page == '1'">                
        <div class="row">
            <div class="col-12 mb-2 pb-2" v-if="user.isAdmin == 'yes'">
                <div class="border-bottom">
                    <h4><i class="fa-fw fad fa-unlock"></i> <a href="./admin/advisorlink">Advisorlink administration</a></h4>
                    <h4 class="mt-1"><i class="fa-fw fas fa-search"></i> <a href="./admin/annuitysearch">Annuity search tool</a></h4>
                </div>
            </div>
            <div class="col-12">
                <h4><i class="fad fa-users"></i> Advisorlink user pages</h4>
                <h3 v-if="loading"><i class="fas fa-circle-notch fa-spin"></i> Loading...</h3>	
                <ul v-else class="mb-2">
                    <li class="mb-1" v-for="u in users">
                        <i class="fa-fw fas text-primary mr-2 fa-angle-right"></i><a href="#" v-on:click.prevent="showUser(u)">{{u.displayName}}</a>
                    </li>
                </ul>               
            </div> 
        </div>
    </div>
    <div v-show="page == '2'">
        <div class="row pb-3">
            <div class="col">
                <div id="tree-root">
                    <h3 v-if="$parent.loading"><i class="fas fa-circle-notch fa-spin"></i> Loading...</h3>				
                    <div v-else>
                        <div class="alert alert-success mr-1">
                            <p>Enter some or all of a file or folder name below to find items that match it. Click a folder name to expand it and see its contents.  Click a file to download it.</p>                                
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input type="text" :minlength="searchMin" :disabled="!nodes.length" v-model="query" class="form-control" placeholder="Enter at least three characters to begin searching">                                    
                            </div> 				
                        </div>

                        <h4 v-if="query.trim().length < searchMin">Showing all documents</h4>
                        <h4 v-if="query.trim().length >= searchMin">Showing documents matching "<strong>{{query}}</strong>"</h4>

                        <ol>
                            <!-- The nodes, activeNode is set on click -->
                            <node v-show="nodes.length > 0" v-for="(n, i) in nodes" :showall="true" :query="query" :node="n" :user="user" :key="n.id"></node>		
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
            </div>
        </div>
    </div>                
    <div v-show="page == '3'">
        <div class="row">
            <div class="col mb-3 mt-1"> 
                <iframe src="https://calendar.google.com/calendar/embed?src=vgr3iagu4l3vggkmjc0l34980o%40group.calendar.google.com&ctz=America%2FNew_York" style="border: 0" width="100%" height="700" frameborder="0" scrolling="no"></iframe>
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
        },
        role: {
            type: String,
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
            loading: false,     
            fsMidwood: [], //The firestore advisorlink collection            
            docs: [], //The nested array of firestore objects
            nodes: [],
            users: [],
            query: ''
        }
    },
    methods: {
        getData: function() {            
            this.loading = true;
            var _this = this;            
            var docsPromise = this.$bind('fsMidwood',  db.collection('midwood').orderBy('sort', 'asc'), {maxRefDepth: 1});
            var usersPromise = this.$bind('fsUsers',  db.collection('users').orderBy('displayName', 'asc'), {maxRefDepth: 1}); 
            Promise.all([docsPromise, usersPromise]).then(function(response) {	                
                //Get the firestore collection, create the nested array of objects			
				var d = response[0] || [];														
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

				//Get users
                let u = response[1] || [];														
				for (j = 0; j < u.length; j++) {
					let a = u[j];					
					_this.users.push(a);					
                } 

                _this.loading = false;      
			});
        },
        showUser: function(user) {
            this.$parent.fromPortal = true;
            this.$parent.userCopy = JSON.parse(JSON.stringify(this.$parent.user));
            this.$parent.roleCopy = Object.assign('', this.$parent.role);
            this.$parent.user = user;
            this.$parent.role = 'user';
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
                
                var o = this.$parent.staff.filter(function(a) {
                    return a.id == coll[i].staffId;
                })[0];
                             
                if (o.role == 'sales') {
                    o.states = coll[i].states;
                }
                out.push(o);
            }
            return out;
        },
        bubbleVisibility: function() {
            this.collapsed = false;	
            this.visible = true;	
		}
    },
    mounted: function() {
        this.getData();
        this.iam = this.getStaff(this.user.iam || []);
        this.sales = this.getStaff(this.user.sales || []);        
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

ul, ol {
	list-style-type: none;	
	font-size: 16px;
	margin: 0px;
	padding: 0px;
}

#tree-root {
	
}

#tree-root .node {	
	padding: 5px 0px 0px 15px;	
	position: relative;	
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