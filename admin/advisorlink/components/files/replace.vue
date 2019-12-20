<template>
    <div class="modal fade" tabindex="-1" role="dialog" id="fileReplace">        
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-repeat-alt"></i> Replace a file</h5>
                        <button type="button" class="close" v-on:click="close()" aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                </div>
                <div class="modal-body">                    
                    <div class="container-fluid">
                        <div class="custom-file mb-3">
                            <input type="file" :key="key" class="custom-file-input" ref="fileReplace" id="inputGroupReplace" v-on:change="setFile">
                            <label class="custom-file-label" for="inputGroupReplace">Choose the replacement file</label>
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
            this.files = [];
            this.fileNames = [];   
            for (i = 0; i < this.$refs.fileReplace.files.length; i++) {
                this.files.push(this.$refs.fileReplace.files[i]);
                this.fileNames.push(this.$refs.fileReplace.files[i].name);
            }
            this.key = this.key + 1;
        },
        removeFile: function(i) {
            this.files = [];
            this.fileNames = [];
            this.key = this.key + 1;
        },
        close: function() {
            if (this.uploading) {
                return;
            }            
            this.files = [];     
            this.fileNames = [];                              
            $('#fileReplace').modal('hide');  
        },
        testName: function(node, count) {
            return this.$parent.testName(node, count);
        },
        upload: function() {
            this.uploading = true;        
            var node = this.$parent.activeNode;
            var _this = this;

            var p = []; //the array of file upload promises
            var u = []; //The array of URL promises
            var s = []; //The array of sort promises

            p.push(storage.child(this.$parent.manage.storage+'/'+node.id).put(this.files[0])); //Add the files to storage
            Promise.all(p).then(fileResult => {
                for (l = 0; l < fileResult.length; l++) {
                    var ref = fileResult[l].ref; //Get the result from the file addition                        
                    u.push(ref.getDownloadURL()); //Get the download URL of the added file  
                }
                Promise.all(u).then(urlResult => {                    
                    for (m = 0; m < urlResult.length; m++) {                        
                        node.url = urlResult[m]; //Add the URL to the node                        
                        node.filename = _this.files[0].name; //The file name                    
                        node.mime = _this.files[0].type; //The MIME type
                        s.push(db.collection(this.$parent.manage.database).doc(node.id).update({
                            url: urlResult[m],                             
                            filename: node.filename, 
                            mime: node.mime
                        })); //Update the file values
                    }
                    _this.uploading = false;
                    _this.files = [];   
                    _this.fileNames = [];
                    _this.close();
                    this.$root.setAlert(true, 'File replaced successfully', 'alert-success');	        
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