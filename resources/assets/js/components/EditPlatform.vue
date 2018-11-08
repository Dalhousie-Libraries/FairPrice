<template>
    <div class="container">
        <button class='btn btn-success' v-on:click="doSubmit()">Save</button>
        <fieldset>
            <legend>Platform</legend>
            <div class='row'>
                <div class='col-md-8'>
                    <select v-model='p_id' v-on:change='retrieveThisPlatform();'>
                        <option value=''></option>
                        <option v-for='(platform, index) in platforms' :value='platform.id'>{{platform.name}}</option>                
                    </select><br/>
                </div>
            </div>
        </fieldset>
        <fieldset>
            <legend>Settings</legend>
            <div class='row'>
                <div class='col-md-3'>
                <label for='priority'>Priority Package:</label>
                </div>
                <div class='col-md-9'>                
                <input id='priority' type='checkbox' v-model='priority_package'/>            
                </div>
            </div>
            
            <div class='row'>
                <div class='col-md-3'>
                    <label for='perpetual'>Perpetual Access:</label>
                </div>
                <div class='col-md-9'>
                    <input id='perpetual' type='checkbox' v-model='perpetual_access'/>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                <label for='coverage'>Perpetual Access Coverage:</label>
                </div>
                <div class='col-md-9'>
                <input id='coverage' type='text' v-model='perpetual_access_coverage'/>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                <label for='aggregator'>Aggregator Platform:</label>
                </div>
                <div class='col-md-9'>                
                <input id='aggregator' type='checkbox' v-model='aggregator_platform'/>            
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                <label for='years'>Years Available:</label>
                </div>
                <div class='col-md-9'>                
                <input id='years' type='text' v-model='years'/>            
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                <label for='volumes-start'>Volumes:</label>
                </div>
                <div class='col-md-9'>
                <input id='volumes-start' type='text' v-on:keypress="isNumber(event)" v-model='start_volume'/>            
                <label for='volumes-end'> to </label>
                <input id='volumes-end' type='text' v-on:keypress="isNumber(event)" v-model='end_volume'/>            
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                <label for='is_embargo'>Embargo:</label>
                </div>
                <div class='col-md-9'>
                <input id='is_embargo' type='checkbox' v-model='is_embargo'/>       
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                <label for='embargo_length'>Embargo Length:</label>
                </div>
                <div class='col-md-9'>                
                <input id='embargo_length' type='text' v-model='embargo_length'/>       
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                <label for='embargo_updated'>Embargo Updated:</label>
                </div>
                <div class='col-md-9'>                
                <input id='embargo_updated' type='text' v-model='embargo_updated'/>       
                </div>
            </div>            
        </fieldset>
        <button class='btn btn-success' v-on:click="doSubmit()">Save</button>
    </div>
</template>

<script>
    export default {
        mounted: function() {
            var self = this;
            $.getJSON('https://' + document.location.hostname + '/api/subject/', function(returned_subjects){
                self.subjects = returned_subjects;
            });
        },
        props: ['platform_id', 'journal_id'],
        data: function() {
            return {
                platform:{},
                platforms:[],
                perpetual_access:"",
                perpetual_access_coverage:"",
                aggregator_platform:false,
                priority_package:false,
                years:"",
                start_volume:"",
                end_volume:"",
                is_embargo: false,
                embargo_length: "",
                embargo_updated: "",
                p_id:this.platform_id
            }
        },
        created: function() {
            this.retrievePlatforms();
            this.retrieveThisPlatform();
            
        },
        computed: {
        },
        methods: {
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
                var data = {
                    platform_id: this.platform_id,
                    journal_id: this.journal_id,
                    perpetual_access: this.perpetual_access,
                    perpetual_access_coverage:this.perpetual_access_coverage,
                    aggregator_platform:this.aggregator_platform,
                    priority_package:this.priority_package,
                    years: this.years,
                    start_volume: this.start_volume,
                    end_volume:this.end_volume,
                    is_embargo: this.is_embargo,
                    embargo_length: this.embargo_length,
                    embargo_updated: this.embargo_updated
                };

                    axios.post('https://' + document.location.hostname + "/edit/journal/" + this.journal_id + "/platform/" + this.p_id, data).then(function(response) {
                        if(response.data.status == "success") window.location = response.data.redirect;
                    }.bind(this));
            },
            retrievePlatforms() {
                axios.get('https://' + document.location.hostname + "/api/platform").then(function(response) {
                    this.platforms=response.data;
                }.bind(this));
            },
            retrieveThisPlatform() {
                axios.get('https://' + document.location.hostname + "/api/platform/" + this.p_id + "/withjournal/" + this.journal_id).then(function(response) {
                    this.platform=response.data;
                    if(this.platform.pivot) {
                        this.perpetual_access = this.platform.pivot.perpetual_access;
                        this.perpetual_access_coverage = this.platform.pivot.perpetual_access_coverage;
                        this.priority_package = this.platform.pivot.priority_package;
                        this.aggregator_platform = this.platform.pivot.aggregator_platform;
                        this.years = this.platform.pivot.years;
                        this.start_volume = this.platform.pivot.start_volume;
                        this.end_volume = this.platform.pivot.end_volume;
                        this.is_embargo = this.platform.pivot.is_embargo;
                        this.embargo_length = this.platform.pivot.embargo_length;
                        this.embargo_updated = this.platform.pivot.embargo_updated;
                    } else {
                        this.perpetual_access = false;
                        this.perpetual_access_coverage = "";
                        this.priority_package = false;
                        this.aggregator_platform = false;
                        this.years = "";
                        this.start_volume = "";
                        this.end_volume = "";
                        this.is_embargo = false;
                        this.embargo_length = "";
                        this.embargo_updated = "";
                    }
                }.bind(this));
            }
            
        },

    }
</script>
