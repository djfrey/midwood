<template>
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="text-md-left text-center">
                <img src="./assets/images/bisa-logo.png" />
                <h5>Member in good standing</h5>
                <div class="small">
                    &copy; Copyright {{currentYear}} | Midwood Financial Services, Inc. | <a href="legal-notice">Legal notice</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 icons col-12">
            <div class="text-md-right text-center">
                <a href="https://linkedin.com/company/midwood-financial-services" target="_blank"><i class="fab fa-lg fa-linkedin-in"></i></a>
                <a href="mailto:info@midwood.com" target="_blank"><i class="fas fa-lg fa-envelope"></i></a>
            </div>
        </div>
    </div>
</template>
<script>
module.exports = {  
    computed: {
        currentYear: function() {
            return new Date().getFullYear();
        }
    }
}
</script>
<style scoped>
.row {
    background-color: #2b507c;
    border-top: 1px solid #373f49;
    padding: 20px;
    color: #FFF;
}
.small a {
    color: #8cc0ff;
}
.icons a {
    color: #FFF;
    padding: 0px 5px;
}

.icons a:hover {
    opacity: 0.8;
}
</style>