<template>
    <div class="col-12 row">        
        <h4 class="col-12 p-3">Internal Account Managers</h4>
        <div class="col-sm-12 col-md-6 col-xl-4" v-for="d in iam">
            <div class="row">
                <div class="col-sm-3 col-md-5 col-lg-4 col-xl-4">
                    <img class="rounded-lg" :src="'./site/assets/employees/'+d.img" :alt="d.name"/>
                </div>
                <div class="col-sm-9 col-md-7 col-lg-8 col-xl-8">
                    <h5>{{d.name}}</h5>
                    <h6>{{d.title}}</h6>                    
                    <div>866-959-1010 <span v-if="d.telephone">ext. {{d.telephone}}</span></div>
                    <div>
                        <a :href="'mailto:'+d.email"><i class="fas fa-envelope"></i> {{d.email}}</a>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="col-12 p-3">Regional Sales Directors</h4>
        <div class="col-sm-12 col-md-6 col-xl-4" v-for="d in rsd">
            <div class="row">
                <div class="col-sm-3 col-md-5 col-lg-4 col-xl-4">
                    <img class="rounded-lg" :src="'./site/assets/employees/'+d.img" :alt="d.name"/>
                </div>
                <div class="col-sm-9 col-md-7 col-lg-8 col-xl-8">
                    <h5>{{d.name}}</h5>
                    <h6>{{d.title}}</h6>                    
                    <div>866-959-1010 <span v-if="d.telephone">ext. {{d.telephone}}</span></div>
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
            iam: [
                {                                        
                    name: 'Ashley Logerot',
                    title: 'Internal Account Manager',                    
                    img: 'logerot.jpg',                    
                    email: 'alogerot@midwood.com',
                    telephone: '726'
                },
                {                                        
                    name: 'Summer Przybylak',
                    title: 'Internal Account Manager',                    
                    img: 'sprzybylak.jpg',                    
                    email: 'summer@midwood.com',
                    telephone: '706'
                },
                {                                        
                    name: 'Dee Schmidt',
                    title: 'Internal Account Manager',                    
                    img: 'schmidt.jpg',                    
                    email: 'dschmidt@midwood.com',
                    telephone: '711'
                },
                {                                        
                    name: 'Patti Thompson',
                    title: 'Internal Account Manager',                    
                    img: 'thompson.jpg',                    
                    email: 'pthompson@midwood.com',
                    telephone: '705'
                },
                {                                        
                    name: 'Kim Thorne',
                    title: 'Internal Account Manager',                    
                    img: 'thorne.jpg',                    
                    email: 'kthorne@midwood.com',
                    telephone: '708'
                },
                {                                        
                    name: 'Kristina Stevens',
                    title: 'Internal Account Manager',                    
                    img: 'kstevens.jpg',                    
                    email: 'kstevens@midwood.com'                    
                }                
            ],
            rsd: [
                {                                        
                    name: 'Brian David',
                    title: 'Regional Sales Director',                    
                    img: 'david.jpg',                    
                    email: 'bdavid@midwood.com',
                    telephone: '709'
                },
                {                                        
                    name: 'Craig Deery',
                    title: 'Regional Sales Director',                    
                    img: 'deery.jpg',                    
                    email: 'cdeery@midwood.com',
                    telephone: '708'
                },
                {                                        
                    name: 'Wayne Flanagan',
                    title: 'Regional Sales Director',                    
                    img: 'flanagan.jpg',                    
                    email: 'wflanagan@midwood.com',
                    telephone: '704'
                },
                {                                        
                    name: 'Chris Gilmore',
                    title: 'Regional Sales Director',                    
                    img: 'gilmore.jpg',                    
                    email: 'cgilmore@midwood.com',
                    telephone: '718'
                },
                {                                        
                    name: 'Mike Murphy',
                    title: 'Regional Sales Director',                    
                    img: 'murphy.jpg',                    
                    email: 'mmurphy@midwood.com',
                    telephone: '713'
                },
                {                                        
                    name: 'Ryan Noe',
                    title: 'Regional Sales Director',                    
                    img: 'noe.jpg',                    
                    email: 'rnoe@midwood.com'
                    //telephone: '709'
                },
                {                                        
                    name: 'Luke Robichaud',
                    title: 'Regional Sales Director',                    
                    img: 'robichaud.jpg',                    
                    email: 'lrobichaud@midwood.com',
                    telephone: '731'
                },
                {                                        
                    name: 'Byron Snoddy',
                    title: 'Regional Sales Director',                    
                    img: 'snoddy.jpg',                    
                    email: 'bsnoddy@midwood.com',
                    telephone: '710'
                },
                {                                        
                    name: 'Matt Will',
                    title: 'Regional Sales Director',                    
                    img: 'will.jpg',                    
                    email: 'mwill@midwood.com',
                    telephone: '713'
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