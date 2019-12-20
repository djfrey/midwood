<template>
<div class="container-fluid">
    <heading></heading>
    <navigation></navigation>
    
    <div class="row intro">
        <div class="col">
            <h1>Join our National Team</h1>
            <p>Looking for a new and exciting challenge every single day? Midwood is always looking for energetic and engaging financial professionals who are driven to do their 
                best and are ready to collaborate with some of the best in the industry. Our leaders are here to encourage you and empower you. We are committed to providing our 
                people with an enriching and rewarding environment. We provide the resources, tools and support our employees need to do their best and succeed in their careers.</p>
        </div>
    </div>
    <flipboxes></flipboxes>
    <div class="row contact">
        <div class="col-12">
            <h1><i class="fas fa-envelope-open"></i> Get started</h1>
        </div>
        <div class="col-12">
            <form novalidate="true" @submit.prevent="checkForm">
                <div class="form-group row">
                    <label class="col-sm-2" for="contactName">Name</label>
                    <input type="text" v-model="contact.name" class="col mr-3 form-control" id="contactName" placeholder="Enter your name">                    
                </div>
                <div v-if="errors.name" class="alert alert-warning col-sm-10 offset-sm-2"><i class="fas fa-exclamation-circle"></i> {{this.errors.name}}</div>
                <div class="form-group row">
                    <label class="col-sm-2" for="contactEmail">Email</label>
                    <input type="email" v-model="contact.email" class="col mr-3 form-control" id="contactEmail" placeholder="Enter your email address">                    
                </div>
                <div v-if="errors.email" class="alert alert-warning col-sm-10 offset-sm-2"><i class="fas fa-exclamation-circle"></i> {{this.errors.email}}</div>
                <div class="form-group">
                    <label for="contactMessage">Your message</label>
                    <textarea class="form-control" v-model="contact.message" id="contactMessage" rows="3"></textarea>
                </div>
                <vue-recaptcha
                    ref="recaptcha"
                    v-on:verify="onCaptchaVerified"
                    v-on:expired="onCaptchaExpired"
                    size="invisible"
                    sitekey="6Lcn17EUAAAAAF-Q9sNyx5JhnL15a6jN70kgVa8K">
                </vue-recaptcha>
                <button type="submit" :disabled="status == 'submitting'" class="btn btn-primary"><i class="fas fa-arrow-right"></i> Submit</button>                 
            </form>
        </div>
    </div>
        
    <foot></foot>
</div>
</template>
<script>
module.exports = {   
    components: {
        heading: httpVueLoader('./common/heading.vue'),
        navigation: httpVueLoader('./common/navigation.vue'),
        flipboxes: httpVueLoader('./careers/flipboxes.vue'),        
        foot: httpVueLoader('./common/foot.vue'),
        vueRecaptcha: VueRecaptcha
    },
    data: function() {
        return {
            contact: {email: '', name: '', message: ''},
            errors: {name: false, email: false},
            status: false
        }
    },
    methods: {
        checkForm: function (e) {            
            this.errors = {name: false, email: false};

            if (!this.contact.name || this.contact.name.trim() == '') {
                this.errors.name = 'Please enter your name';
            }

            if (!this.contact.email || this.contact.email.trim() == '') {
                this.errors.email = 'Please enter your email address';
            } else if (!this.validEmail(this.contact.email.trim())) {
                this.errors.email = 'Please enter a valid email address';
            }
            
            if (!this.errors.name && !this.errors.email) {
                this.submit();
            } else {
                e.preventDefault();
            }
        },
        validEmail: function (email) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);            
        },
        submit: function() {           
            this.$refs.recaptcha.execute();
        },
        onCaptchaVerified: function (recaptchaToken) {
            this.status = "submitting";
            this.$refs.recaptcha.reset();
            var params = {subject: 'midwood.com Careers form submission', message: this.contact.message, from: this.contact.email, token: recaptchaToken};
            axios.get('/site/data/email.send.php', {
                params: params
            }).then(function(response) {
                if (response.data.error) {
                    this.$parent.setAlert(true, response.data.error, 'alert-danger');
                } else {
                    this.$parent.setAlert(true, response.data.data, 'alert-success');
                    this.errors = {name: false, email: false};
                    this.contact = {email: '', name: '', message: ''};
                }
                this.status = false;
            }).catch(function(error) {
                this.$parent.setAlert(true, error, 'alert-danger');
                this.status = false;
            });
        },
        onCaptchaExpired: function () {
            this.$refs.recaptcha.reset();
        }
    }

}
</script>
<style scoped>
.row {
    background-color: #FFF;
    padding: 30px auto;
    font-family: 'Raleway', sans-serif;
}

.row h1 {
    margin: 30px auto;
}

.row.intro p {
    margin: 20px 0px;
    padding: 0px;
}

.intro {
    padding: 30px;
    color: #FFF;
    background-image: url('/site/assets/AdobeStock_93245244.jpg');
    background-position: center center;
    background-repeat: no-repeat;
    -webkit-background-size:cover;
    -moz-background-size:cover;
    -o-background-size:cover;
    background-size:cover;
    text-shadow: rgba(0, 0, 0, 0.8) 1px 1px 2px;
}

.contact, .contact .row {
    background-color: rgb(33, 51, 73);
}

.contact {
    padding: 20px 30px 40px 30px;
}

.contact h1, .contact label {    
    color: #FFF;
}

.grecaptcha-badge {
    display: none !important;
}

</style>