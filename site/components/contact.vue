<template>
<div class="container-fluid">
    <heading></heading>
    <navigation></navigation>
    
    <div class="row">
        <div class="col">
            <h1 class="border-bottom pb-2">Contact Us</h1>
        </div>
    </div>
    <div class="row contact">
        <div class="col-lg-6 col-12">
            <p>You can contact us using the form on this page, or directly via the following information.</p>
            <h3 class="border-bottom pb-2">Midwood Financial Services, Inc.</h3>
            <p><i class="fas fa-fw fa-map-marker-alt" aria-hidden="true"></i> 16133 Ventura Blvd Suite 700 Encino, CA 91436</p>
            <p><i class="fas fa-phone-alt"></i>  1-866-959-1010</p>
            <p><i class="fas fa-envelope"></i> <a href="mailto:info@midwood.com" target="_blank">info@midwood.com</a></p>
            <p><i class="fab fa-linkedin-in"></i> <a href="http://linkedin.com/company/midwood-financial-services" target="_blank">linkedin.com/company/midwood-financial-services</a></p>

        </div>
        <div class="col-lg-6 col-12">
            <form novalidate="true" @submit.prevent="checkForm">
                <div class="form-group row">
                    <label class="col-sm-2" for="contactName">Name</label>
                    <input type="text" v-model="contact.name" class="col mr-3 form-control" id="contactName" placeholder="Enter your name">                    
                </div>
                <div v-if="errors.name" class="alert alert-warning col"><i class="fas fa-exclamation-circle"></i> {{this.errors.name}}</div>
                <div class="form-group row">
                    <label class="col-sm-2" for="contactEmail">Email</label>
                    <input type="email" v-model="contact.email" class="col mr-3 form-control" id="contactEmail" placeholder="Enter your email address">                    
                </div>
                <div v-if="errors.email" class="alert alert-warning col"><i class="fas fa-exclamation-circle"></i> {{this.errors.email}}</div>
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
    
    <div class="row">
        <div class="col mr-3 ml-3">
            <iframe src="https://snazzymaps.com/embed/175900" width="100%" height="400px" style="border:none;"></iframe>
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
            var params = {subject: 'midwood.com Contact form submission', message: this.contact.message, from: this.contact.email, token: recaptchaToken};
            axios.get('/site/data/email.send.php', {
                params: params
            }).then(function (response) {
                if (response.data.error > '') {
                    this.$parent.setAlert(true, response.data.error, 'alert-danger');
                } else {
                    this.$parent.setAlert(true, response.data.data, 'alert-success');
                    this.errors = {name: false, email: false};
                    this.contact = {email: '', name: '', message: ''};
                }
                this.status = false;
            }).catch(function (error) {
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

.contact, h1 {
    padding: 0px 20px;
}

.contact p {
    font-size: 1.1rem;    
}

.contact p i {
    color: #666;
    font-size: 1.1rem;
    padding: 0px 5px;
}

iframe {
    margin: 30px 0px;
}

.grecaptcha-badge {
    display: none !important;
}

</style>