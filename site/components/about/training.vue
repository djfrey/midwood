<template>
    <div class="col-12 row">
        <div class="col-md-12 col-lg-6" v-for="d in data">
            <div class="row">
                <div class="col-sm-4 col-md-3 col-lg-5 col-xl-4">
                    <img class="rounded-lg" :src="'./site/assets/employees/'+d.img" :alt="d.name"/>
                </div>
                <div class="col-sm-8 col-md-9 col-lg-7 col-xl-8">
                    <h4>{{d.name}}</h4>
                    <h5>{{d.title}}</h5>
                    <h5 v-if="d.title2">{{d.title2}}</h5>
                    <div v-if="d.bio">
                        <span class="a" data-toggle="modal" data-backdrop="static" data-target="#modalEmployeeBio" v-on:click="$parent.employee = d">
                            <i class="fas fa-arrow-alt-right"></i> Read more
                        </span>
                    </div>
                    <div>
                        <a :href="'mailto:'+d.email"><i class="fas fa-envelope"></i> {{d.email}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
module.exports = {
    data: function() {
        return {
            data: [
                {                                        
                    name: 'Jennifer Shields',
                    title: 'National Training Director',                    
                    img: 'shields.jpg',                    
                    email: 'jshields@midwood.com',
                    bio: '<p>Jennifer Shields joined Midwood Financial Services, Inc. in October of 2016.</p><p>As National Training Director, Jennifer will partner with Midwood’s Regional Sales Directors to deliver high quality, customizable training to Midwood’s partner firms in coordination with the firm’s product, sales and compliance departments.</p><p>Jennifer has over 22 years of experience in the Financial Services industry. After graduating from Purdue University with a Bachelor of Science in Financial Planning and Counseling, Jennifer began her career with Lafayette Savings Bank. She was also a Retail Investment Training Specialist for National City Bank.</p><p>Prior to joining Midwood, Jennifer spent 12 years with Fifth Third Bank in various sales management and training roles.</p>'               
                },                                              
                {                                        
                    name: 'Christopher O\'Malley',
                    title: 'Regional Training Director',
                    img: 'omalley.jpg',                    
                    email: 'comalley@midwood.com'
                    //bio: ''
                },
                {                                        
                    name: 'Jackie Martin',
                    title: 'Regional Training Director',
                    img: 'martin.jpg',                    
                    email: 'jmartin@midwood.com'
                   // bio: ''
                },  
                {                                        
                    name: 'Michelle Miller',
                    title: 'Regional Training Director',
                    img: 'miller.jpg',                    
                    email: 'michelle@midwood.com'
                    //bio: ''
                }
            ]
        }
    }
}
</script>
<style scoped>
.row .row {
    padding: 10px 0px;
}
</style>