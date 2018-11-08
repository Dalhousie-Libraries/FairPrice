<template>
    <div class="container">
        <button class='btn btn-success' v-on:click="doSubmit()">Save</button>
        <fieldset>
            <legend>Price</legend>
            <div class='row' v-if='create'>
                <div class='col-md-3'>
                    <label for='report_year'>Year:</label>
                </div>
                <div class='col-md-9'>                
                    <input id='report_year' type='text' v-on:keypress="isNumber" v-model='report_year'/>            
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                    <label for='price'>Price:</label>
                </div>
                <div class='col-md-9'>
                    <input id='price' type='text' v-on:keypress="isNumber" v-model='price_value'/>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                    <label for='currency'>Currency:</label>
                </div>
                <div class='col-md-9'>
                    <input id='currency' type='text' v-model='currency'/>
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                    <label for='cost_per_use'>Manual Cost Per Use:</label>
                </div>
                <div class='col-md-9'>                
                    <input id='cost_per_use' type='text' v-model='cost_per_use'/>            
                </div>
            </div>
            <div class='row'>
                <div class='col-md-3'>
                <label for='adjusted_cost_per_use'>Adjusted Cost Per Use:</label>
                </div>
                <div class='col-md-9'>                
                <input id='adjusted_cost_per_use' type='text' v-model='adjusted_cost_per_use'/>            
                </div>
            </div>
        </fieldset>
        <button class='btn btn-success' v-on:click="doSubmit()">Save</button>
        
    </div>
</template>

<script>

    export default {
        mounted: function() {

        },
        props: {
            create: {
                default: false,
            },
            current_price: {
                default: {}
            },
            href: {
                default: ""
            },
            journal: {

            },
        },
        data: function() {
            return {
                report_year:"",
                price: JSON.parse(this.current_price),
                price_value: "",
                currency: "",
                cost_per_use: "",
                adjusted_cost_per_use: "",
                journal_id: "",
            }
        },
        created: function() {
            this.journal_id = this.journal;
            this.report_year = this.price.report_year;
            this.price_value = this.price.price;
            this.currency = this.price.currency;
            this.cost_per_use = this.price.cost_per_use;
            this.adjusted_cost_per_use = this.price.adjusted_cost_per_use;
        },
        computed: {
        },
        methods: {
            isNumber: function(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                    evt.preventDefault();;
                } else {
                    return true;
                }
            },
            doSubmit: function() {
                var data = {
                    id: this.price.id,
                    journal_id: this.journal_id,
                    report_year: this.report_year,
                    price_value: this.price_value,
                    currency: this.currency,
                    cost_per_use: this.cost_per_use,
                    adjusted_cost_per_use: this.adjusted_cost_per_use,
                };

                axios.post(this.href, data).then(function(response) {
                    if(response.data.status == "success") window.location = response.data.redirect;
                    if(response.data.status == "fail") console.log("ERROR");
                }.bind(this));
            }
            
        },

    }
</script>
