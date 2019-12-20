<template>
    <div class="modal fade bd-modal-lg" id="modalEmployeeBio" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">                    
                    <h3>{{data.name}}, {{data.title}}</h3>                
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>                    
                </div>
                <div class="modal-body">
                    <img class="float-left img-thumbnail mr-3 mb-3" :src="'./site/assets/employees/'+data.img" :alt="data.name"/>
                    <div v-html="data.bio"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
module.exports = {
    props: ['data']
}
</script>
<style scoped>
.modal-dialog{
    overflow-y: initial !important
}
.modal-body{
    max-height: 400px;
    overflow-y: auto;
}
</style>