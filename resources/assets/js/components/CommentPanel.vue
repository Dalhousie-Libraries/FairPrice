<template>
    <div>
        <template v-if='!edit'>
            <p v-if='message != ""' class='text-success'>{{message}}</p>
            <p>{{comments}}</p>
            <p  v-if='comments == ""'>There are no comments yet for this journal.</p>
            <div v-if='is_admin' class='row'>
                <div class='col-md-12' style='text-align:center'>
                    <button class='btn btn-info' v-on:click='edit=true'>Edit</button>
                </div>
            </div>
        </template>
        <template v-if='edit'>
            <h4>Edit Comments</h4>
            <p v-if='error_message != ""' class='text-danger'>{{message}}</p>
            <div class='row' style='text-align:center'>
                <textarea v-model='comments' style='width:80%' ></textarea>
            </div>
            <div class='row' style='text-align:center'>
                <div class='col-md-6'>
                    <button class='btn btn-warning' v-on:click='doUndo()'>Undo</button>
                </div>
                <div class='col-md-6'>
                    <button class='btn btn-success' v-on:click='doSubmit()'>Save</button>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    export default {
        props: ["current_comments", "journal_id", "is_admin", "comment_url"],
        data: function() {
            return {
                original_comments:this.current_comments,
                comments:this.current_comments,
                error_message:"",
                message:"",
                edit:false,
                timer: null,
            }
        },

        created: function() {
            
        },
        methods: {
            doSubmit: function() {
                var data = {
                   comments: this.comments,
                };
                axios.post(this.comment_url, data).then(function (response) {
                    if(response.data.status == "success") {
                        this.original_comments = this.comments;
                        this.edit=false;
                        this.error_message = "";
                        this.message = response.data.message;
                        var self=this;
                        this.timer = setTimeout(function() { 
                            self.message="";
                        }, 5000, self);
                    }
                    if(response.data.status == "fail") {
                        this.error_message = response.data.message;
                        var self=this;
                        this.timer = setTimeout(function() { 
                            self.error_message="";
                        }, 5000, self);
                    }
                }.bind(this));
            },
            doUndo: function() {
                this.comments = this.original_comments;
                this.message = "";
                this.error_message = "";
                this.edit = false;
            }
        },

    }
</script>
