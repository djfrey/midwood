<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="fileUpload">        
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-file-upload"></i> File upload</h5>
                        <button type="button" class="close" v-on:click="close()" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                </div>
                <div class="modal-body">                    
                    <div class="container-fluid">
                        <div class="custom-file mb-3">
                            <input type="file" :key="key" multiple class="custom-file-input" ref="fileUpload" id="inputGroupFile" v-on:change="setFile">
                            <label class="custom-file-label" for="inputGroupFile">Choose one or more files to upload</label>
                        </div>
                        <div class="row pb-2 pt-2" :class="{'border-top': i > 0}" v-if="files.length" v-for="(f, i) in files">
                            <div class="col input-group">
                                <input type="text" v-model="fileNames[i]" class="form-control" placeholder="File display name">
                                <div class="input-group-append">
                                    <button type="button" class="btn btn-sm btn-danger" :disabled="uploading" v-on:click="removeFile(i)"><i class="fas fa-trash"></i> Remove</button>                            
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" v-on:click="upload()" :disabled="!files.length || uploading">
                        <span v-if="!uploading"><i class="fas fa-file-upload"></i> Upload</span>
                        <span v-if="uploading"> <i class="fas fa-circle-notch fa-spin"></i> Uploading...</span>
                    </button>
                    <button type="button" class="btn btn-secondary" v-on:click="close()">Close</button>                
                </div>                
            </div>
        </div>
    </div>
</template>
<script>
module.exports = {	
    data: function() {
        return {
            files: [],
            fileNames: [],
            uploading: false,
            key: ''
        }
    },
    methods: {
        setFile: function() {            
            for (i = 0; i < this.$refs.fileUpload.files.length; i++) {
                this.files.push(this.$refs.fileUpload.files[i]);
                this.fileNames.push(this.$refs.fileUpload.files[i].name);
            }
            this.key = this.key + 1;
        },
        removeFile: function(i) {
            this.files.splice(i, 1);
            this.fileNames.splice(i, 1);
            this.key = this.key + 1;
        },
        close: function() {
            if (this.uploading) {
                return;
            }            
            this.files = [];     
            this.fileNames = [];                              
            $('#fileUpload').modal('hide');  
        },
        testName: function(node, count) {
            return this.$parent.testName(node, count);
        },
        upload: function() {    
            this.uploading = true;        
            var p = []; //the array of file upload promises
            var n = []; //The array of new node promises
            var u = []; //The array of URL promises
            var s = []; //The array of sort promises
            var nodes = []; //The array of nodes we will create
            var parent = db.collection(this.$parent.manage.database).doc(this.$parent.rootNode.id); //The parent of all added nodes            
            var _this = this;

            //Set up the nodes array to hold the node data for the filenodes we're making
            for (i = 0; i < this.files.length; i++) {
                var node = {                                   
                    url: '', //Blank URL for now, we'll set it when the promise resolves
                    type: 'file',                
                    name: '', //The user-inputted file name
                    filename: this.files[i].name, //The file name                    
                    mime: _this.files[i].type, //The MIME type
                    parent: parent,                                            
                    sort: i
                    //direction: '',
                    //children: []
                };
                node.name = this.testName(node, 0);        
                nodes.push(node);                                
            }
            //Add the nodes to firebase
            for (j = 0; j < nodes.length; j++) {
                n.push(db.collection(this.$parent.manage.database).add(nodes[j])); //Add the node to the database                
            }
            Promise.all(n).then(nodeResult => { //Resolve the node additions
                for (k = 0; k < nodeResult.length; k++) { 
                    p.push(storage.child(this.$parent.manage.storage+'/'+nodeResult[k].id).put(_this.files[k])); //Add the files to storage
                    nodes[k].id = nodeResult[k].id;                                        
                    nodes[k].direction = ''; //Add direction to only the model
                    nodes[k].children = []; //Add children only to the model
                    nodes[k].parent = {id: _this.$parent.rootNode.id}; //Reset parent to a JSON object instead of an object reference                    
                }
                Promise.all(p).then(fileResult => {
                    for (l = 0; l < fileResult.length; l++) {
                        var ref = fileResult[l].ref; //Get the result from the file addition                        
                        u.push(ref.getDownloadURL()); //Get the download URL of the added file  
                    }
                    Promise.all(u).then(urlResult => {                    
                        for (m = 0; m < urlResult.length; m++) {                        
                            nodes[m].url = urlResult[m]; //Add the URL to the node
                            s.push(db.collection(this.$parent.manage.database).doc(nodes[m].id).update({url: urlResult[m]})); //Update the URL value of the document                                                        
                        }
                        //Update sort
                        for (n = 0; n < _this.$parent.nodes.length; n++) {
                            //The existing nodes need to have the sort order pushed out by the number of new nodes added
                            let o = Number(_this.$parent.nodes[n].sort) + nodeResult.length;			
                            //Update the sort of the existing nodes	
                            s.push(db.collection(this.$parent.manage.database).doc(_this.$parent.nodes[n].id).update({sort: o}));
                            //Update the model
                            _this.$parent.nodes[n].sort = o;
                        }                        
                        //After sorting is resolved, prepend nodes to the existing nodes collection
                        Promise.all(s).then(sortResult => {
                            for (q = nodes.length - 1; q >= 0; q--) { 
                                _this.$parent.nodes.unshift(nodes[q]);              
                            }
                            _this.uploading = false;
                            _this.files = [];   
                            _this.fileNames = [];
                            _this.close();
                            this.$root.setAlert(true, 'File upload successful', 'alert-success');	        
                        });
                    });

                });
            });
        }
    }
}
</script>
<style>
    .modal-body {
        padding-left: 0;
        padding-right: 0;
        padding-bottom: 0.5rem;
    }    
</style>