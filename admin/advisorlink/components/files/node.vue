<template>
	<div v-on:click="setActiveNode(node)" class="node" :key="node.sort" :class="node.direction">		
		<div :class="{'on-clipboard': onClipboard || deleting}">
			<span class="name folder" v-on:click.stop="openFolder(node.name)" v-if="node.type == 'folder'">
				<span class="fa-stack">
					<i class="fas fa-stack-2x fa-folder"></i>
					<i v-if="node.children.length > 0 && !node.pasting" class="fas fa-plus fa-stack-1x"></i>					
					<i v-if="node.pasting || deleting" class="fas fa-stack-1x fa-circle-notch fa-spin"></i>
				</span>
				<span class="nodename">{{node.name}}</span>
			</span>			
			<span class="name" v-if="node.type == 'file'"><i class="fa-fw fa-lg" :class="fileClass(node)"></i>
				<span class="nodename">{{fileName}}</span>    			
			</span>
			<span class="name" v-if="node.type == 'link'"><i class="fa-fw fa-lg fas fa-link" ></i>
				<span class="nodename">{{node.name}}</span>
			</span>
			<div class="float-right">
				<i v-if="index > 0" :class="{'last-node': index == $parent.nodes.length - 1}" v-on:click="sort(node, 'up')" class="fas fa-fw fa-angle-up"></i>
				<i v-if="index != $parent.nodes.length - 1" v-on:click="sort(node, 'down')" class="fas fa-fw fa-angle-down"></i>
				<button :disabled="onClipboard" v-on:click="$parent.activeNode = node" class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					Actions
				</button>				
				<div class="dropdown-menu">
					<span class="dropdown-item" v-on:click="$parent.edit(node)">Edit <i class="fad fa-fw pt-1 fa-edit float-right"></i></span>					
					<span class="dropdown-item" v-on:click="$parent.toClipboard('copy')">Copy <i class="fad fa-fw pt-1 fa-copy float-right"></i></span>
					<span v-if="node.type == 'file'" class="dropdown-item" v-on:click="download(node)">Download <i class="fad fa-fw pt-1 fa-download float-right"></i></span>
					<span v-if="node.type == 'link'" class="dropdown-item" v-on:click="openURL(node)">Open URL<i class="fad fa-fw pt-1 fa-external-link-alt float-right"></i></span>
				</div>
			</div>
		</div>			
	</div>
</template>
<script>
module.exports = {    
    props: {
        node: {
            type: Object,
            required: true
        },
        index: {
            type: Number,
            required: true
        }
	},
	data: function() {
		return {
			
		}	
	},
	methods: {
		setActiveNode: function(node) {
			this.$parent.activeNode = node;			
		},
		fileClass: function(node) {
			return this.$parent.fileClass(node);
		},
		sort: function(node, direction) {
			if (!this.onClipboard) {
				this.$parent.sort(node, direction);
			}
		},
		openFolder: function(name) {
			if (!this.onClipboard && !this.deleting) {
				this.$parent.openFolder(name + '/');
			}
		},
		download: function(node) {		
			var _this = this;	
			var xhr = new XMLHttpRequest();
			xhr.responseType = 'blob';
			xhr.onload = function(event) {				
				var blob = xhr.response;				
				var a = document.createElement('a');
   				a.href = window.URL.createObjectURL(blob);
				a.download = node.filename;
				a.dispatchEvent(new MouseEvent('click'));				
			};							
			xhr.open('GET', node.url);
			xhr.send();	
		},
		openURL: function(node) {
			var a = document.createElement('a');
			a.href = node.url;	
			a.target = "_blank";   		
			a.dispatchEvent(new MouseEvent('click'));
		}
	},
	computed: {
		onClipboard: function() {
			return this.node.id == this.$parent.clipboard.id;
		},
		deleting: function() {
			return this.node.id == this.$parent.deleting.id;
		},
		fileName: function() {
			var n = this.node.filename;
			if (this.node.name > '') {
				n = this.node.name;
			}
			return n;
		}
	},
	mounted: function() {
		var _this = this;  
		eventBus.$on('update-paste', function() {
			_this.node.pasting = false;
		});     		
	}
}
</script>
<style>
.btn-sm {
	line-height: 1.0;
}
.last-node {
	margin-right: 1.5rem;
}
.name.folder:hover {
	text-decoration: none;
}

.nodename {
	display: inline-block;
	max-width: 900px;
	vertical-align: top;
	word-wrap: break-word;
}

.name .fa-stack {
	
}
</style>