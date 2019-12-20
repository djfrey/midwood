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
                    <div>
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
                    name: 'Ed Van Hoorebeke',
                    title: 'Senior Vice President',
                    title2: 'National Sales Manager',
                    img: 'edvan.jpg',                    
                    email: 'edvan@midwood.com',
                    bio: '<p>Edward Van Hoorebeke accepted the position of National Sales Manager and Senior Vice President of Midwood Financial Services, Inc. in 2006. He is a proven industry leader with over thirty years of wholesaling and sales management experience within the financial services industry.</p><p>As National Sales Manager, Mr. Van Hoorebeke manages the sales and training initiatives of Midwood’s national wholesaling team. The wholesalers strive to coordinate their sales and training efforts with the respective institution’s Regional Sales Managers while encouraging compliant product solutions.</p><p>Mr. Van Hoorebeke’s distinguished wholesaling career began at Planco Financial Services in 1988. As Regional Marketing Director (wholesaler) in the state of Florida, he spent five years marketing and wholesaling both annuities and mutual funds to Wire Houses, National, and Regional Brokerage firms.</p><p>From 1993 to 2002, Mr. Van Hoorebeke gained valuable experience wholesaling a variety of financial products and services through American Skandia Life in the financial institutions channel. He was instrumental in distributing products such as variable annuities, mutual funds, retirement vehicles such as 401(k) plans, in addition to life insurance based financial and estate planning solutions.</p><p>From 2002-2005, Mr. Van Hoorebeke was National Sales Manager with MetLife Investors, Managing a national wholesaling team of 18 individuals distributing both fixed and variable annuities through the bank channel. During his tenure annual sales volumes were dramatically increased from $200 million to $2 billion.</p><p>Prior to joining Midwood, Mr. Van Hoorebeke accepted a position with Symetra Financial as National Sales Manager. Within the first twelve months he expanded the existing wholesaling team from 6 to 12 and increased annual sales volume from $240 million to $720 million.</p><p>Mr. Van Hoorebeke is accredited in sales and presentation training through various professional sales academies and holds FINRA Series 6, 7, 24, 26 and 63 securities licenses.</p>'                                       
                },
                {                                        
                    name: 'John Pinson',
                    title: 'Senior Vice President',
                    title2: 'National Accounts Manager',
                    img: 'pinson.jpg',                    
                    email: 'jpinson@midwood.com',
                    bio: '<p>John Pinson joined Midwood Financial Services, Inc. in March of 2009 as a New Business Development Officer and Senior Key Accounts Manager. Mr. Pinson brings over 27 years of experience working in the financial services industry, the majority of which has been spent distributing investment products in a national footprint of financial institutions, independent broker dealers and wirehouses. In joining Midwood, he will contribute to its growth by continuing to serve his extensive network of relationships, delivering consumer-friendly financial products, strong service standards and credible advice.</p><p>Prior to joining Midwood, Mr. Pinson spent 12 years with Security Benefit in various new business development roles. From 2006 to 2009, he was a Strategic Business Development Officer, managing relationships with many of Security Benefit’s key partners. From 2004 to 2007, he was responsible for the fixed annuity product line. During this time, he played a key role in revamping products, marketing materials and web content in order to improve product competitiveness and exposure to a national audience of financial advisors. His initial role was in the founding of the company’s Financial Institution Division, which contributed greatly to the company’s growth of assets.</p><p>Joining GE Capital Assurance (formerly GNA Corporation) in 1989, Mr. Pinson held numerous positions over the next eight years. He gained valuable experience working with financial institutions, from community banks to large regionals. He successfully directed sales teams in acquiring new relationships and managing all aspects of retail sales operations. His achievements were recognized by GE when he was awarded the Pinnacle Award as a rising star among young executives.</p><p>Mr. Pinson began his career at Prudential Financial Services as a registered representative in January of 1983. During his tenure, he was a consistent production leader in a district that employed over 50 representatives. He is a graduate of Presbyterian College with a B.S. Degree in Business Administration and Marketing.</p>'
                },
                {                                        
                    name: 'Kevin Marino',
                    title: 'Director, National Accounts',
                    img: 'marino.jpg',                    
                    email: 'kmarino@midwood.com',
                    bio: '<p>Kevin Marino joined Midwood Financial Services, Inc. in August of 2010 as the Director of Business Development where he was responsible for broadening Midwood’s relationships with financial institutions and independent broker dealers. In 2013, he transitioned to he new role, Director, National Accounts, where he supports Midwood’s Key Account Partner Firms. Mr. Marino brings over twenty years of financial services experience to the firm. A versatile investment professional, Mr. Marino has spent the majority of his career distributing financial products across all channels.</p><p>Prior to joining Midwood, Mr. Marino was a Key Account Manager for MassMutual’s Retirement Income Group where he focused on securing and maintaining relationships with financial institutions, independent broker dealers and regional wirehouses. While at MassMutual, Mr. Marino made countless contributions that resulted in increased distribution for the firm’s fixed, immediate and variable annuity products.</p>'
                },
                {                                        
                    name: 'John Philip Sousa IV',
                    title: 'Director of Business Development, Banks',
                    img: 'sousa.jpg',                    
                    email: 'jsousa@midwood.com',
                    bio: '<p>John Sousa joined Midwood Financial Services, Inc. in June 2013 where he will be responsible for broadening Midwood’s relationships within the bank marketplace.</p><p>Mr. Sousa began his career as a General Agent for Connecticut General Life. After holding positions with a major California savings and loan and a national 403(b) marketing company, he co-founded PAMCO with his partner, Alan Blank.</p><p>As President of PAMCO, Mr. Sousa directed all recruiting, training and sales efforts for the company which employed over 700 sales people in 39 states, rapidly exceeding over a billion dollars of sales per annum.</p><p>Subsequently joining SunAmerica Life as Executive Vice President and member of the Executive Committee and then Mass Mutual, Mr. Sousa successfully expanded those companies into the national bank distribution channel, exceeding expectations for growth and sales. In both companies, he built highly effective and efficient sales wholesaling teams to service the bank clients. His responsibilities included working with product development, actuarial and distribution to help develop leading edge products that would meet consumer needs while generating income to the bank programs.</p><p>Mr. Sousa has been a frequent speaker at industry events and an assistant editor of leading bank publications.</p>'
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