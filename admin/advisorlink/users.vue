<template>
	<div class="container">
		<transition name="fade" mode="out-in">
			<div class="mt-2" v-if="!editing" key="list">
				<button 
					type="button" 
					v-on:click="addNew()"
					class="btn btn-sm btn-primary">
						<i class="fas fa-user-plus"></i> Add an account
				</button>
				<div v-if="!loading && users.length">                
					<div class="form-row mt-2">			
						<div class="col search input-group form-label-group">
							<input type="text" class="form-control" v-model="search" placeholder="Type a user name to begin searching...">
							<label>Type an account name, account manager name or sales team member name to begin searching...</label>
							<div class="input-group-append">
								<button type="button" :disabled="search == ''" v-on:click="search = ''" class="btn btn-secondary"><i class="fas fa-times"></i> Clear</button>
							</div>				
						</div>			
					</div>	
					<div class="card mb-3">
						<ul class="list-group list-group-flush">
							<li class="list-group-item" :class="{'inactive': n.enabled == 'no'}" v-for="n in paginatedData">                    
								<div class="float-left">                
									<h4 class="long-name" v-html="$options.filters.highlight(n.displayName, search)"></h4>							
									<span class="badge badge-pill badge-info" v-if="n.enabled == 'no'"> <i class="fa fa-bell" aria-hidden="true"></i> Disabled</span>						
									<div class="detail"><span class="text-secondary">IAM:</span> <span class="text-info" v-html="$options.filters.highlight(n.iamDisplay, search)"></span></div>
									<div class="detail"><span class="text-secondary">Sales:</span> <span class="text-info" v-html="$options.filters.highlight(n.salesDisplay, search)"></span></div>						
								</div>
								<div class="float-right">
									<div class="text-right">
										<button 
											type="button"
											v-on:click="edit(n);" 
											class="btn btn-sm btn-success"                                
										><i class="fas fa-pencil-alt"></i> Edit</button>
										<button 
											type="button"
											v-on:click="n.deleting = true;"
											class="btn btn-sm btn-danger"
											:disabled="n.deleting"
										><i class="fas fa-trash"></i> Delete</button>                                               
									</div>
									<div class="float-right pt-2" v-show="n.deleting">
											<span class="text-danger">Are you sure?</span>  
											<button type="button" v-on:click="del(n)" class="btn btn-sm btn-success"><i class="fas fa-check"></i> Yes</button>
											<button type="button" v-on:click="n.deleting = false;" class="btn btn-sm btn-danger"><i class="fas fa-times"></i> No</button>
									</div>
								</div>								
							</li>                                
						</ul>
						<div class="card-footer">
							<div class="row">
								<div class="col-2">
									<button class="btn btn-primary" type="button" :disabled="pageNumber == 0" v-on:click="prevPage()"><i class="fas fa-chevron-left"></i> Back</button>
								</div>
								<div class="col-8 mt-2" style="text-align: center">
									<span v-if="pageCount > 0">page {{pageNumber + 1}} of {{pageCount}}</span>
									<span v-else>Your search returned no results</span>
								</div>
								<div class="col">
									<button class="float-right btn btn-primary" type="button" :disabled="pageNumber == pageCount - 1 || pageCount == 0" v-on:click="nextPage()">Next <i class="fas fa-chevron-right"></i></button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div v-if="!loading && !users.length" class="border-top mt-2 pt-2">
					<h3><i class="text-primary far fa-square"></i> <span>No accounts exist, yet.</span></h3>
				</div>
				<div v-if="loading" class="border-top mt-2 pt-2">
					<h2 v-if="loading"><i class="text-primary fas fa-circle-notch fa-spin"></i> Loading...</h2>	
				</div>							
			</div>
			<div v-if="editing" key="alias">
				<edit></edit>				
			</div>
		</transition>
	</div>
</template>
<script>
const userDefault = { 
	id: '',
	access: [],
	bluerock: "no",
	displayName: "", 
	enabled: "no", 
	iam: [], 
	logo: "",
	message: "", 
	password: "",
	sales: [], 
	userName: "",
	iamDisplay: "Not assigned", 
	salesDisplay: "Not assigned" 
};

module.exports = {
	components: {
		edit: httpVueLoader('./components/users/edit.vue')
	},
	data: function() {
		return {
            fsAdvisorlink: [], //The firestore advisorlink collection
            fsUsers: [],
            fsStaff: [],
            docs: [], //The nested array of firestore objects
            users: [],
			user: {},
            staff: [],
			nodes: [], //The working collection of objects, updated per route
			rootNode: {path: '', name: ''}, //The current node
			root: {}, //The root element containing all other elements				
			editing: false, //If we're editing a node, this controls visibility of the edit component
			loading: false, //Show a loading indicator before the main collection is available
			search: '',
			size: 5, //Elements per page
            pageNumber: 0 //Current page number
		}
	},
	methods: {	
        getData: function() {
            this.loading = true;
            var _this = this;
            var docsPromise = this.$bind('fsAdvisorlink',  db.collection('advisorlink').orderBy('sort', 'asc'), {maxRefDepth: 1});
            var usersPromise = this.$bind('fsUsers',  db.collection('users').orderBy('displayName', 'asc'), {maxRefDepth: 1});            
            var staffPromise = this.$bind('fsStaff',  db.collection('staff').orderBy('name', 'asc'), {maxRefDepth: 1});    
            Promise.all([docsPromise, usersPromise, staffPromise]).then(function(response) {	                
				//Get the firestore collection, create the nested array of objects			
				var d = response[0] || [];														
				for (i = 0; i < d.length; i++) {
					let x = d[i];
					//The 'some' variable is set when some of the node's children are assigned
					x.some = false;						
					x.children = d.filter(y => {
						return y.parent.id == x.id;							
					});						
					_this.docs.push(x);
				}			
				//Set the global root element, the array element that will contain everything else 
				_this.root = _this.docs.filter(n => {
					return n.id == 'root';
				});			
				//Set the root element 
				_this.rootNode = _this.root[0]; //'root' is the array containing all of our data, use it to recursively find the current folder based on the path								
                _this.nodes = _this.rootNode.children;  //The children of the current folder are displayed				
                
                //Get staff
                let s = response[2] || [];														
				for (k = 0; k < s.length; k++) {
					let b = s[k];					
					_this.staff.push(b);
                }

				//Get users
                let u = response[1] || [];														
				for (j = 0; j < u.length; j++) {
					let a = u[j];
					_this.$set(a, 'deleting', false);
					a.iamDisplay = _this.getStaff(a.iam);
					a.salesDisplay = _this.getStaff(a.sales);					
					_this.users.push(a);					
                }

				_this.loading = false;
			});
		},		
		edit: function(user) {
			this.editing = true;
			this.user = user;
		},
		nextPage: function() {
            if (this.pageNumber < this.pageCount) {
                this.pageNumber++;
            }
        },
        prevPage: function() {
            if (this.pageNumber > 0) {
                this.pageNumber--;
            }
        },
		addNew: function() {
			var u = Object.assign({}, userDefault);
			this.edit(u);			
		},
		del: function(user) {
			db.collection('users').doc(user.id).delete().then(() => {
				if (user.logo > '') { //If there's a logo, delete the logo file
					storage.child('users/'+user.id).delete();
				}
				//Update the current nodes collection
				let j = this.users.findIndex(f => {
					return f.id == user.id;
				});
				this.users.splice(j, 1);	
				this.$root.setAlert(true, 'Account deleted successfully', 'alert-success');			
			});
		},
		getStaff: function(arr) {
			var tmp = [];			
			for (i = 0; i < arr.length; i++) {
				let r = this.staff.find(s => {
					return s.id == arr[i].staffId;
				}) || {name: 'Staff not found'};
				if (r) {
					tmp.push(r.name);
				}
			}
			if (!tmp.length) {
				tmp[0] = 'Not assigned';
			}
			return tmp.join(', ');
		},
		resetSearch: function() {
            //Trigger update of filtered  by adding a space - then resetting the search to what it was
            var s = this.search;    
            this.search = this.search + ' '; 
            this.search = s;
        }
	},
	computed: {
		filtered: function() {
            var s = this.search.toLowerCase();
            return this.users.filter(a => {                
               return a.displayName.toLowerCase().includes(s) || 
                a.iamDisplay.toLowerCase().includes(s) ||
                a.salesDisplay.toLowerCase().includes(s);
            });
        },
        pageCount: function() {
            let l = this.filtered.length;
            let s = this.size;
            var c = Math.ceil(l/s);            
            return c;
        },
        paginatedData: function() {            
            if (this.pageNumber >= this.pageCount) {                
                this.pageNumber = 0;
            }
            const start = this.pageNumber * this.size;            
            const end = start + this.size;
            return this.filtered.slice(start, end);
        } 	
	},
	mounted() {
		this.getData();	
        var _this = this;        
        eventBus.$on('update-user', function(k, v) {			
			var i = _this.users.findIndex(a => {
                return a.id == k;
            });                        
            if (i == -1) {
                _this.users.unshift(v);            
            } else {
				v.iamDisplay = _this.getStaff(v.iam);
				v.salesDisplay = _this.getStaff(v.sales);
				_this.users[i] = v;				
            }
            //Trigger update of filtered by adding a space - then resetting the search to what it was
            var s = _this.search;
            _this.search = _this.search + ' '; 
            _this.search = s;
        });     
	},
	watch: {    
		activeNode: function(old) {
			this.editing = false;
		}
  	}
}
</script>
<style>
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
	padding: 5px 0px 0px 10px;	
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