<template>
    <div>
        <div v-if='!mobile' class='row'>
            <div class="col-md-1">{{journal.e_issn}}</div>
            <div class="col-md-1">{{journal.p_issn}}</div>
            <div class="col-md-4" style='text-align:left'><a :href="'journal/' + journal.id" style='text-align:left'>{{journal.journal_title}}</a></div>
            <div v-if='election'class="col-md-6">
                <minivote :add_url='add_url' :delete_url='delete_url'
                            :ballot_url='ballot_url' :journal_id='journal_id'
                            :election_id='election_id' :voted='current_vote'></minivote>
            </div>
			<div v-else class="col-md-6">&nbsp;</div>
		</div>


        <div v-if='mobile' v-bind:class='{"row": true}'>
			<div class="col-xs-12">
            <div v-bind:class='{"panel": true,
                                "panel-default": true}' style='width:100%; float:left'>
                <div class="panel-heading">
                    <h4>
                        <a :href="'journal/' + journal.id" style='text-align:left'>{{journal.journal_title}}</a>
                    </h4>
                </div>
                <div v-bind:class='{
                    "panel-body": true,
                    "row-notneeded": vote_val == 0,
                    "row-wanted":vote_val == 1,
                    "row-needed":vote_val == 2}'>
                    <h5 style='text-align:left'>Electronic ISSN: {{journal.e_issn}}</h5>
                    <h5 style='text-align:left'>Print ISSN: {{journal.p_issn}}</h5>
                    <h5 style='text-align:left'><a :href="'journal/' + journal.id" style='text-align:left'>View Journal Details</a></h5>
                    <template v-if='election'>
                        <h5 style='text-align:left'>Quick Recommend</h5>
                        <minivote :add_url='add_url' :delete_url='delete_url'
                                    :ballot_url='ballot_url' :journal_id='journal_id'
                                    :election_id='election_id' :voted='current_vote' mobile='mobile'></minivote>
                    </template>
                </div>
            </div>
			</div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['add_url', 'delete_url', 'ballot_url', 'journal_id', 'election_id', 'voted', 'vote_val', 'mobile', 'election', 'prop_journal'],
        data: function() {
            return {
                current_vote: this.voted,
                comments: "",
                journal: JSON.parse(this.prop_journal),
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
                    if(response.data.result) {
                        this.current_vote = type;
                    } else {
                        window.alert("There was a problem recording your selection. Please refresh this page and try again.");
                    }
                }.bind(this));
            },
            
            voteDelete: function() {
                var data = {};
                data.journal = this.journal_id;
                data.vote = -1;
                data.election = this.election_id;
                axios.post(this.delete_url, data).then(function(response) {
                    if(response.data.result) {
                        this.current_vote = -1;
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
