<template>
	<div>
		<ol class="breadcrumb">
			<li v-for="link in breadcrumb" class="breadcrumb-item" :class="{'active': link.path == ''}">
				<span v-if="link.path == ''">{{link.name}}</span>
				<span v-if="link.path != ''"><a href="" v-on:click.prevent="$parent.openFolder(link.path)">{{link.name}}</a></span>
			</li>
		</ol>
	</div>
</template>
<script>
module.exports = {
	props: {
        path: {
            type: String,
            required: true
        }
    },
	computed: {
		breadcrumb: function() {			
			var p = this.path.split("/");
			//If the last element is empty, remove it
			if (p[p.length - 1] == '') { p.pop(); }			
			var out = [];			
			//Add a 'home' link to take us back to all documents if we're not in the home directory
			out[0] = {name: 'Home', path: '/files/'};
			var tmp = '/files/';
			for (i = 0; i < p.length; i++) {
				if (p[i] != '' && p[i] != 'files') { //Ignore the /files/ that's added when the route changes					
					tmp = tmp+p[i]+'/';
					out.push({name: p[i], path: tmp});									
				}
			}			
			out[out.length - 1].path = '';
			if (out.length > 1) {
				this.$parent.parentLink = out[out.length - 2];
			} else {
				this.$parent.parentLink = {};
			}			
			return out;
		}
	}
}
</script>
<style scoped>
.breadcrumb::before {
	content: "Location:";
	padding-right: 10px;
	font-weight: bold;
}

ol {
	margin-bottom: 0px;
}
</style>
