<template>
	<div class="node">					
		<div class="border-bottom" :class="{'assigned': hasAccess != 'none' || tempNode.some != 'none'}">
			<span class="name folder" v-on:click.stop="collapsed = !collapsed" v-show="node.type == 'folder'">
				<span class="fa-stack">
					<i class="fas fa-stack-2x fa-folder"></i>
					<i v-show="node.children.length > 0 && !collapsed" class="fas fa-minus fa-stack-1x"></i>		
					<i v-show="node.children.length > 0 && collapsed" class="fas fa-plus fa-stack-1x"></i>						
				</span> {{node.name}}
			</span>
			<span class="name" v-show="node.type == 'file'"><i class="fa-fw fa-lg" :class="fileClass(node)"></i>{{fileName}}</span>    			
			<span class="name" v-show="node.type == 'link'"><i class="fa-fw fa-lg fas fa-link" ></i>{{node.name}}</span>
			
			<span v-show="hasAccess == 'all'"><i class="text-success fas fa-star"></i></span>
			<span v-show="hasAccess == 'some' || (hasAccess == 'none' && tempNode.some != 'none')"><i class="text-success fas fa-star-half-alt"></i></span>			
			
			<div class="float-right">
				<div class="custom-control custom-switch">
  					<input type="checkbox" class="custom-control-input" :key="node.id" :value="node.id" v-model="user.access" v-on:click="nodeUpdate(node)" :id="'assign_'+node.id">
  					<label class="custom-control-label" :for="'assign_'+node.id"></label>
				</div>
			</div>
		</div>	
		<ol v-show="!collapsed">
			<!-- The nodes, activeNode is set on click -->
			<li v-show="tempNode.children.length > 0" v-for="(n, i) in tempNode.children">
				<node :node="n" :user="user"></node>		
			</li>			
		</ol>			
	</div>
</template>
<script>
module.exports = {    
	components: {
		node: httpVueLoader('components/users/node.vue')	
	},
    props: {
        node: {
            type: Object,
            required: true
        },
		user: {
			type: Object,
			required: true
		}
	},
	data: function() {
		return {
			collapsed: true,
			tempNode: Object.assign({}, this.node)			
		}	
	},
	methods: {		
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
		}
	},
	computed: {	
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
				var status = this.user.access.findIndex(s => {return s == this.node.id});
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
<style>
.btn-sm {
	line-height: 1.0;
}
.name {
	max-width: 93%;
	display: inline-block;
}
.assigned {
	background-color: #d4edda;
	border-bottom-color: #28a745 !important;
}

.assigned > .name, .assigned > .name i {
	color: #155724;	
}
</style>