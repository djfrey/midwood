<template>
    <div class="card mb-3">
        <div class="card-header">
            <h2><i :class="data.icon"></i>{{data.title}}</h2>
        </div>
        <ul v-if="data.items" class="list-group list-group-flush">
            <li class="list-group-item" v-for="i in data.items">{{i}}</li>
        </ul>
    </div>
</template>
<script>
module.exports = {
    props: ['data']
}
</script>
<style scoped>
li:hover {    
    color: #FFF;
    background-color: rgb(90, 140, 201);
    text-indent: 0.2rem;
}
h2 {
    margin-bottom: 0;
}
li {
    transition: all 0.3s;
}
i {
    padding-right: 20px;
    color: rgb(90, 140, 201);
}
</style>