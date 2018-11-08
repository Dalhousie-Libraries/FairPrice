<template>
    <div class="container">
        <div v-if='message != ""' class="panel panel-danger">
            <div class="panel-body">{{message}}</div>
        </div>
        <button class='btn btn-success' v-on:click="doSubmit()">Save</button>
        <fieldset>
            <legend>Basic Information</legend>
            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                <label for='journal_title'>Journal Title:</label>
                </div>
                <div class='col-md-9'>
                <input id='journal_title' type='text' v-model='journal.journal_title' style='width:100%'/><br/>
                </div>
            </div>

            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='journal_eissn'>Electronic ISSN:</label>
                </div>
                <div class='col-md-3'>
                    <input id='journal_eissn' type='text' v-model='journal.e_issn' style='width:100%'/>
                </div>
                
                <div class='col-md-offset-2 col-md-1'>
                    <label for='journal_pissn'>Print ISSN:</label>
                </div>
                <div class='col-md-3'>
                    <input id='journal_pissn' type='text' v-model='journal.p_issn' style='width:100%'/>
                </div>
            </div>

            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='journal_jup'>JUP:</label>
                </div>
                <div class='col-md-3'>
                    <input id='journal_jup' maxlength="9" type='text' v-on:keypress="isNumber(event)" v-model='journal.jup' style='width:100%'/>
                </div>
                <div class='col-md-offset-2 col-md-1'>
                    <label for='journal_doi'>DOI:</label>
                </div>
                <div class='col-md-3'>
                    <input id='journal_doi' type='text' v-model='journal.doi' style='width:100%'/>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='journal_abbreviation'>Abbreviation:</label>
                </div>
                <div class='col-md-3'>
                    <input id='journal_abbreviation' type='text' v-model='journal.abbreviation' style='width:100%'/>
                </div>
                <div class='col-md-offset-2 col-md-1'>
                    <label for='proprietary_identifier'>Proprietary Identifier:</label>
                </div>
                <div class='col-md-3'>
                    <input id='proprietary_identifier' type='text' v-model='journal.proprietary_identifier' style='width:100%'/>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='journal_url'>URL:</label>
                </div>
                <div class='col-md-9'>
                    <input id='journal_url' type='text' v-model='journal.url' style='width:100%'/>
                </div>
            </div>
            <div class='row' v-if='admin'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='journal_fund'>Fund:</label>
                </div>
                <div class='col-md-9'>
                    <input id='journal_fund' type='text' v-model='journal.fund' style='width:100%'/>
                </div>
            </div>
            <div class='row' v-if='admin'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='journal_domain'>Domain:</label>
                </div>
                <div class='col-md-9'>
                    <select id='journal_domain' v-model='journal.domain' style='width:100%'>
                        <option value='AH'>Arts and Humanities</option>
                        <option value='SS'>Social Sciences</option>
                        <option value='BM'>Biomedical</option>
                        <option value='NSE'>Natural Science and Engineering</option>
                    </select>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='journal_status'>Status:</label>
                </div>
                <div class='col-md-9'>
                    <input id='journal_status' type='text' v-model='journal.journal_status' style='width:100%'/>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='historical_choices'>Subscription Years:</label>
                </div>
                <div class='col-md-9'>
                    <input id='historical_choices' type='text' v-model='subscription_years' v-on:change='validateYears()' style='width:100%'/>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-3 col-md-offset-11'>
                    <p class='text-danger' v-if='!subscription_years_valid'>This field must contain only years separated by comma (,), please check your input (EG: 2017,2018,2019)</p>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Subjects</legend>
            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='subject_1'>Subject One:</label>
                </div>
                <div class='col-md-9'>
                    <select id='subject_1' v-model='journal.subject_1'>
                        <template v-for='(s, index) in subjects'>
                            <option :value='s.subject'>{{s.subject}}</option>
                        </template>
                    </select><br/>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='subject_2'>Subject Two:</label>
                </div>
                <div class='col-md-9'>
                    <input id='subject_2' type='text' v-model='journal.subject_2'></input>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='subject_3'>Subject Three:</label>
                </div>
                <div class='col-md-9'>
                    <input id='subject_3' type='text' v-model='journal.subject_3'></input>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='subject_4'>Subject Four:</label>
                </div>
                <div class='col-md-9'>
                    <input id='subject_4' type='text' v-model='journal.subject_4'></input>
                </div>
            </div>
            <div class='row' v-if='admin'>
                <div class='col-md-offset-1 col-md-2'>
                    <label for='subject_4'>User Subject:</label>
                </div>
                <div class='col-md-9'>
                    <input id='subject_4' type='text' v-model='journal.user_subject'></input>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Faculty and Departments</legend>
            <div class='row'>
                <div class='col-md-offset-1 col-md-11'>
                    <h4>Faculties</h4>
                    <div class='row'>
                        <template v-for='(faculty, index) in faculties'>
                            <div class='col-md-4'>
                                <input type='checkbox' :id='"faculty-" + faculty.id' v-model='faculty.bool'/>
                                <label :for='"faculty-" + faculty.id''>{{faculty.faculty_name}}</label>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-offset-1 col-md-11'>
                    <h4>Departments</h4>
                    <div class='row'>
                        <template v-for='(department, index) in getFilteredDepartments()'>
                            <div class='col-md-4'>
                                <input type='checkbox' :id='"department-" + department.id' v-model='department.bool'/>
                                <label :for='"department-" + department.department_id''>{{department.department_name}}</label>
                            </div>
                        </template>
                        <div v-if='getFilteredDepartments().length == 0' class='col-md-12' style='text-align:center'>
                            <p>The faculty or faculties you have selected do not have associated departments to choose from.</p>
                        </div>
                    </div>
                </div>
            </div>

        </fieldset>

        <fieldset>
            <legend>Retention</legend>
                <table class='table' style='width:100%'>
                    <thead>
                            <th v-for="library in libraries">{{library.library_name}}</th>
                    </thead>
                    <tr>
                            <td v-for='(library, index) in libraries'>
                                <input type='checkbox' :id='"library-" + library.id' v-model='library.retentionbool'/>
                            </td>
                    </tr>
                </table>    
        </fieldset>
        <fieldset>
            <legend>Print Holding Libraries</legend>
                <table class='table' style='width:100%'>
                    <thead>
                            <th v-for="library in libraries">{{library.library_name}}</th>
                    </thead>
                    <tr>
                            <td v-for='(library, index) in libraries'>
                                <input type='checkbox' :id='"libraryprint-" + library.id' v-model='library.printbool'/>
                            </td>
                    </tr>
                </table>    
        </fieldset>
        <fieldset v-if='admin'>
            <legend>Flags</legend>
            <table class='table' style='width:100%'>
                <thead>
                    <th>Priority?</th>
                    <th>Subscribed?</th>
                    <th>Recommendation?</th>
                    <th>Consultation?</th>
                    <th>Print Access?</th>
                </thead>
                <tr>
                    <td><input type='checkbox' v-model='journal.is_priority'/></td>
                    <td><input type='checkbox' v-model='journal.is_subscribed'/></td>
                    <td><input type='checkbox' v-model='journal.is_recommendation'/></td>
                    <td><input type='checkbox' v-model='journal.is_consultation'/></td>
                    <td><input type='checkbox' v-model='journal.is_print_access'/></td>
                </tr>
            </table>
        </fieldset>


        <button class='btn btn-success' v-on:click="doSubmit()">Save</button>
    </div>
</template>

<script>
    export default {
        props: ["id", "admin", "prop_journal", "prop_libraries", "prop_subjects", "prop_departments", "prop_faculties"],
        data: function() {
            return {
                journal: JSON.parse(this.prop_journal),
                libraries: JSON.parse(this.prop_libraries),
                subjects: JSON.parse(this.prop_subjects),
                faculties: JSON.parse(this.prop_faculties),
                departments: JSON.parse(this.prop_departments),
                subscription_years: "",
                subscription_years_valid: true,
                message: "",
            }
        },
        created: function() {
            this.populate_selected();
            this.parse_subscription_years();
        },
        computed: {
        },
        methods: {
            getFilteredDepartments() {
                return this.departments.filter(function(item) {
                    if(item.department_name == "N/A") return false;
                    if(this.faculties[item.faculty_id -1]) { 
                        return this.faculties[item.faculty_id-1].bool;
                    } else {
                        return false;
                    }
                }, this); 
            },
            validateYears: function() {
                    this.subscription_years = this.subscription_years.replace(';', ',');
                    this.subscription_years_valid = /^[0-9,]*$/.test(this.subscription_years);
            },
            populate_selected: function() {
                this.faculties.forEach(function(item, index, arr) {
                    Vue.set(arr[index], 'bool', false);
                });
                this.departments.forEach(function(item, index, arr) {
                    Vue.set(arr[index], 'bool', false);
                });
                this.journal.faculties.forEach(function(item){
                    this.faculties[item.id - 1].bool=true;
                }, this);
                this.journal.departments.forEach(function(item){
                    this.departments[item.id - 1].bool=true;
                }, this);

            },
            parse_subscription_years: function() {
                var self=this;
                this.journal.historical_choices.forEach(function(item) {
                    self.subscription_years += item.subscription_year + ";";
                }, self);
            },
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();
                } else {
                return true;
                }
            },
            doSubmit: function() {
                this.getLibraryBitFlags();
                var data = {
                    journal:this.$data.journal,
                    faculties: this.faculties.filter(function(item) {
                            if(item.bool) return true;
                        }),
                    
                    departments: this.departments.filter(function(item) {
                            if(item.bool && this.faculties[item.faculty_id - 1].bool) return true;
                        }, this)
                };
                axios.post('https://' + document.location.hostname + "/edit/journal/" + this.journal.id, data).then(function (response) {
                    if(response.data.status == "success") window.location = response.data.redirect;
                    if(response.data.status == "fail") this.message = response.data.message;
                }.bind(this));
            },
            setLibraryBitFlags: function() {
                var journal = this.journal;
                this.libraries.forEach(function(library, index) {
                    if((journal.retained_by & library.bit_value) > 0) {
                        library.retentionbool=true;
                    } else {
                        library.retentionbool=false;
                    }

                    if((journal.libraries_holding_print & library.bit_value) > 0) {
                        library.printbool=true;
                    } else {
                        library.printbool=false;
                    }
                })
                
            },
            getLibraryBitFlags: function() {
                var journal = this.journal;
                var retain = 0;
                var print = 0;
                this.libraries.forEach(function(library, index) {
                    if(library.retentionbool) {
                        retain += library.bit_value;
                    } 
                    if(library.printbool) {
                        print += library.bit_value;
                    } 
                })
                this.journal.retained_by = retain;
                this.journal.libraries_holding_print = print;
                
            }
        },

    }
</script>
