<template>
    <div width='100px' height='50px' style='width:100%'>
        <h5 id='controls'>Filter: <input id='filter_start' v-on:change="updateChart()" v-model='filter_start' type="date">   to   <input id='filter_end' v-on:change="updateChart()" v-model='filter_end' type="date"></h5>
        <center id='chart_error''>
            <h3>No Price Data To Display</h3>
            <p>We were unable to retrieve any price records for this journal. If you feel that this is in error, please refresh the page and try again.</p>
        </center>
        <canvas id='priceChart' style='width:100%;height:200px'></canvas>
    </div>
</template>

<script>
 export default {
        props: ['id'],
        data: function() {
            return {
                query:"",
                journallist: [],
                selectedvotelist:"",
                selectedunvotelist:"",
                focus:"unvoted",
                focusedrecord:"",
                ctx: "",
                mychart:"",
                data: "",
                filter_start: "",
                filter_end: ""
            }
        },
        computed: {
        },
        methods: {
            updateChart: function() {
                this.mychart.options.scales.xAxes[0].time.min = this.filter_start;
                this.mychart.options.scales.xAxes[0].time.max = this.filter_end;
                this.mychart.update();
            },
            filter_start_as_date: function() {
                if(this.filter_start == ""){ return "";}
                var year = this.filter_start.split("-")[0]
                var month = this.filter_start.split("-")[1]-1;
                var day = this.filter_start.split("-")[2]

                return new Date(year, month, day);
            },
            filter_end_as_date: function() {
                if(this.filter_end == ""){ return "";}
                var year = this.filter_end.split("-")[0]
                var month = this.filter_end.split("-")[1]-1;
                var day = this.filter_end.split("-")[2]

                return new Date(year, month, day);
            }
        },
        mounted() {
            var url = 'https://' + document.location.hostname + '/api/prices?id=' + this.id;
            var data = this.data;
            $.getJSON(url,function(result){
                    this.ctx = document.getElementById("priceChart");
                    data = result;
                    if(result == "" || result.datasets[0].data == Array || result.datasets[0].data.length < 0 ) {
                        console.log("Not trying to render");
                        $('#priceChart').hide();
                        $('#filter_start').hide();
                        $('#filter_end').hide();
                        $('#controls').hide();
                        $('#chart_error').show();
                    } else {
                        $('#chart_error').hide();
                        data.labels = data.labels.map(function(entry) {
                            return new Date(entry * 1000);
                        });
                        this.mychart = new Chart(this.ctx, {
                            type: 'line',
                            series: 'Price',
                            data: data,
                            options: {
                                tooltips: {
                                    callbacks: {
                                        title: function(tooltipItem, chartData) {
                                            return String(tooltipItem[0].xLabel).split(" ")[3];
                                        },
                                        label: function(tooltipItem, chartData) {
                                            return "Price: " + tooltipItem.yLabel;
                                        }
                                    }
                                },
                                scales: {
                                    xAxes: [{
                                        type:'time',
                                        display: 'true',
                                        time: {
                                            displayFormats: {
                                                day: 'MMM'
                                            },
                                            unit: 'month',
                                            distribution: 'linear'
                                        },
                                        ticks: {
                                            autoSkip: false,
                                        },
                                        scaleLabel: {
                                            labelString: 'Date'
                                        },
                                    }],
                                
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true,
                                            callback: function(value, index, values) {
                                                return "$" + value.toString();
                                            }
                                        },
                                        scaleLabel: {
                                            labelString: 'Price'
                                        },
                                    }]
                                }
                            }
                        });
                    }
                }.bind(this));
            
        }
    }
</script>

