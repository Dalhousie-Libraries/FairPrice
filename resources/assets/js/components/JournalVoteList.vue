<template>
    <div>
        <div id='tgt_list'></div>
        <div v-if='!voted || voted.length == 0'>
            <h3>Your Recommendations</h3>
            <div class='row col-md-8 col-md-offset-2'>
                <div class='panel panel-info'>
                    <div class='panel-heading'>
                        <h4>Consultation in Progress</h4>
                    </div>
                    <div class="panel-body">
                        <p>It looks like you haven't made any recommendations in this consultation. 
                        Click <a :href='browse_url'>here</a> to browse the database. </p>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <div id='page-1'>
                <div class='row'>
                    <div class='panel panel-default'>
                        <div class='panel-heading'>
                        <h3>Review Your Recommendations</h3>
                        </div>
                        <div class='panel-body'>
                            <p class='col-md-12'>Your current recommendations are listed below.</p>
                            <p class='col-md-12'>Click on the journal title to:</p>
                            <ul>
                                <li>Revise your recommendation</li>
                                <li>Add a comment</li>
                            </ul>
                            <p class='col-md-12'>Use the download button to keep a record of your current choices</p>
                            <p class='col-md-12'>You can also take a break and come back later.</p>
                            <p class='col-md-12'><b>Your current recommendation will be saved and you can return to edit them anytime before {{election_end}}</b></p>
                            <p class='col-md-12'>Click on Finalize Recommendations to submit your final recommendations.</p>
                        </div>
                        <div class='panel-footer'>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div id='journal_list'>
                        <div class='col-md-5'>
                            <div class='panel panel-default float-left'>
                                <div class='panel-heading'>
                                    <h3>Your Recommendations</h3>
                                </div>
                                <div class='panel-body review-panel' style="max-height:40.75em">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                                                <i class="fa fa-download" aria-hidden="true"></i> Download <i class="fa fa-caret-down" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                                <a v-on:click='download("xlsx")'>
                                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Microsoft Excel 2007 (XLSX)
                                                </a>
                                            </li>
                                            <li>
                                                <a v-on:click='download("xls")'>
                                                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Microsoft Excel (XLS)
                                                </a>
                                            </li>
                                            <li>
                                                <a v-on:click='download("csv")'>
                                                    <i class="fa fa-table" aria-hidden="true"></i> Comma-separated values (CSV)
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                    
                                        <template v-for="(vote, index) in voted">
                                            
                                            <div v-bind:class='{"row":true,
                                                                "report-row": true}'  style='padding:5px;'>
                                                <div class='col-md-1'>{{index + 1}}.</div>
                                                <div style='color:black;margin-left:5px;margin-right:5px;'>
                                                    <template v-if='is_mobile'>
                                                        <div class="col-md-7"><h4><a v-on:click='focusJournal(vote)' style="cursor: pointer;">{{vote.journal.journal_title}}</a></h4></div>
                                                    </template>
                                                    <template v-else>
                                                        <div class="col-md-7"><a v-on:click='focusJournal(vote)' style="cursor: pointer;">{{vote.journal.journal_title}}</a></div>
                                                    </template>
                                                    <div v-bind:class='{"col-md-4": true,
                                                            "row-notneeded": vote.vote == 0,
                                                            "row-wanted":vote.vote == 1,
                                                            "row-needed":vote.vote == 2}' style='color:#fff;'>{{shortVoteText(vote.vote)}}</div>
                                                </div>
                                            </div>
											<hr width="100%" style="margin-bottom: 0px;margin-top: 0px;" />
                                        </template>

                                </div>
                                <div class='panel-footer'>
                                    
                                </div>
                                <!-- <select style='width:100%' id='ballot' v-model="selectedvote" size='30'> -->
                                <!--    <option class='ballotentry' v-for="(vote, index) in voted" v-on:click='focusJournal(index, $event)' :value="vote.journal.id">{{vote.journal.journal_title}}</option> -->
                                <!-- </select> -->
                            </div>
                        </div>
                    </div>
                    <div id='journal_details' class='col-md-7 hidden'>
                        <div v-if="focusedrecord != '' && focusedrecord != null" class='panel panel-default float-right'>
                            <div class='panel-heading'>
                                <h3>Your recommendation for: {{focusedrecord.journal.journal_title}}</h3>
                            </div>
                            
                            <div class='panel-body'>
                                <button v-if='is_mobile' class='btn btn-primary' v-on:click='backToList()'>Back To List</button>
								<p><span v-if="focusedrecord.journal.fullurl != null && focusedrecord != ''"><a appearance="Button" target="_blank" :href="focusedrecord.journal.fullurl" class="btn btn-info btn-sm" style="float: left; padding-left: 20px; padding-right: 20px;">View This Journal</a></span></p><br />
                                <p><h4>Journal Details</h4>
                                <b>Electronic ISSN:</b> {{focusedrecord.journal.e_issn}}<br />
                                <b>Print ISSN:</b> {{focusedrecord.journal.p_issn}}</p><br />
                                <p><h4>Current Recommendation</h4>
                                <b>Recommendation:</b> {{getVoteText(focusedrecord.journal.vote)}}<br />
                                <b>Comments:</b> {{focusedrecord.comments}}</p><br />
                                <p><h4>Modify your Recommendation</h4></p>

                                <div class='row'>
                                    <label class="col-md-2" for="Needed">Needed</label>
                                    <input class="col-md-1" id="Needed" type="radio" v-model="modifiedvote"  v-bind:value="2">
                                </div>
                                <div class='row'>
                                    <label class="col-md-2" for="Wanted">Wanted</label>
                                    <input class="col-md-1" id="Wanted" type="radio" v-model="modifiedvote"  v-bind:value="1">
                                </div>
                                <div class='row'>
                                    <label class="col-md-2" for="NotNeeded">Not Needed</label>
                                    <input class="col-md-1" id="NotNeeded" type="radio" v-model="modifiedvote"  v-bind:value="0">
                                </div>                        
                                <div class='row'>
                                    <label class="col-md-1" for="comments">Comments:</label>
                                </div>
                                <div class='row'>
                                    <textarea id='comments' class="col-md-offset-1 col-md-10" v-model="modifiedcomments"/><br/><br/>
                                </div>
                                <div class='row' style='padding-top:15px'>
                                    <div class='col-md-12'>
                                        <div class='col-md-3'>
                                            <button class="btn btn-warning" style='left-padding:5%;right-padding:5%' v-on:click='focusJournal(focusedrecord)'><i class="fa fa-undo" aria-hidden="true"></i> Undo Changes</button>
                                        </div>
                                        <div class='col-md-3 col-md-offset-1'>
                                        <button class="btn btn-primary" style='left-padding:5%;right-padding:5%'  v-on:click='updateVote(focusedrecord)'>
                                            <template v-if='saved'><i class="fa fa-check" aria-hidden="true"></i>Saved</template><template v-else><i class="fa fa-floppy-o" aria-hidden="true"></i> Save Changes</template>
                                        </button>
                                        </div>
                                        <div class='col-md-3 col-md-offset-1'>
                                        <button class="btn btn-danger" style='left-padding:5%;right-padding:5%'  v-on:click='unvoteFor(focusedrecord)'><i class="fa fa-trash" aria-hidden="true"></i> Delete Recommendation</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='panel-footer'>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-1'>
                        <button id='page-4-btn' class='btn btn-primary' style='float:left' v-on:click="changeWizardPage(2)">Finalize Recommendations</button>
                    </div>
                </div>
            </div>
            <div id='page-2' class='hidden'>
                <div class='panel panel-default'>
                    <div class='panel-heading'>
                        <h3>Finalize and Submit</h3>
                    </div>
                    <div class='panel-body'>
                        <p>You have made recommendations about {{voted.length}} {{journal_term}}.</p>
                        <p>You can submit your recommendations <b>once</b>.</p>
                        <p>You can take a break and come back later. Your current recommendations will be saved and you can revise them any time before {{election_end}}.</p>
                        <p>If you would like to revise your recommendations, click on the Review My Recommendations button.</p>
                        <p>To submit your final recommendations, click on the Submit button.</p>
                        <p>You can only submit recommendations <b>ONCE</b> in 2018.</p>
                        <p><b>You will not be able to change your recommendations after you click submit.</b></p>
                        <div class="row">
                            <div class='col-md-1'>
                                <button id='page-3-back-btn' class='btn btn-primary' style='float:left' v-on:click="changeWizardPage(1)">Review my Recommendations</button>
                            </div>
                            <div class='col-md-1 col-md-offset-10'>
                                <button id='submit-btn' class='btn btn-success' style='float:right' v-on:click="submitVote()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
 export default {
        props: ['name', 'save_url', 'delete_url', 'submit_url', 'initial_votes', 'election_id',
                'browse_url', 'is_mobile', 'finalize', 'election_end'],
        mounted: function() {
            if(this.finalize == 'true') {
                this.wizardpage = 2;
                this.changeWizardPage(2);
            }
        },
        data: function() {
            return {
                focusedrecord:"",
                modifiedvote: "",
                modifiedcomments: "",
                selectedvote:"",
                clean_votes: JSON.parse(this.initial_votes),
                voted: JSON.parse(this.initial_votes),
                wizardpage: 1,
                saved: false,
            }
        },
        created: function() {

                $('#ballot').focus();
            
        },
        computed: {
            is_dirty: function () {
                if(this.focusedrecord) {
                    var i = this.findCleanVote(this.focusedrecord);
                    if(this.focusedrecord.comments != this.clean_votes[i].comments)  {
                        return true;
                    }
                    if(this.focusedrecord.vote != this.clean_votes[i].vote) {
                        return true;
                    }
                    return false;
                }
            },
            journal_term: function() {
                if(this.voted.length == 1) {
                    return "journal";
                } else {
                    return "journals";
                }
            }
        },
        methods: {
            getVoteText: function() {
                if(this.focusedrecord.vote == -1) return "You have not cast a vote for this journal";
                if(this.focusedrecord.vote == 0) return "You do not require this journal";
                if(this.focusedrecord.vote == 1) return "You would like to maintain access to this journal";
                if(this.focusedrecord.vote == 2) return "You need to maintain access to this journal";
                return "Undefined Vote Condition";
            },
            shortVoteText: function(vote) {
                if(vote == 0) return "Not Needed";
                if(vote == 1) return "Wanted";
                if(vote == 2) return "Needed";
                return "Undefined Vote Condition";
            },
            focusJournal: function(vote) {
                this.focusedrecord = vote;
                this.modifiedvote = this.focusedrecord.vote;
                this.modifiedcomments = this.focusedrecord.coments;
                $('#journal_details').removeClass('hidden');
                if(this.is_mobile) {
                    $('#journal_list').addClass('hidden');
                    $('#journal_details').removeClass('hidden');
                }
            },
            backToList: function() {
                this.focusedrecord = "";
                this.modifiedvote = "";
                this.modifiedcomments = "";
                if(this.is_mobile) {
                    $('#journal_list').removeClass('hidden');
                    $('#journal_details').addClass('hidden');
                }
            },
            showModal: function() {
                $('#journalSelectModal').modal('show');
            },
            download: function(format) {
                var url = window.location.href;    
                if (url.indexOf('?') > -1){
                    url += '&download=' + format
                }else{
                    url += '?param=' + format
                }
                window.location.href = url;
            },
            scrollToPoint: function(point) {
                    var offset = point.offset().top - $(window).scrollTop();
                    if(offset > window.innerHeight){
                        // Not in view
                        $('html,body').animate({scrollTop: offset}, 1000);
                        return false;
                    }
                return true;
            },
            findCleanVote: function(journal) {
                var i=0;
                for(i=0;i<this.clean_votes.length;i++) {
                    if(this.clean_votes[i].journal.journal_title == journal.journal.journal_title) {
                        return i;
                    } 
                }
                return -1;
            },
            findVote: function(journal) {
                var i=0;
                for(i=0;i<this.voted.length;i++) {
                    if(this.voted[i].journal.journal_title == journal.journal.journal_title) {
                        return i;
                    } 
                }
                return -1;
            },
            unvoteFor: function(journal) {
                var i = this.findVote(journal);
                var ci = this.findCleanVote(journal)
                var data = {};
                data.journal = this.focusedrecord.journal.id;
                data.vote = -1;
                data.election = this.election_id;
                var self = this;
                axios.post(this.delete_url, data).then(function(response) {
                    self.focusedrecord="";
                    if(i != -1) {
            	        self.voted.splice(i, 1);
                        self.clean_votes.splice(ci, i);

                    }
                    if(self.is_mobile) {
                        $('#journal_list').removeClass('hidden');
                        $('#journal_details').addClass('hidden');
                    }
                });   
            },
            updateVote: function(journal) {
                var data = {};

                data.vote = this.modifiedvote;
                data.journal = this.focusedrecord.journal.id;
                data.election = this.election_id;
                data.comments = this.modifiedcomments;
                var self = this;
                axios.post(this.save_url, data).then(function(response) {
                    self.saved=true;
                    self.focusedrecord.vote = data.vote;
                    self.focusedrecord.comments = data.comments;
                    var alsoself = self;
                    setTimeout(function(){
                        alsoself.saved = false;
                    }, 2000);
                });   
            },
            changeWizardPage: function(page) {
                this.wizardpage=page;
                $('#page-' + 1).addClass('hidden');
                $('#page-' + 2).addClass('hidden');
                $('#page-' + page).removeClass('hidden');
                if(page == 1) {
                    $('#ballot').focus();
                }
            },
            submitVote: function() {
                if(confirm("You are about to finalize your recommendations for this consultation. Once finalized, you will be unable to change your vote. Do you want to cast your vote?")) {
                    var data = {};
                    data.election_id = this.election_id;
                    axios.post(this.submit_url, data).then(function (response) {
                        // Success
                        console.log(response);
                        if(response.data.result == true) {
                            window.location.href=response.data.redirect_url;
                        } else {
                            //Vote not submitted
                        }
                    },function (response) {
                        // Error
                        console.log(response.data)
                    });

                }
            }

        },
    }
</script>

