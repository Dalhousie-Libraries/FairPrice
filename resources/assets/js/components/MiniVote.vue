<template>
    <div>
        <div v-if='mobile'>
            <div v-if='current_vote == -1'>
                <div class='col-md-12'>
                    <button type='button' class='btn btn-notneeded btn-sm' style='width:100%;padding:5px' v-on:click='voteSubmit(0)'><i class="fa fa-check" aria-hidden="true" v-if='current_vote == 0'></i><b> Not Needed</b></button><br/>
                </div>
                <div class='col-md-12'>
                    <button type='button' class='btn btn-wanted btn-sm' style='width:100%;padding:5px' v-on:click='voteSubmit(1)'><i class="fa fa-check" aria-hidden="true" v-if='current_vote == 1'></i><b> Wanted</b></button><br/>
                </div>
                <div class='col-md-12'>
                    <button type='button' class='btn btn-needed btn-sm' style='width:100%;padding:5px' v-on:click='voteSubmit(2)'><i class="fa fa-check" aria-hidden="true" v-if='current_vote == 2'></i> <b>Needed</b></button><br/>
                </div>
            </div>
            <div class='recommendation-container' v-else>
                <div v-bind:class='{"col-md-10": true,
                                    "recommendation": true,
                                    "row-notneeded": current_vote == 0,
                                    "row-wanted": current_vote == 1,
                                    "row-needed": current_vote == 2}' 
                    style='text-align:center; width:100%'>
                    <b>{{getVoted}}</b>
                </div>
                <div class='col-md-2' style='text-align:center'>
                    <button type='button' data-toggle="tooltip" title="Undo Recommendation" class='btn btn-erase btn-sm' style='width:100%;padding:5px' v-on:click='voteDelete()'><i style='color:black' class="fa fa-times fa-lg" aria-hidden="true"></i></button>
                </div>
            </div>
        </div>
        <div v-else>
            <div class='col-md-8'>
                <div v-if='current_vote == -1'>
                    <button type='button' class='btn btn-notneeded btn-sm' style='padding:10px;width:30%;white-space:pre-wrap;' v-on:click='voteSubmit(0)'><i class="fa fa-check" aria-hidden="true" v-if='current_vote == 0'></i><b> Not Needed</b></button>
                    <button type='button' class='btn btn-wanted btn-sm' style='padding:10px;width:30%;white-space:pre-wrap;' v-on:click='voteSubmit(1)'><i class="fa fa-check" aria-hidden="true" v-if='current_vote == 1'></i><b> Wanted</b></button>
                    <button type='button' class='btn btn-needed btn-sm' style='padding:10px;width:30%;white-space:pre-wrap;' v-on:click='voteSubmit(2)'><i class="fa fa-check" aria-hidden="true" v-if='current_vote == 2'></i> <b>Needed</b></button>
                </div>
            </div>
            <div class='col-md-4'>
                <div v-if='current_vote != -1' class='recommendation-container' v-else>
                    <div class='recommendation' style='text-align:center;padding:10px;width:60%'>
                        <div v-bind:class='{"recommendation": true,
                            "row-notneeded": current_vote == 0,
                            "row-wanted": current_vote == 1,
                            "row-needed": current_vote == 2}' style='width:100%; white-space: pre-wrap;font-size: 12px;border-radius: 3px;padding:10px;margin-top:-10px;margin-bottom:-10px;'>
                            <b>{{getVoted}}</b>
                        </div>
                    </div>
                    <div class='recommendation' style='text-align:center; width:20%'>
                        <button type='button' data-toggle="tooltip" title="Undo Recommendation" class='btn btn-erase btn-sm' style='padding:10px;' v-on:click='voteDelete()'><i style='color:black' class="fa fa-times fa-lg" aria-hidden="true"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['add_url', 'delete_url', 'ballot_url', 'journal_id', 'election_id', 'voted', 'mobile'],
        data: function() {
            return {
                comments: "",
                current_vote: this.voted,
            }
        },
        
        methods: {
            voteSubmit: function(type) {
                var data = {};
                data.vote = type;
                data.journal = this.journal_id;
                data.election = this.election_id;
                data.comments = this.comments;
                if(this.type != this.$parent.current_vote) {
                    axios.post(this.add_url, data).then(function(response) {
                        if(response.data.result) {
                            this.current_vote = type;
                            this.$parent.current_vote = type;
                            $(".finalize").each(function(index) {
                                $(this).removeClass('hidden');
                            });
                            $('.btn-needed').blur();
                            $('.btn-notneeded').blur();
                            $('.btn-wanted').blur();
                        } else {
                            window.alert("There was a problem recording your selection. Please refresh this page and try again.");
                        }
                    }.bind(this));
                }
            },
            
            voteDelete: function() {
                var data = {};
                data.journal = this.journal_id;
                data.vote = -1;
                data.election = this.election_id;
                axios.post(this.delete_url, data).then(function(response) {
                    if(response.data.result) {
                        this.current_vote = -1;
                        this.$parent.current_vote = -1;
                    } else {
                        window.alert("There was a problem recording your selection. Please refresh this page and try again.");
                    }
                }.bind(this));
            },
        },
        computed: {
            getVoted: function() {
                if(this.current_vote == 0) { 
                    return "Not Needed"; 
                }
                if(this.current_vote == 1) { 
                    return "Wanted"; 
                }
                if(this.current_vote == 2) { 
                    return "Needed"; 
                }

            }
        }

    }

</script>
