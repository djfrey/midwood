<template>
    <div class="row justify-content-center" v-on:keyup.enter="signIn()">
        <div class="card col-md-5 col-lg-4 col-sm-7">
            <div class="logo">
                <img src="/assets/images/midwood_logo.png" alt="Midwood Financial Services. Inc.">
            </div>
            <form class="mt-3 mb-3">
                <div class="row">
                    <div class="col">
                        <div class="form-label-group input-group">
                            <input type="text" name="userName" class="form-control" placeholder="User name" v-model="login.name" />
                            <label>User name</label>						
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-label-group input-group">
                            <input type="password" name="userPassword" class="form-control" placeholder="Password" v-model="login.password" />
                            <label>Password</label>						
                        </div>
                    </div>
                </div>
                <div v-if="error > ''" :class="{'shake': error > ''}" :key="errKey" class="row">
                    <div class="alert col mr-3 ml-3 alert-danger"><i class="fas fa-lg fa-exclamation-square"></i> {{error}}</div>
                </div>
                <div class="row">
                    <div class="col">
                        <button type="button" v-on:click="signIn()" class="btn btn-lg btn-block btn-dark"><i class="fad fa-sign-in-alt"></i> Sign in</button>
                    </div>
                </div>
            </form>
            <div class="border-top pt-3 pb-2 mb-2">
                <button type="button" v-on:click="$router.push('/home')" class="btn btn-lg btn-block btn-primary"><i class="fad fa-arrow-to-left"></i> Go back to midwood.com</button>                    
            </div>
        </div>          
    </div>
</template>
<script>
module.exports = {   
    data: function() {
        return {
            login: {name: '', password: ''},
            error: '',
            errKey: 0            
        }
    },
    methods: {
         signIn: function() {            
            this.error = '';
            this.errKey += 1;                              
            var _this = this;
            _this.login.name = _this.login.name.trim();
            _this.login.password = _this.login.password.trim();
            var up = this.$bind('fsUsers', db.collection('users').where('userName', '==', _this.login.name));
            var sp = this.$bind('fsStaff', db.collection('staff').where('userName', '==', _this.login.name));      
            Promise.all([up, sp]).then(function(response) {	                                    
                let u = response[0] || [];
                let s = response[1] || [];	
                if (u.length == 0 && s.length == 0) {
                    //Invalid user
                    _this.error = "You've entered an invalid username.";
                } else {
                    //Login is a user
                    if (u.length > 0) {                
                        if (u[0].password == _this.login.password) {
                            var email = 'user@midwood.com';
                            var password = '4dy174KmAZU7Qqw';
                            firebase.auth().signInWithEmailAndPassword(email, password).then(function() {                        
                                //Valid user, set cookie                        
                                localStorage.setItem('cookie', JSON.stringify({key: 'mw-advisorlink', user: u[0], role: 'user'}));                                                            
                                _this.$root.user = {userName: u[0].userName};                                             
                                _this.$parent.isLoggedIn();
                            }).catch(function(error) {
                                _this.error = error.message;                    
                            });                    
                        } else {
                            _this.error = "You've entered an invalid password.";
                        }
                    }			
                	//Login is staff								
                    if (s.length > 0) {
                        if (s[0].password == _this.login.password) {
                            var email = 'aladmin@midwood.com';
                            var password = 'ajWG057Zo28O1Jy';
                            firebase.auth().signInWithEmailAndPassword(email, password).then(function() {
                                //Valid user, set cookie
                                localStorage.setItem('cookie', JSON.stringify({key: 'mw-advisorlink', user: s[0], role: s[0].role}));                                
                                _this.$root.user = {userName: s[0].userName};
                                console.log(localStorage.getItem('cookie'));
                                _this.$parent.isLoggedIn();
                            }).catch(function(error) {                        
                                _this.error = error.message;                    
                            });  
                        } else {
                            _this.error = "You've entered an invalid password.";
                        }   
                    }				
                }
            });
        }

    },
    created: function() {

    }
}
</script>
<style scoped>
.row {
    background-color: transparent;
}
.card {
    padding-top: 20px;
    margin-top: 20px;
    background-color: rgba(255,255,255, 0.6);
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
}

.card .logo {
    margin: 0px auto;
}

.card .logo img {
    width: 100%;
    
}

.shake {
  animation: errShake 0.82s cubic-bezier(.36,.07,.19,.97) both;
  transform: translate3d(0, 0, 0);
  backface-visibility: hidden;
  perspective: 1000px;
}

@keyframes errShake {
  10%, 90% {
    transform: translate3d(-1px, 0, 0);
  }
  
  20%, 80% {
    transform: translate3d(2px, 0, 0);
  }

  30%, 50%, 70% {
    transform: translate3d(-4px, 0, 0);
  }

  40%, 60% {
    transform: translate3d(4px, 0, 0);
  }
}

</style>