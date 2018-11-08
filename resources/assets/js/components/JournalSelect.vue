<template>
    <div id='journalSelectModal' class='modal fade' role='dialog'>
          <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Find Journal</h4>
                </div>
                <div class="modal-body">
                    <div class='row form-group'>
                        <div class='col-md-2'>
                            <label for='modal-titlesearch'>Title</label>
                            <input class='form-control input-sm' v-on:keyup.enter='doSearch()' type='text' id='modal-titlesearch' v-model='term'/><br/>
                        </div>
                        <div class='col-md-offset-1 col-md-9'>
                            <label for='modal-subjectsearch'>Subject</label>
                            <select id='modal-subjectsearch' v-on:keyup.enter='doSearch()' @change='doSearch()' v-model='subject'>
                                <option value=''>All</option>
                                <option v-for='(s, index) in subjects' :value='s.subject'>{{s.subject}}</option>
                            </select><br/>
                        </div>
                    </div>
                    
                        <button class='btn btn-primary' v-on:click='clearFilters()'>Clear Filters</button>
                        <button class='btn btn-success' v-on:click='doSearch()'>Search</button>
                    
                    <div v-if='results > 0'>
                        <div class='row'>
                                <div class='col-md-3'>
                                    <h3>Search Results</h3>
                                </div>
                        </div>
                        <h4>
                            <a v-on:click='changePage(current_page - 1)' style='margin-left:5px; margin-right:5px;'><<</a>
                            <span v-for='page in pagearray'>
                                <template v-if='page==current_page'>
                                    <b><u><a v-on:click='changePage(page)' style='margin-left:5px; margin-right:5px;'>{{page}}</a></u></b>
                                </template>
                                <template v-else>
                                    <a v-on:click='changePage(page)' style='margin-left:5px; margin-right:5px;'>{{page}}</a>
                                </template>
                            </span>
                            <a v-on:click='changePage(current_page + 1)' style='margin-left:5px; margin-right:5px;'>>></a>
                        </h4>
                        <table class='table table-bordered table-striped'>
                            <tbody>
                                <tr class='row' v-for="(journal, index) in journals">
                                    <td class='col-md-7'>
                                        {{journal.journal_title}}
                                    </td>
                                    <template v-if='$parent.findVote(journal) < 0'>
                                        <td class='col-md-5 hidden-xs hidden-sm text-center'>
                                            <button class='btn button btn-primary' v-on:click='castVote(journal, 2)'>Needed</button>
                                            <button class='btn button btn-primary' v-on:click='castVote(journal, 1)'>Nice to Have</button>
                                            <button class='btn button btn-primary' v-on:click='castVote(journal, 0)'>Not Needed</button>
                                        </td>
                                        <td class='col-md-5 visible-xs visible-sm text-center'>
                                            <button class='btn button btn-primary' v-on:click='castVote(journal, 2)'>Needed</button>
                                            <button class='btn button btn-primary' v-on:click='castVote(journal, 1)'>Nice to Have</button>
                                            <button class='btn button btn-primary' v-on:click='castVote(journal, 0)'>Not Needed</button>
                                        </td>
                                    </template>
                                    <template v-else>
                                        <td class='col-md-4 text-center'>
                                            <button type="button" class='btn button btn-success disabled'>Added</button>
                                        </td>
                                    </template>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div v-if='results == -1'>
                    <div class='row'>
                        <div class='text-center'>
                            <h3>No results found</h3>
                            <p>Please change your search criteria and try again</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>   
</template>

<script>


export default {
        
        mounted: function() {
            var self = this;

            $.getJSON('https://' + document.location.hostname + '/api/subject/', function(returned_subjects){
                self.subjects = returned_subjects.sort(function(a, b) {
                        if(a.subject < b.subject) return -1;
                        if(a.subject > b.subject) return 1;
                        return 0;
                        
                });
            });

            this.doSearch();
        },
        data: function() {
            return {
                subjects: [],
                subject: "",
                term: "",
                journals: [],
                first_page: 1,
                current_page: 1,
                last_page: 1,
                pagearray: [],
                results: 0,
            }
        },
        created: function() {

        },
        methods: {
            castVote: function(journal, value) {
                journal.vote_value = value;
                this.$parent.voted.push(journal)
            },
            clearFilters: function() {
                this.subject="";
                this.term="";
                this.doSearch();
            },
            doSearch: function(){
                var data = {
                    "subject": this.subject,
                    "term": this.term,
                };
                $.getJSON('https://' + document.location.hostname + '/api/journal/', data, function(result){
                    this.journals = [];
                    var j = this.journals;
                    if(result.from==null) {
                        this.results=-1;
                    } else {
                        this.results=result.from;
                    }
                    this.first_page = 1;
                    this.current_page = result.current_page;
                    this.last_page = result.last_page;
                    result.data.forEach(function(element, index) {
                        element.vote_value = 0;
                        j[index] = element;
                    });
                    this.journals = j;
                    this.getPageArray();
                    this.$forceUpdate();                    
                }.bind(this));
            },
            getPageArray: function(){
                var i = 0;
                var j = 0;
                this.pagearray = [];
                for(i=this.current_page - 5;j < 10 && j < this.last_page; i++) {
                    if (i > 0 && i <= this.last_page) {
                        this.pagearray.push(i);
                        j++;
                    } else {
                        if( i > this.last_page ) {
                            j=10;
                        }
                    }
                    
                }
            },
            changePage: function(page) {
                if(page > this.last_page) {
                    page = this.last_page;
                }
                if(page <= 0) {
                    page = 1;
                }
                var data = {
                    "subject": this.subject,
                    "term": this.term,
                    "page": page,
                };

                $.getJSON('https://' + document.location.hostname + '/api/journal/', data, function(result){
                    var j = this.journals;

                    this.first_page = 1;
                    this.current_page = result.current_page;
                    this.last_page = result.last_page;

                    result.data.forEach(function(element, index) {
                        element.vote_value = 0;
                        j[index] = element;
                    });

                    this.journals = j;
                    this.getPageArray();
                    this.$forceUpdate();                  
                }.bind(this));
            }

        },
        computed: {
        }
    }
</script>

