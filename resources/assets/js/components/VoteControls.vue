<template>
    <div>
        <div v-if='!voted'>
            <div class='row'>
                <div class='col-md-12'>
                    <label for='commentstxt'>Comments</label>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-12'>
                    <input style='width:100%;margin-bottom:4px' type='text' id='commentstxt' v-model='comments'></input><br/>
                </div>
            </div>
            
            <div class='row' style='width:100%;padding:5px'>
                <div class='col-md-12'>
                    <button type='button' class='btn btn-notneeded' style='width:100%;padding:5px' v-on:click='voteSubmit(0)'>Not Needed</button><br/>
                </div>
            </div>
            <div class='row' style='width:100%;padding:5px'>
                <div class='col-md-12'>
                    <button type='button' class='btn btn-wanted' style='width:100%;padding:5px' v-on:click='voteSubmit(1)'>Wanted</button><br/>
                </div>
            </div>
            <div class='row' style='width:100%;padding:5px'>
                <div class='col-md-12'>
                    <button type='button' class='btn btn-needed' style='width:100%;padding:5px' v-on:click='voteSubmit(2)'>Needed</button><br/>
                </div>
            </div>
        </div>
        <div v-if='voted'>
            <div class='row'>
                <div class='col-md-12' style='width:100%;padding:5px'>
                    <button type='button' class='btn btn-danger' style='width:100%;padding:5px' v-on:click='voteDelete()'><i class="fa fa-trash" aria-hidden="true"></i> Erase this Recommendation</button>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-12' style='width:100%;padding:5px'>
                    <button type='button' class='btn btn-success' style='width:100%;padding:5px' v-on:click='gotoReview()'>Review My Recommendations</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['add_url', 'delete_url', 'ballot_url', 'journal_id', 'election_id', 'voted'],
        data: function() {
            return {
                comments: "",
            }
        },
        methods: {
            voteSubmit: function(type) {
                var data = {};
                data.vote = type;
                data.journal = this.journal_id;
                data.election = this.election_id;
                data.comments = this.comments;
                axios.post(this.add_url, data).then(function(response) {
                    location.reload(true);
                }.bind(this));
            },
            gotoReview: function() {
                window.location=this.ballot_url;
            },
            voteDelete: function() {
                var data = {};
                data.journal = this.journal_id;
                data.vote = -1;
                data.election = this.election_id;
                axios.post(this.delete_url, data).then(function(response) {
                    location.reload(true);
                }.bind(this));
            },
        }
    }

</script>