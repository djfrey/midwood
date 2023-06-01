<template>
<div class="container-fluid">
    <heading></heading>
    <navigation></navigation>
    
    <div class="row intro">
        <div class="col">
            <h1>Bridging the Gap</h1>
            <p>We offer a wide range of high quality instruction of both vendor-specific and generic training with custom-built curriculum. A key component 
            in building a solid training curriculum is matching <i>content</i> to <i>needs</i>.</p> 

            <p>By partnering with our financial institutions, we can provide training that will ultimately increase revenue in a fiduciary-compliant manner.</p>
        </div>
    </div>
    <div class="row p-3 plans">
        <div class="col">
            <div class="row">
                <div class="col-12 text-center">
                    <h1>Customized training plans meet your business needs.</h1>            
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-lg-4">
                    <plan :data="sales"></plan>                    
                </div>
                <div class="col-md-12 col-lg-8">
                    <div class="row">
                        <div class="col-sm-12 col-md-6">
                            <plan :data="product"></plan>   
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <plan :data="positioning"></plan>   
                        </div>
                        <div class="col-sm-12 col-md-6">
                           <plan :data="planning"></plan>  
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <plan :data="compliance"></plan>  
                        </div>
                    </div>
                </div>           
            </div>
        </div>
    </div>

    <div class="row p-3 products">
        <div class="col">
            <div class="row">
                <div class="col-12 text-center">
                    <h1>Specialized product support.</h1>            
                </div>
            </div>
            <div class="row">
                <div class="mt-3 mb-3 col-sm-12 col-md-6 col-lg-3" v-for="f in flyers">
                    <h4>{{f.title}}</h4>
                    <img class="img-fluid" :src="f.img" />
                </div>                         
            </div>
        </div>
    </div>

    <div class="row p-3">
        <contentboxes></contentboxes>
    </div>
    
    <foot></foot>
</div>
</template>
<script>
module.exports = {   
    components: {
        heading: httpVueLoader('./common/heading.vue'),
        navigation: httpVueLoader('./common/navigation.vue'),
        plan: httpVueLoader('./training/plan.vue'),
        contentboxes: httpVueLoader('./training/contentboxes.vue'),
        foot: httpVueLoader('./common/foot.vue')
    },
    data: function() {
        return {
            flyers: [
                {title: 'Growth Over Time', img: './site/assets/training/20180426_MW_Flyer_V08-3-pdf.jpg'},
                {title: 'Annuities vs CDs', img: './site/assets/training/20180426_MW_Flyer_V08-4-pdf.jpg'},
                {title: 'Overcoming Objections', img: './site/assets/training/20180426_MW_Flyer_V08-5-pdf.jpg'},
                {title: 'Safe Growth', img: './site/assets/training/20180426_MW_Flyer_V08-6-pdf.jpg'}
            ],            
            sales: {title: 'Sales Skills', 
                icon: 'fas fa-handshake-alt',
                items: [
                'Sales process',
                'Profiling',
                'Concept/product presentations',
                'Closing',
                'Overcoming objections',
                'Referrals',
                'Calling workshops',
                'Coaching workshops',
                'Communicating your value',
                'Building partnerships',
                'Sales ideas'
                ]
            },
            product: {title: 'Product', 
                icon: 'fas fa-file-chart-line',
                items: [
                'Generic concept',
                'Specific product',
                'New-hire onboarding'
                ]
            },
            positioning: {title: 'Positioning', 
                icon: 'fas fa-arrows-alt',
                items: [
                'How to position fixed annuities and fixed index annuities',
                'How to position specific products'
                ]
            },
            planning: {title: 'Planning', 
                icon: 'fas fa-laptop-code',
                items: [
                'Planning software'
                ]
            },
            compliance: {title: 'Compliance', 
                icon: 'fas fa-file-certificate',
                items: [
                'Needs-based selling and documentation'
                ]
            }
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

.plans, .plans .row {
    background-color: #2b507c;
}

.plans h1, .products h1 {
    color: #FFF;
}

.products, .products .row {
    background-color: #5a8cc9;
}

.products h4 {
    text-align: center;
    color: #FFF;
}

.products img {
    transition: all .3s ease-in-out; 
}

.products img:hover {
    transform: scale(1.02);
    box-shadow: 0 3px 15px rgba(0,0,0,.4);
}

.intro {
    padding: 30px;
    color: #FFF;
    background-image: url('/site/assets/AdobeStock_129807531.jpg');
    background-position: center center;
    background-repeat: no-repeat;
    -webkit-background-size:cover;
    -moz-background-size:cover;
    -o-background-size:cover;
    background-size:cover;
    text-shadow: rgba(0, 0, 0, 0.8) 1px 1px 2px;
}



</style>