<template>
    <div class="col-12 row">
         <div class="col-sm-12 col-md-6 col-xl-4">
            <div class="row">
                <div class="col-sm-3 col-md-5 col-lg-4 col-xl-4">
                    <img class="rounded-lg" :src="'./site/assets/employees/'+svp.img" :alt="svp.name"/>
                </div>
                <div class="col-sm-9 col-md-7 col-lg-8 col-xl-8">
                    <h5>{{svp.name}}</h5>
                    <h6>{{svp.title}}</h6>                                        
                    <h6>{{svp.title2}}</h6>                                        
                    <div>
                        <a :href="'mailto:'+svp.email"><i class="fas fa-envelope"></i> {{svp.email}}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-100 d-none d-md-block"></div>
        <div class="col-sm-12 col-md-6 col-xl-4" v-for="d in data">
            <div class="row">
                <div class="col-sm-3 col-md-5 col-lg-4 col-xl-4">
                    <img class="rounded-lg" :src="'./site/assets/employees/'+d.img" :alt="d.name"/>
                </div>
                <div class="col-sm-9 col-md-7 col-lg-8 col-xl-8">
                    <h5>{{d.name}}</h5>
                    <div>{{d.title}}</div>                                        
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
            svp: {
                name: 'Diane Blank',
                title: 'Senior Vice President',    
                title2: 'Administration',    
                img: 'dblank.jpg',                                    
                email: 'dblank@midwood.com'
            },
            data: [
                {                                        
                    name: 'Anne von Herrmann',
                    title: 'Commissions Administrator',               
                    img: 'herrmann.jpg',                                                            
                    email: 'anne@midwood.com'
                },
                {                                        
                    name: 'Lani Biegaj',
                    title: 'Marketing Director',               
                    img: 'lbiegaj.jpg',                                                            
                    email: 'lbiegaj@midwood.com'
                },
                {                                        
                    name: 'Joanne Van Hoorebeke',
                    title: 'Administrative Assistant',               
                    img: 'vanhoorebeke.jpg',                                                            
                    email: 'jvan@midwood.com'
                },
                {                                        
                    name: 'Cat Kaelyn',
                    title: 'Administrative Assistant',               
                    img: 'caelyn.jpg',                                                            
                    email: 'ckaelyn@midwood.com'
                },
                {                                        
                    name: 'Amber Bloom',
                    title: 'Administrative Assistant',               
                    img: 'krupski.jpg',                                                            
                    email: 'abloom@midwood.com'
                },
                {                                        
                    name: 'Alex Roberts',
                    title: 'Administrative Assistant',               
                    img: 'roberts.jpg',                                                            
                    email: 'aroberts@midwood.com'
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