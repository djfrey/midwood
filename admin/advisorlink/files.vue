<template>
	<div class="container">
		<div class="row">	
			<div class="col-12 pb-2 mb-2 border-bottom">
				<div class="custom-control custom-radio custom-control-inline">
					<input type="radio" id="radioAdvisorlink" v-on:click="setManage('advisorlink')" checked="checked" name="filesToggle" class="custom-control-input">
					<label class="custom-control-label" for="radioAdvisorlink">Advisorlink files</label>
				</div>
				<div class="custom-control custom-radio custom-control-inline">
					<input type="radio" id="radioMidwood" v-on:click="setManage('midwood')" name="filesToggle" class="custom-control-input">
					<label class="custom-control-label" for="radioMidwood">Midwood internal files</label>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="mb-2 col-lg-12 col-xl-6">
				<!-- Node controls -->
				<span>
					<button type="button" class="btn btn-sm btn-primary" :disabled="addDisabled" v-on:click="modalOpen()" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#fileUpload"><i class="fas fa-file-upload"></i> Upload files</button> 
					<button type="button" class="btn btn-sm btn-primary" :disabled="addDisabled" v-on:click="addNew('link')"><i class="fas fa-link"></i> Add link</button>
					<button type="button" class="btn btn-sm btn-primary" :disabled="addDisabled" v-on:click="addNew('folder')"><i class="fas fa-folder-plus"></i> Add folder</button> | 								
					<button type="button" class="btn btn-sm btn-warning" :disabled="copyDisabled" v-on:click="toClipboard('copy')"><i class="fas fa-copy"></i> Copy</button> | 
					<button type="button" class="btn btn-sm btn-info" :disabled="alphaSortDisabled" v-on:click="alphaSort()"><i class="fal fa-sort-alpha-down"></i> Alpha sort</button>							
				</span>
				<!-- The clipboard, will be shown when something's been added to it -->			
			</div>
			<div class="mb-2 col-lg-12 col-xl-6">
				<clipboard></clipboard>
			</div>
		</div>		
		
		<!-- 
		<div class="mb-2" v-if="$root.user.userName == 'djfrey'">
			<button type="button" class="btn btn-sm btn-danger" v-on:click="loadLegacy()"><i class="fal fa-sort-alpha-down"></i> Load legacy</button>			
		</div>
		-->

		<!-- Show breadcrumbs when we're nested -->
		<breadcrumb :path="path"></breadcrumb>
		<upload :key="key"></upload>
		<replace :key="key + 1"></replace>

		<div id="tree-root">	

			<h2 v-if="loading"><i class="text-primary fas fa-circle-notch fa-spin"></i> Loading...</h2>				
			<div v-else>				
				<ol>
					<!-- the 'Back to <parent>' button, shown for navigation when a user is in a nested folder -->
					<li v-if="parentLink.path">
						<div class="node">						
							<span class="home"><a href="" v-on:click.prevent="openFolder(parentLink.path)"><i class="fas fa-arrow-to-left"></i> Back to {{parentLink.name}}</a></span>
						</div>
					</li>
					<!-- The nodes, activeNode is set on click -->
					<li :class="{'active': activeNode.id == n.id}" v-if="nodes.length > 0" v-for="(n, i) in nodes">						
						<node :node="n" :index="i"></node>		
					</li>
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
		<!-- The editing panel -->
		<edit :node="activeNode"></edit>
	</div>
</template>
<script>
//Defaults for new nodes
const folderDefault = {
	name: 'New folder',
	sort: 0,
	type: 'folder',
	parent: {}
};

const linkDefault = {
	name: 'New link',
	sort: 0,
	type: 'link',
	url: '',
	parent: {}
};

const activeNodeDefault = {
	id: '', 
	name: '', 
	type: '',
	pasting: false
}

const deletingDefault = {
	id: ''
}

module.exports = {
	components: {
		clipboard: httpVueLoader('./components/files/clipboard.vue'),
		breadcrumb: httpVueLoader('./components/files/breadcrumb.vue'),
		edit: httpVueLoader('./components/files/edit.vue'),
		upload: httpVueLoader('./components/files/upload.vue'),
		replace: httpVueLoader('./components/files/replace.vue'),
		node: httpVueLoader('./components/files/node.vue'),		
	},
	data: function() {
		return {
			manage: {database: 'advisorlink', storage: 'advisorlink'}, //The settings to determine which db/storage collection we're using		
			fsCollection: [], //The nested array of firestore objects
			nodes: [], //The working collection of objects, updated per route
			rootNode: {path: '', name: ''}, //The current node
			root: {}, //The root element containing all other elements	
			activeNode: activeNodeDefault, //The node being edited	
			clipboard: {}, //The clipboard object
			clipboardAction: '', //What we'll do with the clipboard (copy or cut)
			parentLink: {}, //The link to the current node's parent, used for navigation in the breadcrumbs component and file uploading
			editing: false, //If we're editing a node, this controls visibility of the edit component
			loading: false, //Show a loading indicator before the main collection is available
			adding: false, //Disable buttons when a node is being added
			deleting: deletingDefault, //Copy the object being deleted, prevent clicking and show a visual
			path: '', //Holds the route path so we can directly navigate to sub-folders
			users: [], //The collection of all users
			key: 0, //An incrementing key passed to the file upload modal to force it to re-render the file upload controls
			pasting: 0, //Disable some controls when an element is being pasted,
			delCount: 0, //Keep track of sub-items being deleted
			legacy: []

		}
	},
	methods: {	
		loadLegacy: function() {	
			return;
			////	
			var parent = db.collection(this.manage.database).doc('root');
			var _this = this;
			var p = [];
			var n = [];
			for (var i = 0; i < this.legacy.length; i++) {						
				//Create the base folders
				var node = {
					name: this.legacy[i].title,
					parent: parent,
					sort: i,
					type: this.legacy[i].element_type				
				}
				p.push(db.collection(this.manage.database).add(node));
				n.push(node);
			}

			Promise.all(p).then(function(response) {									
				for (var j = 0; j < response.length; j++) {	
					node = n[j];
					node.id = response[j].id;
					node.direction = null;
					if (node.type == 'file') {
						_this.copyFile(node);				
					}				
					node.parent = {id: _this.rootNode.id};								
					_this.nodes.unshift(node);
					_this.pasteChildren(_this.legacy[j], node);		
					//_this.clear();
				}	
			});					
		},
		pasteChildren: function(source, node) {
			var _this = this;			
			var p = [];			
			var n = [];
			node.children = node.children || [];
			for (var i = 0; i < source.children.length; i++) {																				
				var type = source.children[i].element_type;
				var nodeNew = {
					name: source.children[i].title,					
					sort: i,
					type: type,
					parent: db.collection(this.manage.database).doc(node.id)
				};	
				if (type == 'document') {
					var parser = document.createElement('a');
					parser.href = source.children[i].path;					
					if (parser.host && parser.host != window.location.host) {
						nodeNew.type = 'link';
						nodeNew.url = source.children[i].path;
					} else {
						nodeNew.type = 'file';
						nodeNew.filename = decodeURIComponent(parser.pathname.substring(parser.pathname.lastIndexOf('/')+1));
						nodeNew.url = 'http://midwoodfinancial.org/docs/'+source.children[i].path;											
					}
				}
				n.push(nodeNew);
				p.push(db.collection(this.manage.database).add(nodeNew));
			}
			Promise.all(p).then(function(response) {									
				for (var j = 0; j < response.length; j++) {	
					nodeNew = n[j];										
					nodeNew.id = response[j].id;
					nodeNew.direction = null;			
					if (nodeNew.type == 'file') {							
						_this.copyFile(nodeNew);
					}
					nodeNew.parent = node;
					node.children.push(nodeNew);					
					if (source.children[j].children && source.children[j].children.length > 0) {						
						_this.pasteChildren(source.children[j], nodeNew);
					} else {
						nodeNew.children = [];
					}									
				}				
			});
		},
		copyFile: function(node) {
			//Get the old file
			var _this = this;						
			var xhr = new XMLHttpRequest();
			xhr.responseType = 'blob';			
			xhr.onload = function() {
				var status = xhr.status;				
				var blob = xhr.response;		
				var mime = xhr.response.type;				
				if (status == '200') {
					storage.child(_this.manage.storage+'/'+node.id).put(blob).then(function(response) {					
						response.ref.getDownloadURL().then(function(url) {						
							db.collection(_this.manage.database).doc(node.id).update({url: url, mime: blob.type}).then(() => {								
								node.url = url;							
								node.mime = mime;
								console.log(node);
							});
						});
					});
				} else {
					console.log(node, status);
				}
			}
			xhr.onerror = function(e) {
				node.url = '';
				console.log(e);
			}
			//Get the file from the old node
			xhr.open('GET', node.url, true);			
			xhr.send();	
		},
		getUsers: function() {
			//Get all users
			var _this = this;
			var usersPromise = this.$bind('fsUsers',  db.collection('users'));   
			Promise.all([usersPromise]).then(function(response) {	
				let u = response[0] || [];														
				for (j = 0; j < u.length; j++) {
					let a = u[j];				
					_this.users.push(a);					
				}
			});  
		},
		getDocs: function() {
			this.loading = true;
			//Get the main collection from firebase
			var fsPromise = this.$bind('fsAdvisorlink',  db.collection(this.manage.database).orderBy('sort', 'asc'), {maxRefDepth: 1});	
//			var legacyFs = axios.get('./data/legacy.php');
			//Get the route path to determine what we're going to show
			this.path = this.$route.params.path;
			var _this = this;
			this.fsCollection = [];
			Promise.all([fsPromise]).then(function(response) {	
				//_this.legacy = response[1].data;					
				//Get the firestore collection, create the nested array of objects			
				var fs = response[0] || [];														
				for (var i = 0; i < fs.length; i++) {
					let f = fs[i];
					f.direction = null; //for sorting	
					//Hold the kids			
					f.children = fs.filter(e => {
						if (e.parent) {
							return e.parent.id == f.id;							
						} else {							
							db.collection(_this.manage.database).doc(e.id).delete().then(() => {
								if (e.type == 'file') {
									storage.child(_this.manage.storage+'/'+e.id).delete();
								}
								_this.delAccess(e);
							});
						}
					}) || [];						
					_this.fsCollection.push(f);
				}			
				//Set the global root element, the array element that will contain everything else 
				_this.root = _this.fsCollection.filter(n => {
					return n.id == 'root';
				});						
				//Set the root element based on the route
				_this.setRoot(_this.path);					
				_this.loading = false;
			});			
		},
		getRootFolder: function(path, arr) {			
			var f = {};			
			if (path.length) {				
				var p = path[0]; //Get the first path element, this is the name of the folder we're looking for
				path.shift(); //Take the first element off the array so we can recursively drill down the folder structure
				f = arr.children.filter(a => {				
					return a.name == p;
				});				
				//If the provided path is invalid, return				
				if (!f.length) {					
					f = arr;		
					router.push({path: this.parentLink.path});								
					return f;					
				} else {
					f = f[0];
				}
				//Find the folder in the array of passed-in folders
				if (path.length > 0) { //If the path array contains more elements, dig deeper - find the next element in the children of the returned element
					return this.getRootFolder(path, f);
				}			
			} else {
				f = arr;
			}
			return f;			
		},
		setRoot: function(path) {
			if (path.startsWith('/files/')) {
				path = path.replace('/files/', '');
			}			
			
			var arrPath = path.split("/");
			if (arrPath[arrPath.length - 1] == '') { arrPath.pop(); } //Remove the last blank element					
			this.rootNode = this.getRootFolder(arrPath, this.root[0]); //'root' is the array containing all of our data, use it to recursively find the current folder based on the path											
			this.nodes = this.rootNode.children || [];  //The children of the current folder are displayed										
		},
		setManage: function(val) {
			switch (val) {
				case 'advisorlink':
					this.manage = {database: 'advisorlink', storage: 'advisorlink'};
				break;
				case 'midwood':
					this.manage = {database: 'midwood', storage: 'midwood'};
				break;
			}
		},
		modalOpen: function() {
			this.key = this.key + 1;
		},
		edit: function(node) {
			this.editing = true;			
		},
		openFolder: function(name) {			
			this.activeNode = activeNodeDefault; //Reset activeNode, otherwise the old activeNode will be clipboard-available
			this.editing = false;
			router.push({path: name});
		},
		testName: function(node, count) {
			var tn;
			var n = node.name.replace(/\\|\//g, ''); //Remove all slashes
			var ext = '';
			if (node.type == 'file' && node.name == '') { //For files that haven't been renamed from the default name, use the file name as the test string without the extension
				var f = node.filename.split('.');								
				ext = '.'+f.pop();
				n = f.join('.');
			}
			if (count == 0) {
				//tn = n + ext;
				tn = n;
			} else {								
				//tn = n + ' ('+count+')' + ext;
				tn = n + ' ('+count+')';
			}
			if (this.nodes.some(n => { return tn == n.name })) {								
				return this.testName(node, count + 1);
			} 
			return tn;			
		},
		addNew: function(type) {
			switch (type) {
				case 'link':
					node =  Object.assign({}, linkDefault);					
				break;
				case 'folder':
					node =  Object.assign({}, folderDefault);
				break;
			}
			this.add(node);
		},
		add: function(node) {	
			this.adding = true;		
			var _this = this;
			node.parent = db.collection(this.manage.database).doc(this.rootNode.id);
			node.name = this.testName(node, 0);
			
			db.collection(this.manage.database).add(node).then(function(response) {				
				//Update sort on all other nodes in this group
				if (_this.nodes.length) {
					var p = [];
					for (var i = 0; i < _this.nodes.length; i++) {
						let s = Number(_this.nodes[i].sort) + 1;					
						p.push(db.collection(_this.manage.database).doc(_this.nodes[i].id).update({sort: s}));
						_this.nodes[i].sort = s;
					}
					Promise.all(p);
				}				
				node.id = response.id;
				node.direction = '';
				node.children = [];
				node.parent = {id: _this.rootNode.id};
				_this.nodes.unshift(node);			
				_this.editing = false;	
				_this.adding = false;
				_this.$root.setAlert(true, _this.nodeType(node)+' added successfully', 'alert-success');		
			});
		},
		del: function(node) {			
			this.deleting = this.activeNode;											
			db.collection(this.manage.database).doc(node.id).delete().then(() => {
				if (node.type == 'file') {
					storage.child(this.manage.storage+'/'+node.id).delete();
				}
				this.delAccess(node);
				//Update sort on all nodes in this group with a higher sort order
				if (this.nodes.length) {
					var after = this.nodes.filter(a => {
						return a.sort > node.sort;
					});

					var p = []
					for (var i = 0; i < after.length; i++) {
						let s = Number(after[i].sort) - 1;
						p.push(db.collection(this.manage.database).doc(after[i].id).update({sort: s}));
						after[i].sort = s;						
					}
					Promise.all(p);
				}
				
				if (node.children.length) {
					this.delChildren(node);
				} else {
					let j = this.nodes.findIndex(f => {
						return f.id == this.deleting.id;
					});
					this.nodes.splice(j, 1);
					this.activeNode = activeNodeDefault;								
					this.$root.setAlert(true, this.nodeType(this.deleting)+' deleted successfully', 'alert-success');			
					this.deleting = deletingDefault;
				}
			});
		},
		nodeType: function(node) {
			return node.type.charAt(0).toUpperCase() + node.type.slice(1);
		},
		delChildren: function(node) {
			var p = [];
			var _this = this;
			for (var i = 0; i < node.children.length; i++) {
				this.delCount++;
				p.push(db.collection(this.manage.database).doc(node.children[i].id).delete());
				this.delAccess(node);
				if (node.children[i].type == 'file') {
					storage.child(this.manage.storage+'/'+node.children[i].id).delete();			
				}
			}
			Promise.all(p).then(function(result) {
				for (var j = 0; j < result.length; j++) {
					_this.delCount--;
					if (node.children[j].children.length) {
						_this.delChildren(node.children[j]);
					}											
				}
			});
		},
		delAccess: function(node) {
			for (var i = 0; i < this.users.length; i++) {
				var j = this.users[i].access.findIndex(f => {
					return f == node.id;
				});
				if (j != -1) {
					this.users[i].access.splice(j, 1);
					db.collection('users').doc(this.users[i].id).update({access: this.users[i].access});
				}
			}
		},
		alphaSort: function() {
			var nodes = Object.assign([], this.nodes);
			var p = [];
			var _this = this;

			nodes.sort(function(a, b) {
				var n1 = a.name.toLowerCase();
				var n2 = b.name.toLowerCase();
				if (n1 < n2) {
					return -1;
				}
				if (n1 > n2) {
					return 1;
				}
				return 0;
			});

			for (n = 0; n < nodes.length; n++) {				
				p.push(db.collection(this.manage.database).doc(nodes[n].id).update({sort: n}));
				nodes[n].sort = n;										
			}

			Promise.all(p).then(function() {
				_this.nodes = nodes;
				_this.rootNode.children = nodes;
				_this.$root.setAlert(true, 'Sorting completed successfully', 'alert-success');						
			});
		},
		sort: function(node, direction) {	
			var a = -1;
			var b = -1;
			var x = -1;
			var y = -1;
			var otherDirection;
			var _this = this;
			for (i = 0; i < this.nodes.length; i++) {				
				if (this.nodes[i].id == node.id) {
					a = i;					
					switch (direction) {
						case 'down':
							b = a + 1;					
							otherDirection = 'up';
						break;
						case 'up':
							b = a - 1;
							otherDirection = 'down';
						break;
					}
					x = this.nodes[a].sort;
					y = this.nodes[b].sort;
					this.nodes[a].sort = y;
					this.nodes[a].direction = direction;
					this.nodes[b].sort = x;
					this.nodes[b].direction = otherDirection;
					var p = [];					
					p.push(db.collection(this.manage.database).doc(this.nodes[a].id).update({sort: y}));
					p.push(db.collection(this.manage.database).doc(this.nodes[b].id).update({sort: x}));					
					Promise.all(p);
					//Wait 300 ms for the sorting CSS animation to complete
					setTimeout(() => {
						this.nodes[a].direction = null;
						this.nodes[b].direction = null;
						this.nodes[a] = this.nodes.splice(b, 1, this.nodes[a])[0];	
					}, 300);
					
					break;
				}
			}
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
		toClipboard: function(a) {
			this.clipboard = this.activeNode;
			this.clipboardAction = a;
		}
	},
	computed: {
		addDisabled: function() {
			return this.adding || this.deleting.id > '';
		},
		copyDisabled: function() {
			return 	!this.activeNode.id || (this.clipboard.id == this.activeNode.id) || this.deleting.id > '';
		},
		alphaSortDisabled: function() {
			return this.nodes.length < 2 || this.deleting.id > '';
		}
	},
	mounted() {		
		this.getUsers();
		this.getDocs();	

	},
	watch: {
    	'$route' (to, from) {			
			this.path = to.path;			
			this.setRoot(this.path);			
		},
		activeNode: function(old) {
			this.editing = false;
		},
		manage: function(oldVal, newVal) {
			if (oldVal.database != newVal.database) {
				this.getDocs();
				this.clipboard = {};
				this.clipboardAction = '';
			}
		},
		delCount: function(newVal, oldVal) {												
			if (newVal == 0 && oldVal > 0) {
				let j = this.nodes.findIndex(f => {
					return f.id == this.deleting.id;
				});
				this.nodes.splice(j, 1);
				this.activeNode = activeNodeDefault;								
				this.$root.setAlert(true, this.nodeType(this.deleting)+' deleted successfully', 'alert-success');			
				this.deleting = deletingDefault;
			}
		}
  	}
}
</script>
<style scoped>
.fa-stack, .fa-folder-times {
	font-size: 0.8rem;
		
}

.fa-stack-1x {
	font-size: 14px;
}

.home:hover {
	text-decoration: underline;
}

ol {
	list-style-type: none;	
	font-size: 16px;
	margin: 0px;
	padding: 0px;
}

.breadcrumb {
	padding: 5px;
}

#tree-root {
	margin-top: 10px;	
}

#tree-root .node {	
	padding: 5px 10px;	
	position: relative;
	border-bottom: 1px solid #CCC;
	cursor: pointer;
}

#tree-root .node.active, #tree-root .node.active:hover, #tree-root li.active {	
	background-color: #def0ff;
}

#tree-root .node .on-clipboard span.name a {	
	color: #999;
	font-style: oblique;
}


#tree-root .node .on-clipboard span.name *:hover {	
	text-decoration: none;
	cursor: default;
}

#tree-root .node .on-clipboard .fas, #tree-root .node .on-clipboard .far {
	color: #999;
}


#tree-root .node span.name i {	
	margin-right: 5px;
}


#tree-root .node:hover {
	background-color: #eef7ff;
}

#tree-root .fa-folder-plus, #tree-root .fa-folder-minus, #tree-root .fa-folder, #tree-root .fa-folder-open {
	color: #fde082;
	cursor: pointer;
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

#tree-root a, #tree-root a:hover {
	color: #000;	
	text-decoration: none;
	
}

#tree-root ol ol li {
	padding-left: 25px;
}

.node.up {	
	animation-name: nodeup;
	animation-duration: 0.3s;
}

.node.down {	
	animation-name: nodedown;
	animation-duration: 0.3s;
}

@keyframes nodeup {
    0%   {top: 0px;}    
    100% {top: -30px;}
}

@keyframes nodedown {
    0%   {top: 0px;}    
    100% {top: 30px;}
}

</style>