<template>
    <table class='table' style='width:100%'>
        <thead>
            <th>Year</th>
            <th>Price</th>
            <th>Cost Per Use</th>
            <th>Adjusted Cost Per Use</th>
            <th>Options</th>
        </thead>
        <tr v-for='price in prices'>
                <td>{{price.report_year}}</td>
                <td>{{price.price + price.currency}}</td>
                <td>{{price.cost_per_use}}</td>
                <td>{{price.adjusted_cost_per_use}}</td>
                <td></td>
        </tr>
        @endforeach
    </table>        
</template>

<script>
    export default {
        props: ['journal_id'],
        data: function() {
            return {
                prices: {};
            };
        }
        mounted: function() {
        },
        methods: getPrices() {
            axios.get('https://' + document.location.hostname + "/api/journal/" + journal_id + "/prices).then(function(response) {
                this.prices = response;
            }.bind(this));
        }
    }
</script>
