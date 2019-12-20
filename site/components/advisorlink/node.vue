<template>
    <li :class="{'hidden': !visible}">		
        <div class="node">
            <div>
                <span>
                    <span class="name folder" v-on:click.stop="collapse()" v-show="node.type == 'folder'">
                        <span class="fa-stack">
							<i class="fas fa-stack-2x fa-folder"></i>
							<i v-show="node.children.length > 0 && !collapsed" class="fas fa-minus fa-stack-1x"></i>		
							<i v-show="node.children.length > 0 && collapsed" class="fas fa-plus fa-stack-1x"></i>
                        </span>
						<span v-html="$options.filters.userHighlight(node.name, query)"></span>								
                    </span>                    
                    <span v-if="!ie11" class="name" v-show="node.type == 'file'"><a v-on:click.prevent="download()" href="#"><i class="fa-fw fa-lg" :class="fileClass(node)"></i>
					<span v-html="$options.filters.userHighlight(fileName, query)"></span>
					</a></span>    			
					<span v-if="ie11" class="name" v-show="node.type == 'file'"><a :download="node.filename" :href="node.url" target="_blank"><i class="fa-fw fa-lg" :class="fileClass(node)"></i>
					<span v-html="$options.filters.userHighlight(fileName, query)"></span>
					</a></span>    			
                    <span class="name" v-show="node.type == 'link'"><a :href="node.url" target="_blank"><i class="fa-fw fa-lg fas fa-link" ></i>
					<span v-html="$options.filters.userHighlight(node.name, query)"></span>
					</a></span>					
                </span>
            </div>	
            <ol v-show="!collapsed && tempNode.children.length > 0">                                   
                <node v-for="(n, i) in tempNode.children" :node="n" :showall="showall" :user="user" :key="n.id" :query="query"></node>		                
            </ol>			
        </div>
    </li>
</template>
<script>
module.exports = {    
	components: {
		node: httpVueLoader('site/components/advisorlink/node.vue')	
	},
    props: {
        node: {
            type: Object,
            required: true
        },
		user: {
			type: Object,
			required: true
		},
		query: {
			type: String
		},
		showall: {
			type: Boolean
		}
	},
	data: function() {
		return {
			collapsed: true,
			tempNode: Object.assign({}, this.node),
			ie11: ie11	
		}	
	},
	methods: {		
		collapse: function() {
			this.collapsed = !this.collapsed;			
		},
		fileClass: function(node) {
			return this.$parent.fileClass(node);
		},
		nodeUpdate: function(node) {
			this.$parent.nodeUpdate(node);			
		},
		bubbleStatus: function(status) {					
			this.tempNode.some = status;
			if (this.tempNode.parent.id != 'root') {
				for (var i = 0; i < this.tempNode.children.length; i++) {						
					if (this.tempNode.children[i].some != 'none' && status == 'none') {
						return;
					}
				}
				this.$parent.bubbleStatus(status);
			}
		},
		download: function() {		
			console.log(this.node);
			var _this = this;	
			var xhr = new XMLHttpRequest();
			xhr.responseType = 'blob';
			xhr.onload = function(event) {				
				var blob = xhr.response;				
				var a = document.createElement('a');
   				a.href = window.URL.createObjectURL(blob);
				a.download = _this.node.filename;
				a.dispatchEvent(new MouseEvent('click'));				
			};							
			xhr.open('GET', _this.node.url);
			xhr.send();				
		},
		bubbleVisibility: function() {			
			this.collapsed = false;						
			this.$parent.bubbleVisibility();
		}
	},
	watch: {
		query: function(newVal, oldVal) {	
			var item = this.node.name;
			if (this.node.type == 'file') {
				item = this.fileName;
			}
			if (newVal.trim() == '') {
				this.collapsed = true;
				return;
			}
			if (newVal.trim().length >= 3 || newVal.trim().length <= 3 && oldVal.trim().length > 3) {			
				var invalid = /[°"§%()\[\]{}=\\?´`'#<>|,;.:+_]+/g;	
				var repl = this.query.trim().replace(invalid, "");
    			var check = new RegExp(repl, "ig");
				if (check.test(item) === true) {							
           			this.bubbleVisibility();    		
				} else {
					this.collapsed = true;
				}
			} else {
				this.collapsed = true;
			}			
		}	
	},
	mounted: function() {		
		this.$on('showMe', function() {	
			this.bubbleVisibility();				        
		});				
	},
	computed: {
        visible: function() {
			if (this.showall === true) {
				return true;
			} else {
				return this.hasAccess != 'none' || this.tempNode.some != 'none';			
			}
		},
		fileName: function() {
			var n = this.node.filename;
			if (this.node.name > '') {
				n = this.node.name;
			}
			return n;
		},		
		hasAccess: function() {			
			var _this = this;
			var out = '';
			if (this.node.type == 'folder' && this.node.children.length) { //Folders with children, we'll check the children to see if they're the same, or different and deal with the parent accordingly
				var children = this.node.children;
				var status = this.user.access.findIndex(function(s) {return s == _this.node.id});
				if (children.length) {
					//What's the inclusion status of the first child?
					var c1 = this.user.access.includes(this.node.children[0].id);
					var diff = false;  //Assume everything is the same for now
					for (var i = 0; i < this.node.children.length; i++) {
						//If the inclusion status of any of the other children is different from the first child's inclusion status, break
						if (this.user.access.includes(this.node.children[i].id) != c1) {
							diff = true;
							break;
						}
					}
					if (diff) { //The inclusion status of the children are different, remove access for the parent
						if (status != -1) {
							this.user.access.splice(status, 1);
						}											
						out = 'some';
					} else { //The inclusion status of the children are the same
						if (c1) {
							if (status == -1) { //If the parent element doesn't have access, set it as all children have access
								this.user.access.push(this.node.id);
							}
							out = 'all';
						} else { //If the parent node has access, remove it as no children have access
							if (status != -1) {
								this.user.access.splice(status, 1);
							}														
							out = 'none';
						}
					}
				}				
			} else { //Not a folder, either assigned or not
				if (this.user.access.includes(this.node.id)) {
					out = 'all';
				} else {
					out = 'none';
				}
			}
			this.bubbleStatus(out);
			return out;
		}
	}
}
</script>
<style scoped>
.btn-sm {
	line-height: 1.0;
}
.hidden {display: none;}
</style>