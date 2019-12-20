<template>
    <div>
        <div class="row border-bottom pt-3 mb-2">
            <div class="col-9">
                <h3>
                    <i class="text-primary fas fa-pen-square"></i> 
                    <span v-if="!newUser">Editing account "{{$parent.user.displayName}}"</span>
                    <span v-else>Creating a new account</span>                    
                </h3>
            </div>
            <div class="col-3">
                <button type="button" v-on:click="resetUser();" class="mt-1 btn btn-primary float-right"><i class="fas fa-arrow-left"></i> Back to accounts</button>
            </div>
        </div>
        <div v-if="newUser" class="mb-2 alert alert-warning"><i class="fad fa-lg fa-exclamation-triangle"></i> Please enter and save the account's login information before continuing</div> 
        <div class="accordion" id="accordion">
            <div class="card">
                <div class="card-header" id="section1" :class="{'alert-success': testUpdateS1}">
                    <h4 class="float-left" v-on:click="collapse('collapseOne')"><i class="fas fa-fw fa-user"></i> <span>Login information</span></h4>
                    <button type="button" v-on:click="update('section1')" :disabled="!testUpdateS1" class="btn btn-success btn-sm float-right"><i class="fas fa-check"></i> Save</button>      
                </div>
                <div id="collapseOne" data-parent="#accordion" class="collapse show">
                    <div class="card-body">
                        <div class="row border-bottom pb-2 mb-2">
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 col-form-label">
                                        <label><strong>Login ID</strong></label>
                                    </div>			
                                    <div class="col-8">
                                        <input type="text" v-model="tempUser.userName" class="form-control" placeholder="User name">                         
                                    </div>                         
                                </div>
                                <div class="row" v-if="errors.userName">
                                    <div class="col">
                                        <div class="alert alert-danger"><i class="fas fa-lg fa-exclamation-circle"></i> {{errors.userName}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 col-form-label">
                                        <label><strong>Password</strong></label>
                                    </div>			
                                    <div class="col-8">
                                        <div class="form-control-plaintext">
                                            <span v-if="password.hidden">Password is hidden <button type="button" v-on:click="password.hidden = false;" class="btn btn-primary btn-sm float-right"><i class="fas fa-eye"></i> Show</button></span>
                                            <span v-if="!password.hidden">{{tempUser.password}} <button type="button" v-on:click="password.hidden = true;" class="btn btn-primary btn-sm float-right"><i class="fas fa-eye-slash"></i> Hide</button></span>
                                        </div>                                               
                                    </div>                         
                                </div>
                                <div class="row">
                                    <div class="col-4 col-form-label">
                                        <label><strong>Update password</strong></label>
                                    </div>			
                                    <div class="col-8">
                                        <input type="text" v-model="password.pass1" class="form-control mb-1" placeholder="New password">   
                                        <input type="text" v-model="password.pass2" class="form-control" placeholder="Verify password">                                                                    
                                    </div>
                                </div>
                                <div class="row mt-2" v-if="errors.password">
                                    <div class="col">
                                        <div class="alert alert-danger"><i class="fas fa-lg fa-exclamation-circle"></i> {{errors.password}}</div>
                                    </div>
                                </div>
                            </div>                
                        </div>
                        <div class="row">  
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 col-form-label">
                                        <label><strong>Display name</strong></label>
                                    </div>			
                                    <div class="col-8">
                                        <input type="text" v-model="tempUser.displayName" class="form-control" placeholder="Display name">     
                                    </div>                         
                                </div>
                                <div class="row" v-if="errors.displayName">
                                    <div class="col">
                                        <div class="alert alert-danger"><i class="fas fa-lg fa-exclamation-circle"></i> {{errors.displayName}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 col-form-label">
                                        <label><strong>Active?</strong></label>
                                    </div>			
                                    <div class="col-8">
                                        <div class="form-control-plaintext">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" v-model="tempUser.enabled" value="yes" id="userActiveYes" name="userActive" class="custom-control-input">
                                                <label class="custom-control-label" for="userActiveYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" v-model="tempUser.enabled" value="no" id="userActiveNo" name="userActive" class="custom-control-input">
                                                <label class="custom-control-label" for="userActiveNo">No</label>
                                            </div>
                                        </div>
                                    </div>                         
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="section2" :class="{'alert-success': testUpdateS2}">
                    <h4 class="float-left" :class="{'text-black-50': newUser}" v-on:click="collapse('collapseTwo')"><i class="fad fa-fw fa-user-cog"></i> <span>Account detail</span></h4>
                    <button type="button" v-on:click="update('section2')" :disabled="!testUpdateS2" :class="{'btn-secondary': newUser, 'btn-success': !newUser}" class="btn btn-sm float-right"><i class="fas fa-check"></i> Save</button>        
                </div>
                <div id="collapseTwo" data-parent="#accordion" class="collapse">
                    <div class="card-body">
                        <div class="row">            
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 col-form-label">
                                        <label><strong>Logo</strong></label>
                                    </div>			
                                    <div class="col-8">
                                        <div class="pb-2">
                                            <img v-if="tempUser.logo" class="img-fluid" :src="tempUser.logo">
                                            <span v-else>No logo set</span>
                                        </div>                                               
                                    </div>                         
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" accept=".bmp,.gif,.jpg,.jpeg,.png" class="custom-file-input" ref="fileUpload" id="logoFile"  v-on:change="setFile()">
                                                <label class="custom-file-label logo-file-label" for="logoFile">Choose a logo file</label>
                                            </div>
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-success" v-on:click="upload()" :disabled="toUpload.name == '' || uploading">
                                                    <span v-if="!uploading"><i class="fas fa-file-upload"></i> Upload</span>
                                                    <span v-if="uploading"> <i class="fas fa-circle-notch fa-spin"></i> Uploading...</span>
                                                </button>                                
                                            </div>
                                        </div>

                                    </div>		                    
                                </div> 
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-4 col-form-label">
                                        <label><strong>Show Bluerock disclaimer?</strong></label>
                                    </div>			
                                    <div class="col-8">
                                        <div class="form-control-plaintext">
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" v-model="tempUser.bluerock" value="yes" id="userbluerockYes" name="userbluerock" class="custom-control-input">
                                                <label class="custom-control-label" for="userbluerockYes">Yes</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" v-model="tempUser.bluerock" value="no" id="userbluerockNo" name="userbluerock" class="custom-control-input">
                                                <label class="custom-control-label" for="userbluerockNo">No</label>
                                            </div>
                                        </div>
                                    </div>                         
                                </div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="section3" :class="{'alert-success': testUpdateS3}">
                    <h4 class="float-left" :class="{'text-black-50': newUser}" v-on:click="collapse('collapseThree')"><i class="fad fa-fw fa-user-tag"></i> <span>Assigned staff</span></h4>
                    <button type="button" v-on:click="update('section3')" :disabled="!testUpdateS3" :class="{'btn-secondary': newUser, 'btn-success': !newUser}" class="btn btn-sm float-right"><i class="fas fa-check"></i> Save</button>     
                </div>
                <div id="collapseThree" data-parent="#accordion" class="collapse">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">                                
                                <div class="form-group row">
                                    <div class="col col-form-label">
                                        <label><strong>Account managers</strong></label>
                                    </div>			
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div v-if="tempUser.iam.length > 0" :class="{'border-bottom': k < tempUser.iam.length - 1}" v-for="(i, k) in tempUser.iam">
                                            <div class="form-control-plaintext">
                                                {{getStaff(i.staffId).name}}
                                                <button type="button" v-on:click="removeStaff(tempUser.iam, k)" class="btn btn-danger btn-sm float-right"><i class="fas fa-ban"></i> Remove</button>
                                            </div>
                                        </div>
                                        <div v-if="tempUser.iam.length == 0">
                                            <div class="form-control-plaintext">No account managers assigned</div>
                                        </div>                               
                                    </div>                                   
                                </div> 
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="input-group border-top pt-2 mt-2">
                                            <select v-model="iamSelect" class="custom-select">
                                                <option value="" selected>Choose...</option>
                                                <option v-if="!testStaff(tempUser.iam, i)" v-for="i in iamStaff" :value="i">{{i.name}}</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button :disabled="iamSelect == ''" type="button" v-on:click="addStaff('iam')" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Add</button>
                                            </div>
                                        </div>                                          
                                    </div>
                                </div>      
                            </div>
                            <div class="col-6">
                                <div class="form-group row">
                                    <div class="col col-form-label">
                                        <label><strong>Sales staff</strong></label>                    
                                    </div>			
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div v-if="tempUser.sales.length > 0" :class="{'border-bottom': k < tempUser.sales.length - 1}" v-for="(i, k) in tempUser.sales">
                                            <div class="form-control-plaintext">
                                                <div class="pb-2">
                                                    {{getStaff(i.staffId).name}} 
                                                    <button type="button" v-on:click="removeStaff(tempUser.sales, k)" class="btn btn-danger btn-sm float-right"><i class="fas fa-ban"></i> Remove</button>
                                                </div>
                                                <div>
                                                    <span v-if="i.states.length > 0">{{i.states.join(', ')}}</span>
                                                    <span v-if="i.states.length == 0">No states/regions assigned</span>
                                                    <button type="button" v-on:click="stateEdit(i);" data-toggle="modal" data-backdrop="static" data-keyboard="false" data-target="#stateSelect" class="btn btn-success btn-sm float-right"><i class="fas fa-pencil-alt"></i> Edit</button>
                                                </div>                            
                                            </div>
                                        </div>
                                        <div v-if="tempUser.sales.length == 0">
                                            <div class="form-control-plaintext">No sales staff assigned</div>
                                        </div>                                   
                                    </div>                         
                                </div>
                                <div class="form-group row">
                                    <div class="col">
                                        <div class="input-group border-top pt-2 mt-2">
                                            <select v-model="salesSelect" class="custom-select">
                                                <option value="" selected>Choose...</option>
                                                <option v-if="!testStaff(tempUser.sales, j)" v-for="j in salesStaff" :value="j">{{j.name}}</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button :disabled="salesSelect == ''" type="button" v-on:click="addStaff('sales')" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Add</button>
                                            </div>
                                        </div>                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="section4" :class="{'alert-success': testUpdateS4}">
                    <h4 class="float-left" :class="{'text-black-50': newUser}" v-on:click="collapse('collapseFour')"><i class="fas fa-fw fa-file-alt"></i> <span>Message</span></h4>
                    <button type="button" v-on:click="update('section4')" :disabled="!testUpdateS4" :class="{'btn-secondary': newUser, 'btn-success': !newUser}" class="btn btn-sm float-right"><i class="fas fa-check"></i> Save</button>    
                </div>
                <div id="collapseFour" data-parent="#accordion" class="collapse">
                    <div class="card-body">
                        <wysiwyg v-model="tempUser.message"></wysiwyg>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="section5" :class="{'alert-success': testUpdateS5}">
                    <h4 class="float-left" :class="{'text-black-50': newUser}" v-on:click="collapse('collapseFive')"><i class="fas fa-fw fa-file-search"></i> <span>File access</span></h4>
                    <button type="button" v-on:click="update('section5')" :disabled="!testUpdateS5" :class="{'btn-secondary': newUser, 'btn-success': !newUser}" class="btn btn-sm float-right"><i class="fas fa-check"></i> Save</button>    
                </div>
                <div id="collapseFive" data-parent="#accordion" class="collapse">
                    <div class="card-body">                        
                        <div class="border-bottom mb-1 pb-1">
                            <button type="button" v-on:click="tempUser.access = []" :disabled="tempUser.access.length == 0" class="btn btn-sm btn-danger"><i class="fas fa-ban"></i> Clear all access</button>                                
                        </div>
                        <div id="tree-root">	
                            <h3 v-if="loading"><i class="fas fa-circle-notch fa-spin"></i> Loading...</h3>				
                            <div v-else>
                                <ol>
                                    <!-- The nodes, activeNode is set on click -->
                                    <li v-show="nodes.length > 0" v-for="(n, i) in nodes">
                                        <node :node="n" :user="tempUser"></node>		
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
                    </div>
                </div>
            </div>
        </div>
        <state-select :data="activeStaff"></state-select>        
    </div>
</div>
</template>
<script>
const uploadDefault = {name: ''};
module.exports = {
	components: {
        stateSelect: httpVueLoader('./state.select.vue'),
        wysiwyg: httpVueLoader('./wysiwyg.vue'),
        node: httpVueLoader('./node.vue')
	},
	data: function() {
		return {
            nodes: this.$parent.nodes,
            newUser: this.$parent.user.id == '',
            access: {node: {}, action: false},
            loading: this.$parent.loading,
            tempUser: JSON.parse(JSON.stringify(this.$parent.user)),            
            salesStaff: this.$parent.staff.filter(s => {
                return s.role == 'sales'
            }),
            iamStaff: this.$parent.staff.filter(s => {
                return s.role == 'iam'
            }),
            salesSelect: '',
            iamSelect: '',
            activeStaff: {staffId: '', states: []},
            toUpload: uploadDefault, //Hold the logo file
            uploading: false,
            password: {hidden: true, pass1: '', pass2: ''},
            errors: {userName: false, displayName: false, password: false}
		}
	},
	methods: {	
        collapse: function(section) {
            if (this.newUser) {
                return;
            }
            $('#'+section).collapse('toggle');
        },
        setFile: function() {
            if (this.$refs.fileUpload.files.length > 0) {
                this.toUpload = this.$refs.fileUpload.files[0];            
                $('.logo-file-label').html(this.toUpload.name);
            } else {
                this.toUpload = uploadDefault;
                $('.logo-file-label').html('Choose a logo file');
            }
        },
        upload: function() {
            this.uploading = true;
            var p = [];
            var u = [];
            var s = [];
            var _this = this;

            p.push(storage.child('users/'+_this.$parent.user.id).put(_this.toUpload)); //Add the files to storage
            Promise.all(p).then(fileResult => {
                for (l = 0; l < fileResult.length; l++) {
                    var ref = fileResult[l].ref; //Get the result from the file addition                        
                    u.push(ref.getDownloadURL()); //Get the download URL of the added file  
                }
                Promise.all(u).then(urlResult => {                    
                    for (m = 0; m < urlResult.length; m++) {                        
                        _this.tempUser.logo = urlResult[m]; //Add the URL to the node
                        _this.$parent.user.logo = urlResult[m];                        
                        s.push(db.collection('users').doc(_this.$parent.user.id).update({logo: urlResult[m]})); //Update the URL value of the document                                                        
                    }
                    Promise.all(s);
                    this.uploading = false;
                    this.toUpload = uploadDefault;
                    _this.$root.setAlert(true, 'Logo updated successfully', 'alert-success');
                    this.finishUpdate(_this.$parent.user.id, null);
                });
            });
        },
        finishUpdate: function(id, section) {                        
            this.tempUser.id = id;    
            switch (section) {
                case 'section1':
                    var p = this.testValid('password');
                    this.$parent.user.userName = this.tempUser.userName;
                    this.$parent.user.displayName = this.tempUser.displayName;
                    if (p.valid && p.update) {
                        this.tempUser.password = this.password.pass2;
                        this.password = {hidden: true, pass1: '', pass2: ''};
                    }
                    this.$parent.user.password = this.tempUser.password;
                    this.$parent.user.enabled = this.tempUser.enabled;                    
                break;
                case 'section2':
                    this.$parent.user.bluerock = this.tempUser.bluerock;                    
                break;
                case 'section3':
                    this.$parent.user.iam = this.tempUser.iam;
                    this.$parent.user.sales = this.tempUser.sales;                    
                break;
                case 'section4':
                    this.$parent.user.message = this.tempUser.message;               
                break;
                case 'section5':
                    this.$parent.user.access = this.tempUser.access;                    
                break;
            }
            this.tempUser = JSON.parse(JSON.stringify(this.$parent.user));
            eventBus.$emit('update-user', id, this.tempUser);
        },  
        testValid: function(item) {
            var i = '';
            var update = false;
            var valid = true;
            var error = '';
            var out = {item: '', data: '', valid: true, update: false, error: ''};
            switch (item) {
                case 'userName':
                    i = this.tempUser.userName.trim();
                    update = (i > '' && i != this.$parent.user.userName);
                    var _this = this;
                    var exists = this.$parent.users.some(u => {
                        return u.userName.toLowerCase() == i.toLowerCase() && _this.$parent.user.userName.toLowerCase() != i.toLowerCase();
                    });
                    if (exists) {
                        valid = false;
                        error = 'That login ID is being used by another account';                    
                    }                    
                    if (i == '') {
                        valid = false;
                        error = 'Login ID is required';
                    }
                break;
                case 'displayName':
                    i = this.tempUser.displayName.trim();
                    update = (i > '' && i != this.$parent.user.displayName);
                    if (i == '') {
                        valid = false;
                        error = 'Display name is required';
                    }
                break;
                case 'password':
                    i = this.password.pass2.trim();
                    update = (i > '' && i != this.$parent.user.password && this.password.pass1.trim() == i);                   
                    if (this.tempUser.password == '' && (i == '' && this.password.pass1.trim() == '')) {
                        valid = false;
                        error = 'Please create a password';
                    } else if (this.password.pass1.trim() != i) {
                        valid = false;
                        error = 'Both passwords must match';
                    }
                break;
                case 'enabled': 
                    i = this.tempUser.enabled;
                    update = (i != this.$parent.user.enabled);
                break;
                case 'bluerock': 
                    i = this.tempUser.bluerock;
                    update = (i != this.$parent.user.bluerock);
                break;
                case 'iam':
                    i = this.tempUser.iam;
                    var a = Object.assign([], i);
                    var b = Object.assign([], this.$parent.user.iam);
                    a.sort();
                    b.sort();
                    if (a.length != b.length) {
                        update = true;
                    } else {
                        for (var j = 0; j < b.length; j++) {
                            if (JSON.stringify(a[j]) != JSON.stringify(b[j])) {
                                update = true;
                                break;                             
                            }
                        }
                    }              
                break;
                case 'sales':
                    i = this.tempUser.sales;                    
                    var a = Object.assign([], i);
                    var b =  Object.assign([], this.$parent.user.sales);
                    a.sort();
                    b.sort();
                    if (a.length != b.length) {                        
                        update = true;
                    } else {
                        for (var j = 0; j < b.length; j++) {                                                         
                            if (JSON.stringify(a[j]) != JSON.stringify(b[j])) {                                
                                update = true;
                                break;                              
                            }
                        }
                    }                     
                break;
                case 'message':
                    i = this.tempUser.message;
                    update = (i != this.$parent.user.message);
                break;
                case 'access':
                    i = this.tempUser.access;
                    var a = Object.assign([], i);
                    var b =  Object.assign([], this.$parent.user.access);
                    a.sort();
                    b.sort();
                    if (a.length != b.length) {                        
                        update = true;
                    } else {
                        for (var j = 0; j < b.length; j++) {                             
                            if (JSON.stringify(a[j]) != JSON.stringify(b[j])) {                                
                                update = true;
                                break;                              
                            }
                        }
                    }  
                break;
            }
            out.item = item;
            out.data = i;
            out.valid = valid;
            out.update = update;
            out.error = error;
            return out;
        },     
        update: function(section) {
            var error = false;
            var fields = [];
            var toUpdate = [];
            var _this = this;
            switch (section) {
                case 'section1':
                    fields.push(this.testValid('userName'),this.testValid('displayName'),this.testValid('password'),this.testValid('enabled'));                    
                break;
                case 'section2':
                    fields.push(this.testValid('bluerock'));
                break;
                case 'section3':
                    fields.push(this.testValid('iam'),this.testValid('sales'));
                break;
                case 'section4':
                    fields.push(this.testValid('message'));
                break;
                case 'section5':
                    fields.push(this.testValid('access'));
                break;
            }
            for (var i = 0; i < fields.length; i++) {
                this.errors[fields[i].item] = false;
                if (fields[i].update || this.newUser) { //If the field has been flagged to update, make sure it's valid - if it is, add to the update queue, otherwise, add an error
                    if (!fields[i].valid) {
                        this.errors[fields[i].item] = fields[i].error;
                        if (fields[i].item == 'userName') { //Reset username
                            this.tempUser.userName = this.$parent.user.userName;
                        }
                        error = true;
                    } else {                         
                        toUpdate.push(fields[i]);
                    }
                }
            }             
            if (!error) {
                if (this.newUser) { //This is the first user save, create a new user
                    //Not all of the user fields should be saved in the database, this variable holds the necessary fields
                    var newUserData = {
                        access: [],
                    	bluerock: "no",
	                    displayName: this.tempUser.displayName, 
	                    enabled: this.tempUser.enabled, 
	                    iam: [], 
	                    logo: "",
	                    message: "", 
	                    password: this.password.pass2,
	                    sales: [], 
	                    userName: this.tempUser.userName
                    };
                    db.collection('users').add(newUserData).then(function(response) {	                                       
                        _this.$parent.user = _this.tempUser;
                        _this.$parent.user.id = response.id;
                        _this.newUser = false;
                        _this.finishUpdate(_this.$parent.user.id, section);
                        _this.$root.setAlert(true, 'Update successful', 'alert-success');
                    });
                } else {
                    var data = {};                    
                    for (var i = 0; i < toUpdate.length; i++) {
                        data[toUpdate[i].item] = toUpdate[i].data;
                    }                                        
                    db.collection('users').doc(this.$parent.user.id).update(data).then(result => {                
                        _this.finishUpdate(_this.$parent.user.id, section);
                        _this.$root.setAlert(true, 'Update successful', 'alert-success');
                    });
                }
            }
        },        
        resetUser: function() {
            this.$parent.editing = false;
        },
        getStaff: function(id) {
            return this.$parent.staff.find(s => {
                return s.id == id;
            });
        },
        addStaff: function(type) {
            switch (type) {
                case 'iam':
                    this.tempUser.iam.push({staffId: this.iamSelect.id});
                    this.iamSelect = '';
                break;
                case 'sales':
                    this.tempUser.sales.push({staffId: this.salesSelect.id, states: []});
                    this.salesSelect = '';
                break;
            }
        },
        stateEdit: function(staff) {
            this.activeStaff = staff;
            $("#stateSelect").modal({
                keyboard: true,
                backdrop: 'static'
            }, 'show');

        },
        removeStaff: function(arr, key) {
            return arr.splice(key, 1);
        },
        testStaff: function(arr, item) {
            return arr.some(i => {
                return item.id == i.staffId;
            });
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
        nodeUpdate: function(node) {
            this.access.node = node;        	
            this.access.action = false;
			if (node.type == 'folder') { //Recursively grant access to children
				var action = 'grant';				
				if (this.tempUser.access.some(a => { return a == node.id; })) {
					action = 'revoke';
                }
                this.access.action = action;              
            }
        },
		grantChildAccess: function(node, action) {					
			for (var i = 0; i < node.children.length; i++) {
                let c = node.children[i];				
                var ind = false;
				if (c.type == 'folder') {
					if (c.children.length > 0) {
						this.grantChildAccess(c, action);
					}
				}								
				switch (action) {					
                    case 'grant':						
                        ind = this.tempUser.access.findIndex(a => {
					        return a == c.id;
                        }); 
                        if (ind == -1) { 
                            this.tempUser.access.push(c.id);						
                        }
					break;
					case 'revoke':                        
						ind = this.tempUser.access.findIndex(a => {
					        return a == c.id;
                        }); 
                        if (ind != -1) { 
							this.tempUser.access.splice(ind, 1);
						}
					break;
                }
                ind = false;
			}			
		}        
    },
    computed: {
        testUpdateS1: function() {            
           return this.testValid('userName').update || this.testValid('displayName').update || this.testValid('password').update || this.testValid('enabled').update;
        },
        testUpdateS2: function() {            
           return this.testValid('bluerock').update;
        },
        testUpdateS3: function() {            
           return this.testValid('iam').update || this.testValid('sales').update;
        },
        testUpdateS4: function() {            
           return this.testValid('message').update;
        },
        testUpdateS5: function() {            
           return this.testValid('access').update;
        }
    },
    watch: {
		'tempUser.access': function(oldVal, newVal) {            	
            if (this.access.node.type == 'folder') {
                this.grantChildAccess(this.access.node, this.access.action);
            }           
		}
	}
}
</script>
<style>
.custom-file-control.selected::after {
    content: "" !important;
}
.form-group {
    margin-bottom: 0;
}
#tree-root {
    margin-top: 0px;
}

.card-body {
    padding: 10px;
}

h4 {
    cursor: pointer;
}
.alert {
    margin-bottom: 0;
}

.card-header h4 span:hover {
    text-decoration: underline;
}

#tree-root .node {
    padding: 0px 0px 0px 10px;
}

.node > div:hover {
    background-color: #cce5ff;
}

.node > div {
    padding: 3px;
}

</style>