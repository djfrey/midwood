<template>
	<div class="clipboard">
		<strong>Clipboard</strong>
		<div class="clipboard-contents pr-2">
			<span style="color: #999" v-if="!$parent.clipboard.id">The clipboard is empty</span>
			<span v-if="$parent.clipboard.type == 'folder'"><i class="fas fa-fw fa-lg fa-folder"></i> {{$parent.clipboard.name}}</a></span>
			<span v-if="$parent.clipboard.type == 'file'"><i class="fa-fw" ng-class="getFileClass(clipboard)" ></i> {{$parent.clipboard.name}}</a></span>      			
			<span v-if="$parent.clipboard.type == 'link'"><i class="fa-fw fa-lg far fa-link" ></i> {{$parent.clipboard.name}}</a></span>    					
			<button type="button" uib-tooltip="Paste" tooltip-placement="left" class="btn btn-sm btn-primary" :disabled="$parent.pasting > 0 || !$parent.clipboard.id" v-on:click="paste()"><i class="fas fa-fw fa-paste"></i> Paste</button>
			<button type="button" uib-tooltip="Clear&nbsp;clipboard" :disabled="!$parent.clipboard.id" tooltip-placement="right" class="btn btn-sm btn-warning" :disabled="pasting" v-on:click="clear()"><i class="fas fa-fw fa-ban"></i> Clear</button>	
		</div>		
	</div>
</template>
<script>
module.exports = {    
    data: function() {
        return {		
			pasting: 0
		}
	},
	methods: {
		clear: function() {
			this.$parent.clipboard = {};
			this.$parent.clipboardAction = '';
		},
		paste: function() {
			var node = JSON.parse(JSON.stringify(this.$parent.clipboard));			
			var _this = this;
			//Delete properties we don't want going in to the db			
			delete node.children;
			delete node.direction;
			node.parent = db.collection(this.$parent.manage.database).doc(this.$parent.rootNode.id);
			node.name = this.$parent.testName(node, 0);
			node.sort = 0; //Make sure this is the first node
			
			db.collection(this.$parent.manage.database).add(node).then(function(response) {
				//Update sort on all other nodes in this group
				if (_this.$parent.nodes.length) {
					var p = [];
					for (var i = 0; i < _this.$parent.nodes.length; i++) {
						let s = Number(_this.$parent.nodes[i].sort) + 1;					
						p.push(db.collection(_this.$parent.manage.database).doc(_this.$parent.nodes[i].id).update({sort: s}));
						_this.$parent.nodes[i].sort = s;
					}
					Promise.all(p);
				}				
				node.id = response.id;
				node.direction = '';
				if (node.type == 'file') {
					node.url = _this.copyFile(node.url, response.id);				
				}				
				node.parent = {id: _this.$parent.rootNode.id};	
				node.pasting = true;			
				_this.$parent.nodes.unshift(node);				
				_this.pasteChildren(_this.$parent.clipboard, node);				
				_this.clear();
			});						
		},
		pasteChildren: function(source, node) {
			var _this = this;			
			var p = [];			
			var n = [];
			node.children = node.children || [];
			for (var i = 0; i < source.children.length; i++) {						
				this.pasting++;
				var nodeNew = Object.assign({}, source.children[i]);								
				delete nodeNew.children;								
				delete nodeNew.direction;
				nodeNew.parent = db.collection(this.$parent.manage.database).doc(node.id);				
				n.push(nodeNew);
				p.push(db.collection(this.$parent.manage.database).add(nodeNew));
			}
			Promise.all(p).then(function(response) {									
				for (var j = 0; j < response.length; j++) {	
					nodeNew = n[j];										
					nodeNew.id = response[j].id;
					nodeNew.direction = '';			
					if (nodeNew.type == 'file') {							
						_this.copyFile(nodeNew);
					}
					nodeNew.parent = node;
					node.children.push(nodeNew);
					_this.pasting--;
					if (source.children[j].children.length > 0) {						
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
				var blob = xhr.response;
				var status = xhr.status;
				if (status == '200') {						
					storage.child(_this.$parent.manage.storage+'/'+node.id).put(blob).then(function(response) {
						response.ref.getDownloadURL().then(function(url) {
							db.collection(_this.$parent.manage.database).doc(node.id).update({url: url}).then(() => {	
								node.url = url;							
							});
						});
					});
				} 
			}
			xhr.onerror = function(e) {
				node.url = '';
				console.log(e);
			}
			//Get the file from the old node
			xhr.open('GET', node.url, true);			
			xhr.send();	
		}
	},
	watch: {
		pasting: function(newVal, oldVal) {									
			if (oldVal > newVal) {
				eventBus.$emit('update-paste');
			}
		}
	}
}
</script>
<style>
.clipboard {
	
}
.clipboard-contents {
	display: inline-block;
	border: 1px solid #DDD;
	border-radius: 5px;	
	padding: 2px 10px;
}
.clipboard-contents span {
	display: inline-block;
	margin-top: 3px;
	float: left;
	width: 290px;
  	white-space: nowrap;
  	overflow: hidden;
  	text-overflow: ellipsis;
}
</style>