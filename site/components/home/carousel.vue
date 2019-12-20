<template>
    <div class="row">
        <div id="carouselIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselIndicators" v-for="(c,i) in carousel" :class="{'active': i === 0}" :data-slide-to="i"></li>            
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item" :class="{'active': i === 0}" v-for="(c, i) in carousel">
					
                    <img v-if="c.type == 'image'" class="d-block w-100" :src="c.src" alt="c.caption">
					<video class="d-block w-100" v-if="c.type == 'video'" muted="" autoplay="" loop="" preload="auto" style="width: 100%; left: 0px;">
						<source :src="c.src" type="video/mp4">
					</video>
					
                    <div v-if="c.heading || c.caption" class="carousel-caption d-none d-md-block">
                        <h5 class="caption-heading" v-if="c.heading">{{c.heading}}</h5>
                        <p class="caption-body" v-if="c.caption">{{c.caption}}</p>
                    </div>
                </div>            
            </div>
            <a class="carousel-control-prev" href="#carouselIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
</template>
<script>
module.exports = {  
    data: function() {
        return {  
            carousel: [
                {
					type: 'image', 
					src: './site/assets/carousel/20-year-01.jpg', 
					heading: '', 
					caption: ''
				},
                {
                    type: 'image',
					src: './site/assets/carousel/AdobeStock_208781944.jpg',
                    heading: 'Welcome To Midwood Financial', 
                    caption: 'Midwood Financial Services, Inc. is the nation’s largest independently owned distributor of financial products to banks and bank third-party marketing companies.'
                },
                {
					type: 'video',
                    src: './site/assets/carousel/AdobeStock_80491960.mov', 
                    heading: 'Seasoned National Sales Team', 
                    caption: 'We have a footprint of wholesalers across the country. Our territories are designed to make sure that all of our clients are afforded unparalleled support to the sales and marketing efforts of their licensed representatives.'
                },                
				{
					type: 'video',
                    src: './site/assets/carousel/AdobeStock_92399654.mov', 
                    heading: 'Experience', 
                    caption: 'Our dedicated team of industry professionals is ready to leverage their combined decades of relevant experience. Let our knowledge work for you.'
                },
                {
					type: 'video',
                    src: './site/assets/carousel/AdobeStock_19917794.mov', 
                    heading: 'Training and Advisor Development', 
                    caption: 'We develop and deliver customized training programs and materials to meet your specific business needs.'
                },
                {
					type: 'video',
                    src: './site/assets/carousel/AdobeStock_77161109.mov', 
                    heading: 'Technology', 
                    caption: 'Up-to-date rate announcements, customized reports on products, proprietary training modules, marketing materials, and forms that are accessible to all registered clients 24-hours a day, seven days a week, via a password-protected web portal that is designed around your needs.'
                },
				
            ]
        }
    }
}
</script>
<style scoped>
.carousel-item  {
    max-width: 100%; height: 500px; width: 100%;
}

.carousel-caption {
    bottom: 20%;
    right: 0%;
    left: 5%;
    text-align: left;
    width: 85%;
}

.carousel-caption .caption-heading {
    font-family: 'Raleway', sans-serif;
    font-size: 4rem;
    text-shadow: rgba(0, 0, 0, 0.8) 1px 1px 2px;
}

.carousel-caption .caption-body {
    width: 80%;
    padding: 10px;
    background-color: rgba(0, 0, 0, 0.5);
    border-radius: 5px;
    font-size: 1.4rem;
    text-shadow: rgba(0, 0, 0, 0.8) 1px 1px 2px;
}
.carousel-control-next, .carousel-control-prev {
    width: 5%;
    font-size: 2.0rem;
}

.carousel-control-next-icon, .carousel-control-prev-icon {
    width: 30px;
    height: 30px;
}
</style>
