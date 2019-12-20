<template>
<div class="container-fluid">
    <heading></heading>
    <navigation></navigation>
    
    <div class="row">
        <div class="col">
            <h1 class="border-bottom pb-2">Legal Notice</h1>
        </div>
    </div>
    <div class="row">
        <div class="col">         
            <p>This website is intended for use only by the broker/dealer, its financial advisors and other financial professionals. The materials contained in this 
            website are presented “as is” and “as available”, provided for informational purposes only, and subject to change without notice.</p>

            <h3>No Warranty</h3>
            <p>Midwood Financial Services, Inc. intends for the information contained in this website to be accurate and up to date. However, errors sometimes occur. 
            Therefore, the information contained herein is presented without expressed or implied warranties of any kind. In no event shall Midwood Financial Services,
            Inc. be liable for any direct or indirect damages arising out of the use or inability to use the materials on this website.</p>

            <p>The information is not intended as, nor should it be construed as, legal, tax, investment or professional advice. Please consult your broker dealer for
            guidance on insurance, investment, legal or tax matters.</p>

            <hr>

            <h3>Fixed Annuity Disclosure</h3>

            <p>Fixed Annuity Interest rates may be changed by the issuing insurance company at anytime without notice, and the rates set forth herein may not be
            available at the point of sale. Annuity products are not approved in all states.</p>

            <p>Fixed Annuities are:</p>

            <ul>
                <li>Not a deposit</li>
                <li>Not FDIC insured</li>
                <li>Not insured by any government agency</li>
                <li>Not guaranteed by the bank</li>
                <li>May go down in value</li>
            </ul>

            <hr>

            <h3>Outside Website Links</h3>

            <p>From time to time, Midwood Financial Services, Inc. may provide hyperlinks to websites entirely independent of this website, and are provided for
            convenience only. No affiliation with Midwood Financial Services should be inferred or assumed. The responsibility for the accuracy or content of any
            website for which a hyperlink is provided is the responsibility of the owner of the website.</p>

            <h3>Viruses and Malicious Code</h3>
            <p>Midwood Financial Services, Inc. does not guarantee or warrant that files available for downloading from this website are free of viruses or other 
            code that has contaminating or destructive properties.</p>

            <h3>Access and Availability</h3>
            <p>No warranty or representation is made that this Site or any part or function herein will be available and not subject to interruption at any time.</p>

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
        foot: httpVueLoader('./common/foot.vue')
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
</style>