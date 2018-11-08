<template>
    <a type="button" class="btn btn-danger btn-sm" v-on:click='doDelete()'>Delete from Platform</a>
</template>

<script>
    export default {
        props: ['delete_url'],
        mounted() {
            
        }, 
        methods: {
            doDelete() {
                if(confirm("This will remove this journal from this platform. Are you certain you wish to proceed?")) {
                    axios.post(this.delete_url).then(function() {
                        location.reload(true);
                    });
                }
            }
        }
    }
</script>