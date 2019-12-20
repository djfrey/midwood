<template>
    <div class="col-12 row">
        <div class="col-md-6 col-12" v-for="d in data">
            <div class="row">
                <div class="col-12 text-center">
                    <img class="rounded-lg" :src="'./site/assets/employees/'+d.img" :alt="d.name"/>
                </div>
                <div class="col-12">
                    <h3 class="pt-3 pb-1">{{d.name}}</h3>
                </div>
                <div class="col-12">
                    <h4>{{d.title}}</h4>
                </div>
                <div class="col-12">
                    <p>{{d.intro}}</p>
                </div>
                <div class="col-6">
                    <span class="a" data-toggle="modal" data-backdrop="static" data-target="#modalEmployeeBio" v-on:click="$parent.employee = d">
                        <i class="fas fa-arrow-alt-right"></i> Read more
                    </span>
                </div>
                <div class="col-6">
                    <div class="float-right social">                        
                        <a :href="d.linkedIn" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                        <a :href="'mailto:'+d.email"><i class="fas fa-envelope"></i></a>
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
                    name: 'Alan Blank',
                    title: 'President',
                    img: 'blank.jpg',
                    linkedIn: 'https://www.linkedin.com/in/alan-blank-9a81609/',
                    email: 'ablank@midwood.com',
                    intro: 'In the years since 1972, Alan Blank\'s career in the financial services industry has included pioneering and developing the concept of investment product and service delivery through depository institutions in the United States.',
                    bio: '<p>In the years since 1972, Alan Blank\'s career in the financial services industry has included pioneering and developing the concept of investment product and service delivery through depository institutions in the United States. Mr. Blank develops new markets, implements innovative distribution systems geared to specific bank cultural and business orientations while maintaining best practices in levels of service and delivery of training systems for partner banks, which run the gamut from small community banks to large national financial institutions. Today Midwood enjoys business partnerships with over 300 banks.</p><p>Some years ago a <em>Fortune</em> article referred to him as "an industry leader to watch." Indeed, Mr. Blank is a well known financial services industry expert who is regularly called upon as a keynote speaker and panelist at industry conferences and seminars throughout the country. He has been featured and interviewed by industry journals such as <em>Business Week</em>, <em>Money</em>, <em>Personal Investor</em>, and <em>American Banker</em> and has appeared on Financial News Network\'s "Marketplace" and "Nation\'s Business Today."</p><p>Mr. Blank has written articles on bank channel alternative product distribution that have appeared in leading industry journals like <em>American Banker</em>, <em>Banks in Insurance Report</em>, the <em>Financial Institutions Marketing Association (FIMA)</em>, <em>The Bankers Magazine</em>, and <em>National Underwriter</em>. He has also authored <em>Investment Product Marketing: Retail Marketing Through Financial Institutions</em>.</p><p>Prior to founding Midwood Financial Services, Inc., Mr. Blank was the key driver in developing American Skandia Life\'s variable annuity financial institutions distribution system. Over a two-year period, from a starting point of zero, American Skandia\'s financial institutions variable annuity business grew to over $400 million in annual volume.</p><p>One of the highlights of Mr. Blank\'s entrepreneurial career was the founding and rapid development of PAMCO Securities and Insurance Services, one of the first and most successful third party annuity marketing firms in the United States. PAMCO became best known for providing innovative investment product delivery systems for many of the country\'s leading financial institutions. PAMCO was a registered broker-dealer and licensed life and variable life and annuity agency, providing non-traditional investment products and services to bank customers.</p><p>Other notable career achievements include heading up A.H. Blank + Company, another successful entrepreneurial effort delivering nontraditional financial products and services through the bank channel on a national basis. Previous positions of note included stock brokerage experience with E.F. Hutton and a regional management position with Great Western Savings &amp; Loan Insurance Agency.</p><p>In 2017, Mr. Blank was honored as a Distinguished Member of the Bank Insurance &amp; Securities Association\'s (BISA) "<a target="_blank" href="https://www.bisanet.org/page/COEMembers">Circle of Excellence.</a>"</p><p>Mr. Blank graduated from the University of Bridgeport with a B.S. Degree in Economics and Marketing and is a registered principal with FINRA. He joined Deloitte &amp; Touche, Cigna Securities and other financial services firms to found the National Society of Compliance Professionals, a not-for-profit association focusing on regulatory issues to help serve the public with honesty and transparency in financial product and service delivery.</p>'                    
                },
                {                                        
                    name: 'James J. Eagan',
                    title: 'Executive Vice President',
                    img: 'eagan.jpg',
                    linkedIn: 'https://www.linkedin.com/in/james-j-eagan-9b413520/',
                    email: 'jeagan@midwood.com',
                    intro: 'James Eagan joined Midwood Financial Services, Inc. as a partner and Executive Vice President in the year 2000. Mr. Eagan has contributed substantially to the company\'s growth owing, in part, to his reputation and his long-standing relationships at the highest levels in the financial services industry.',
                    bio: '<p>James Eagan joined Midwood Financial Services, Inc. as a partner and Executive Vice President in the year 2000. Mr. Eagan has contributed substantially to the company\'s growth owing, in part, to his reputation and his long-standing relationships at the highest levels in the financial services industry.</p><p>Mr. Eagan\'s background in the industry is deep and diverse. In 1983 he accepted a position as Senior Vice President with Empire Agency, Inc., a fully owned subsidiary of Empire of America, a western New York based thrift, which expanded across five states before its assets were acquired by M&T and KeyCorp in 1989. He was instrumental in pioneering the sales culture within Empire\'s retail branch network that nurtured and developed the sales of financial products by licensed representatives.</p><p>In 1988 he accepted the position of Senior Vice President with Investor Life Services. At that time ILS was a fully owned distribution subsidiary of USF&G Insurance Corporation, which was subsequently reorganized as Old Mutual Financial Network. One of his major accomplishments at ILS was the launching and developing of the company\'s Financial Institution Division. As a result, ILS was quickly recognized as one of the leading product distributors within the financial institution industry.</p><p>Mr. Eagan joined BankMark in 1991, a leading financial institution third party marketing organization, as Executive Vice President of National Business Development. Mr. Eagan\'s tenure at BankMark resulted in growing the company from a limited regional focus, to a national footprint. Conseco subsequently acquired BankMark in 1997.</p><p>From 1997 until joining Midwood in the year 2000, Mr. Eagan was retained as an exclusive consultant to Liberty Mutual Insurance Company. His product development and sales support recommendations were implemented by Liberty, which resulted in the substantial growth of the company\'s revenues through the expansion of product distribution in a national network of financial institutions.</p><p>The latest phase of his career began when he joined Midwood Financial Services, Inc. He, together with Mr. Alan Blank, developed Midwood into the largest privately held distributor of financial products and services via the bank and brokerage channels in the United States. He skillfully negotiates with leading insurers and other product providers to offer the best and most consumer friendly financial products to Midwood\'s partners for retail distribution. He is the co-architect of Midwood\'s philosophy of harmonizing with its business partners\' goals, objectives, culture and ethics.</p><p>Mr. Eagan is honored to have appointed by the Governor of New York to the Niagara Frontier Transportation Authority\'s Board of Commissioners to oversee the cost control initiatives of the region\'s transit system and the Greater Buffalo Niagara International Airport. In addition the Governor also requested the he serve on the Buffalo-Fort Erie International Bridge Commission. He is also a member of the Board of Directors of Erie County\'s SPCA.</p>'
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