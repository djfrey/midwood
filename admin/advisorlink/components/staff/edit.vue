<template>
    <div>         
        <div class="row border-bottom pt-3 mb-2">
            <div class="col-9">
                <h3>
                    <i class="text-primary fas fa-pen-square"></i> 
                    <span v-if="!newMemb">Editing user "{{$parent.memb.name}}"</span>
                    <span v-else>Creating a new staff member</span>                    
                </h3>
            </div>
            <div class="col-3">
                <button type="button" v-on:click="resetMemb();" class="mt-1 btn btn-primary float-right"><i class="fas fa-arrow-left"></i> Back to staff</button>
            </div>
        </div>        
        <div class="card">
            <div class="card-header">
                <h4 class="float-left"><i class="fas fa-fw fa-clipboard-user"></i> Staff member information</h4>
                <button type="button" v-on:click="update()" :disabled="!testUpdate" class="btn btn-success btn-sm float-right"><i class="fas fa-check"></i> Save</button>      
            </div>           
            <div class="card-body">
                <div class="row border-bottom pb-2 mb-2">
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 col-form-label">
                                <label><strong>User name</strong></label>
                            </div>			
                            <div class="col-8">
                                <input type="text" v-model="tempMemb.userName" class="form-control" placeholder="User name">                         
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
                                    <span v-if="!password.hidden">{{tempMemb.password}} <button type="button" v-on:click="password.hidden = true;" class="btn btn-primary btn-sm float-right"><i class="fas fa-eye-slash"></i> Hide</button></span>
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
                <div class="row border-bottom pb-2 mb-2">
                     <div class="col-6">
                        <div class="row">
                            <div class="col-4 col-form-label">
                                <label><strong>Name</strong></label>
                            </div>			
                            <div class="col-8">
                                <input type="text" v-model="tempMemb.name" class="form-control" placeholder="Name">                         
                            </div>                         
                        </div>
                        <div class="row" v-if="errors.name">
                            <div class="col">
                                <div class="alert alert-danger"><i class="fas fa-lg fa-exclamation-circle"></i> {{errors.name}}</div>
                            </div>
                        </div>
                    </div>                   
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 col-form-label">
                                <label><strong>Email</strong></label>
                            </div>			
                            <div class="col-8">
                                <input type="text" v-model="tempMemb.email" class="form-control" placeholder="user@midwood.com">                         
                            </div>                         
                        </div>                        
                    </div>                              
                </div>
                <div class="row border-bottom pb-2 mb-2">
                     <div class="col-6">
                        <div class="row">
                            <div class="col-4 col-form-label">
                                <label><strong>Advisorlink telephone</strong></label>
                            </div>			
                            <div class="col-8">
                                <input type="text" v-model="tempMemb.phone" class="form-control" placeholder="888-555-1234 ext. 999">                         
                            </div>                         
                        </div>                        
                    </div>                   
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 col-form-label">
                                <label><strong>Role</strong></label>
                            </div>			
                            <div class="col-8">
                                <div class="form-control-plaintext">
                                    <div class="custom-control custom-radio custom-control">
                                        <input type="radio" v-model="tempMemb.role" value="sales" id="userRoleSales" name="role" class="custom-control-input">
                                        <label class="custom-control-label" for="userRoleSales">Sales</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control">
                                        <input type="radio" v-model="tempMemb.role" value="iam" id="userRoleIam" name="role" class="custom-control-input">
                                        <label class="custom-control-label" for="userRoleIam">Internal Account Manager</label>
                                    </div>
                                    <!-- <div class="custom-control custom-radio custom-control">
                                        <input type="radio" v-model="tempMemb.role" value="admin" id="userRoleAdmin" name="role" class="custom-control-input">
                                        <label class="custom-control-label" for="userRoleAdmin">Administrator</label>
                                    </div> -->
                                    <div class="custom-control custom-radio custom-control">
                                        <input type="radio" v-model="tempMemb.role" value="other" id="userRoleOther" name="role" class="custom-control-input">
                                        <label class="custom-control-label" for="userRoleOther">Other</label>
                                    </div>
                                </div>
                            </div>                         
                        </div> 
                    </div>                
                </div>
                <div class="row">  
                    <div class="col-6">
                        <div class="row">
                            <div class="col-4 col-form-label">
                                <label><strong>Portrait</strong></label>
                            </div>			
                            <div class="col-8">
                                <div class="pb-2">
                                    <img v-if="tempMemb.portrait" class="img-fluid" :src="tempMemb.portrait">
                                    <span v-else>No portrait set</span>
                                </div>                                               
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div v-if="!newMemb" class="input-group">
                                    <div class="custom-file">
                                        <input type="file" accept=".bmp,.gif,.jpg,.jpeg,.png" class="custom-file-input" ref="fileUpload" id="logoFile"  v-on:change="setFile()">
                                        <label class="custom-file-label logo-file-label" for="logoFile">Choose a portrait file</label>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-success" v-on:click="upload()" :disabled="toUpload.name == '' || uploading">
                                            <span v-if="!uploading"><i class="fas fa-file-upload"></i> Upload</span>
                                            <span v-if="uploading"> <i class="fas fa-circle-notch fa-spin"></i> Uploading...</span>
                                        </button>                                
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="alert alert-warning">Please create and update a name, username and password first.</div>
                                </div>
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
                                        <input type="radio" v-model="tempMemb.enabled" value="yes" id="userActiveYes" name="userActive" class="custom-control-input">
                                        <label class="custom-control-label" for="userActiveYes">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" v-model="tempMemb.enabled" value="no" id="userActiveNo" name="userActive" class="custom-control-input">
                                        <label class="custom-control-label" for="userActiveNo">No</label>
                                    </div>
                                </div>
                            </div>                         
                        </div>
                        <div class="row">
                            <div class="col-4 col-form-label">
                                <label><strong>Advisorlink admin?</strong></label>
                            </div>			
                            <div class="col-8">
                                <div class="form-control-plaintext">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" v-model="tempMemb.isAdmin" value="yes" id="userisAdminYes" name="isAdmin" class="custom-control-input">
                                        <label class="custom-control-label" for="userisAdminYes">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" v-model="tempMemb.isAdmin" value="no" id="userisAdminNo" name="isAdmin" class="custom-control-input">
                                        <label class="custom-control-label" for="userisAdminNo">No</label>
                                    </div>
                                </div>
                            </div>                         
                        </div>  
                        <div class="row">
                            <div class="col-4 col-form-label">
                                <label><strong>Advisorlink color</strong></label>
                            </div>			
                            <div class="col-8">
                                <div class="input-group">
                                    <input type="text" v-model="tempMemb.color" class="form-control">
                                    <div class="input-group-append color">
                                        <span class="input-group-text" :style="{'background-color': '#'+tempMemb.color || '#000000'}">
                                        </span>                                        
                                    </div>
                                </div>
                            </div>                         
                        </div> 
                        <div class="row" v-if="errors.color">
                            <div class="col">
                                <div class="alert alert-danger"><i class="fas fa-lg fa-exclamation-circle"></i> {{errors.color}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>
    </div>
</div>
</template>
<script>
const uploadDefault = {name: ''};
module.exports = {	
	data: function() {
		return {            
            newMemb: this.$parent.memb.id == '',            
            loading: this.$parent.loading,
            tempMemb: JSON.parse(JSON.stringify(this.$parent.memb)),                        
            password: {hidden: true, pass1: '', pass2: ''},
            errors: {name: false, password: false, userName: false, color: false},
            toUpload: uploadDefault, //Hold the portrait file
            uploading: false
		}
	},
	methods: {	
        setFile: function() {
            if (this.$refs.fileUpload.files.length > 0) {
                this.toUpload = this.$refs.fileUpload.files[0];            
                $('.logo-file-label').html(this.toUpload.name);
            } else {
                this.toUpload = uploadDefault;
                $('.logo-file-label').html('Choose a portrait file');
            }
        },
        upload: function() {
            this.uploading = true;
            var p = [];
            var u = [];
            var s = [];
            var _this = this;

            p.push(storage.child('staff/'+_this.$parent.memb.id).put(_this.toUpload)); //Add the files to storage
            Promise.all(p).then(fileResult => {
                for (l = 0; l < fileResult.length; l++) {
                    var ref = fileResult[l].ref; //Get the result from the file addition                        
                    u.push(ref.getDownloadURL()); //Get the download URL of the added file  
                }
                Promise.all(u).then(urlResult => {                    
                    for (m = 0; m < urlResult.length; m++) {                        
                        _this.tempMemb.portrait = urlResult[m]; //Add the URL to the node
                        _this.$parent.memb.portrait = urlResult[m];                        
                        s.push(db.collection('staff').doc(_this.$parent.memb.id).update({portrait: urlResult[m]})); //Update the URL value of the document                                                        
                    }
                    Promise.all(s);
                    this.uploading = false;
                    this.toUpload = uploadDefault;
                    _this.$root.setAlert(true, 'Portrait updated successfully', 'alert-success');
                    this.$parent.memb.portrait = this.tempMemb.portrait;   
                });
            });
        },
        finishUpdate: function(id, section) {
            var i = this.$parent.staff.findIndex(a => {
                return a.id == id;
            });                        
            this.tempMemb.id = id;    
            var p = this.testValid('password');
            this.$parent.memb.name = this.tempMemb.name;
            this.$parent.memb.role = this.tempMemb.role;
            if (p.valid && p.update) {
                this.tempMemb.password = this.password.pass2;
                this.password = {hidden: true, pass1: '', pass2: ''};
            }
            this.$parent.memb.enabled = this.tempMemb.enabled;
            this.$parent.memb.password = this.tempMemb.password;
            this.$parent.memb.isAdmin = this.tempMemb.isAdmin;                    
            this.$parent.memb.phone = this.tempMemb.phone;   
            this.$parent.memb.email = this.tempMemb.email;   
            this.$parent.memb.portrait = this.tempMemb.portrait;   
            this.$parent.memb.color = this.tempMemb.color;   
            eventBus.$emit('update-memb', i, this.tempMemb);
            this.tempMemb = JSON.parse(JSON.stringify(this.$parent.memb));
        },  
        testValid: function(item) {
            var i = '';
            var update = false;
            var valid = true;
            var error = '';
            var out = {item: '', data: '', valid: true, update: false, error: ''};
            switch (item) {
                case 'name':
                    i = this.tempMemb.name.trim();
                    update = (i > '' && i != this.$parent.memb.name);                    
                    if (i == '') {
                        valid = false;
                        error = 'Name is required';
                    }
                break;                
                case 'userName':
                    i = this.tempMemb.userName.toLowerCase().trim();
                    update = (i > '' && i != this.$parent.memb.userName.toLowerCase().trim());  
                    var exists = this.$parent.staff.some(u => {
                        return u.userName == i;
                    });
                    if (exists) {
                        valid = false;
                        error = 'That username is being used by another user';
                    }                      
                    if (i == '') {
                        valid = false;
                        error = 'Username is required';
                    }
                break;                
                case 'password':
                    i = this.password.pass2.trim();
                    update = (i > '' && i != this.$parent.memb.password && this.password.pass1.trim() == i);                   
                    if (this.tempMemb.password == '' && (i == '' && this.password.pass1.trim() == '')) {
                        valid = false;
                        error = 'Please create a password';
                    } else if (this.password.pass1.trim() != i) {
                        valid = false;
                        error = 'Both passwords must match';
                    }
                break;
                case 'email':
                    i = this.tempMemb.email || '';
                    update = (i != this.$parent.memb.email);                                        
                break;  
                case 'phone':
                    i = this.tempMemb.phone || '';
                    update = (i != this.$parent.memb.phone);                                        
                break;  
                case 'color':
                    i = this.tempMemb.color;
                    update = (i != this.$parent.memb.color);
                    if (i > '' && !this.tempMemb.color.match(/[0-9A-Fa-f]{6}/g)) {
                        valid = false;
                        error = 'Invalid color, use hex format (RRGGBB)';
                    }
                break;  
                case 'enabled': 
                    i = this.tempMemb.enabled;
                    update = (i != this.$parent.memb.enabled);
                break;
                case 'role': 
                    i = this.tempMemb.role;
                    update = (i != this.$parent.memb.role);
                break;
                case 'isAdmin': 
                    i = this.tempMemb.isAdmin;
                    update = (i != this.$parent.memb.isAdmin);
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
            
            fields.push(
                this.testValid('name'),
                this.testValid('userName'),
                this.testValid('password'),
                this.testValid('enabled'),
                this.testValid('role'),
                this.testValid('email'),
                this.testValid('phone'),
                this.testValid('color'),
                this.testValid('isAdmin'));                    

            for (var i = 0; i < fields.length; i++) {
                this.errors[fields[i].item] = false;
                //If the field has been flagged to update, make sure it's valid - if it is, add to the update queue, otherwise, add an error
                //All fields need to be validated for new members
                if (fields[i].update || this.newMemb) { 
                    if (!fields[i].valid) {
                        this.errors[fields[i].item] = fields[i].error;
                        error = true;
                    } else {                         
                        toUpdate.push(fields[i]);
                    }
                }
            }             
            if (!error) {
                if (this.newMemb) { //This is the first user save, create a new user
                    //Not all of the user fields should be saved in the database, this variable holds the necessary fields
                    var newMembData = {
                        name: this.tempMemb.name,
                        userName: this.tempMemb.userName,
                        password: this.password.pass2,
                        enabled: this.tempMemb.enabled,
                        role: this.tempMemb.role,
                        email: this.tempMemb.email,
                        phone: this.tempMemb.phone,
                        portrait: '',
                        color: this.tempMemb.color || '',
                        isAdmin: this.tempMemb.isAdmin
                    };
                    db.collection('staff').add(newMembData).then(function(response) {	                                       
                        _this.$parent.memb = _this.tempMemb;
                        _this.$parent.memb.id = response.id;
                        _this.newMemb = false;
                        _this.finishUpdate(_this.$parent.memb.id);
                        _this.$root.setAlert(true, 'Update successful', 'alert-success');
                    });
                } else {
                    var data = {};                    
                    for (var i = 0; i < toUpdate.length; i++) {
                        data[toUpdate[i].item] = toUpdate[i].data;
                    }                                        
                    db.collection('staff').doc(this.$parent.memb.id).update(data).then(result => {                
                        _this.finishUpdate(_this.$parent.memb.id);
                        _this.$root.setAlert(true, 'Update successful', 'alert-success');
                    });
                }
            }
        },        
        resetMemb: function() {
            this.$parent.editing = false;
        }      
    },
    computed: {
        testUpdate: function() {                        
           return this.testValid('name').update 
           || this.testValid('userName').update 
           || this.testValid('password').update 
           || this.testValid('enabled').update 
           || this.testValid('role').update 
           || this.testValid('isAdmin').update 
           || this.testValid('phone').update 
           || this.testValid('color').update 
           || this.testValid('email').update;
        }
    }
}
</script>
<style scoped>
.color span {
    width: 100px;
}
.form-group {
    margin-bottom: 0;
}

.card-body {
    padding: 10px;
}

.alert {
    margin-bottom: 0;
}

.row {
    margin-left: -10px;
    margin-right: -10px;
}
</style>