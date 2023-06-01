<template>
	<div id="node-edit" v-on:keyup.enter="update()" :class="{'edit-show': $parent.editing, 'edit-hide': !$parent.editing}">
		<div class="edit-container" >			
			<div class="title">
				<!-- Title for link, folder and file -->
				<span v-if="node.type != 'files'" class="h4">{{title}} properties</span>
				<!-- Title for batch upload -->
				<span v-if="node.type == 'files'" class="h4">Batch upload</span>
				<i class="close pull-right fa far fa-times" v-on:click="close()"></i>
			</div>
			<!-- Delete non-new nodes -->
			<button v-if="!deleteConfirm" type="button" class="btn btn-sm btn-danger" :disabled="deleting" v-on:click="deleteConfirm = true;">
				<i class="fas fa-trash-alt"></i> 
				<span v-if="!deleting">Delete {{node.type}}</span>
				<span v-if="deleting">Deleting...</span>
			</button>
			<div v-else>
				<strong>Are you sure</strong>
				<button type="button" class="btn btn-sm btn-success" :disabled="deleting" v-on:click="del(node)">
					<i class="fas fa-check"></i> 
					<span v-if="!deleting">Yes, delete</span>
					<span v-if="deleting">Deleting...</span>				
				</button>
				<button type="button" class="btn btn-sm btn-danger" :disabled="deleting" v-on:click="deleteConfirm = false;">
					<i class="fas fa-ban"></i> No, do not delete
				</button>
			</div>
			
			<hr />
			<!-- Node name -->
			<div class="form-group" v-if="node.type != 'files'"> <!-- Hide the name field when we're doing a multi-file upload -->
				<label>Name</label>
				<input class="form-control" :placeholder="title +' name'" v-model="nodeCopy.name">					
			</div>
			<!-- Show the URL field for links -->
			<div class="form-group" v-if="node.type == 'link'">
				<div>
					<label>URL</label>
					<div class="float-right">
						<a class="btn btn-sm btn-info" role="button" :href="nodeCopy.url" target="_blank">
							<i class="fas fa-external-link-square"></i>
							<span>Open URL</span>			
						</a>					
					</div>	
				</div>
				<input class="form-control" placeholder="http://example.com" v-model="nodeCopy.url">					
			</div>
			<!-- Show the filename for existing files -->
			<div class="form-group" v-if="node.type == 'file'">
				<label>File</label> 
				<div class="float-right">
					<button type="button" class="btn btn-sm btn-warning" 
						v-on:click="$parent.modalOpen()"
						data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#fileReplace">
						<i class="fas fa-repeat-alt"></i>
						<span>Replace</span>			
					</button>
					<button type="button" class="btn btn-sm btn-info" v-on:click="download()">
						<i class="fas fa-download"></i>
						<span>Download</span>			
					</button>
				</div>
				<div><i class="fa-fw" :class="$parent.fileClass(node)" ></i> {{node.filename}}</div>	
				<hr />				
				<div>
					<span id="fileurl" style="display: none">{{node.url}}</span>
					<button type="button" v-on:click="copyUrl()" class="btn btn-sm btn-primary"><i class="fas fa-external-link-alt"></i> Copy direct URL</button>
				</div>		
			</div>			
			<button type="button" :disabled="saving || nodeCopy.name.trim() == '' || node.name.trim() == nodeCopy.name.trim() " class="btn btn-sm btn-success" v-on:click="update()">
				<i class="fas fa-save"></i> <span v-if="!saving">Save</span><span v-else>Saving</span>			
			</button>			
		</div>		
	</div>
</template>
<script>
module.exports = {
	props: {
        node: {
            type: Object,
            required: true
		}
	},
	data: function() {
		return {
			deleting: false,
			deleteConfirm: false,
			overwrite: '',
			nodeCopy: {id: '', name: '', type: ''},
			nodeData: this.node,
			file: {},			
			saving: false
		}
	},
	methods: {	
		copyUrl() {
			var $temp = $("<input>");
  			$("body").append($temp);
  			$temp.val($("#fileurl").text()).select();
  			document.execCommand("copy");
  			$temp.remove();
			this.$root.setAlert(true, 'URL copied to the clipboard', 'alert-success');		
		},
		update: function() {
			if (this.nodeCopy.name.trim() == '') {
				return;
			}
			var params = {};

			params.name = this.$parent.testName(this.nodeCopy, 0);
			
			switch (this.node.type) {
				case 'folder': 
					//params.name = this.nodeCopy.name;					
				break;
				case 'file':
					//params.name = this.nodeCopy.name;
				break;
				case 'link':
					//params.name = this.nodeCopy.name;
					params.url = this.nodeCopy.url;
				break;
			}

			//Update firebase entry
			db.collection(this.$parent.manage.database).doc(this.node.id).update(params).then(() => {
				//Update original object with the passed-in parameters
				for (var key in params) {
    				if (params.hasOwnProperty(key)) {
						this.$parent.activeNode[key] = params[key];
					}
				}				
				this.$parent.editing = false;
				this.$root.setAlert(true, this.title+' updated successfully', 'alert-success');		
    		});
		},
		del: function(node) {
			this.deleting = true;	
			this.$parent.del(node);
			this.close();
		},
		close: function() {
			this.$parent.editing = false;
			this.deleting = false;
			this.deleteConfirm = false;
			this.file = {};
			this.saving = false;
			this.nodeCopy = {};
		},
		download: function() {		
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
		}
	},
	computed: {
		title: function() {
			if (this.$parent.activeNode.type > '') {
				return this.nodeCopy.type.charAt(0).toUpperCase() + this.nodeCopy.type.slice(1);
			}
		}
	},
	watch: {
		node: function(val) {			
			this.nodeCopy = Object.assign({}, val);
		},
		'$parent.editing': function() {
			this.nodeCopy = Object.assign({}, this.node);
		}
	},
	mounted: function () {
		
	}
}
</script>
<style>
#node-edit.edit-hide {
	visibility: hidden;
  	transition: visibility 0s 0.2s;
}

#node-edit.edit-show {
	visibility: visible;
	transition: visibility 0s 0s;
}

#node-edit.edit-show .edit-container {
	transform: translate3d(0, 0, 0);
  	transition-delay: 0s;
}

#node-edit .edit-container {
	padding: 5px 10px;
	position: fixed;
  	height: 100%;
  	top: 56px;
  	transition: transform 0.1s 0.1s;
  	right: 0;
	transform: translate3d(100%, 0, 0);
	box-shadow: -5px 0 15px -5px rgba(51, 51, 51, 0.3);
	height: 100%; 
	background-color: #FFF;
	width: 420px;
}

#node-edit .title {
	padding-bottom: 5px;
}

#node-edit hr {	
	width: 100%;
	margin: 10px 0px;
}

label {
	font-weight: bold;
}

</style>