<template>
    <div width='100px' height='50px' style='width:100%'>
        <div id="controls" style="display:none">
            <div class='col-xs-6'>
            <label for='electionselect'>Consultation:</label>
            <select id='electionselect' v-model='selectedsetindex' v-on:change='updateChart()'>
                <option v-for='(d, index) in data' :value='index'>{{d.name}}</option>
            </select>
            </div>
            <div class='col-xs-4'>
            <label for='showsubset'>Filter By:</label>
            <select id='showsubset' v-model='selectedsubset' v-on:change='updateChart()'>
                <option value=''>All</option>
                <option value=1>Students</option>
                <option value=2>Faculty</option>
                <option value=3>Staff</option>
                <option value=0>Other</option>
            </select>
            </div>
            <div class='col-xs-2'>
                <a :href='getDetailsLink()'>View All</a>
            </div>
        </div>
        <center v-show='!data'>
            <h3>No Vote Data To Display</h3>
            <p>We were unable to retrieve a price record for this chart. Please refresh the page and try again.</p>
        </center>
        <canvas id='voteChart' style='display:none;width:100%;height:450px'></canvas>
    </div>
</template>

<script>
 export default {
        props: ['id'],
        data: function() {
            return {
                ctx: "",
                mychart:"",
                original_data:"",
                data: {},
                selectedsetindex: 0,
                selectedsubset:"",
                dataloaded:false,
            }
        },
        computed: {

        },
        methods: {
            getDetailsLink: function() {
                if(this.dataloaded) {
                     return 'https://' + document.location.hostname + '/journal/' + this.id + '/votes/' + this.original_data[this.selectedsetindex].id;
                }
            },
            updateChart: function() {
                $.extend(true, this.data, this.original_data)
                this.mychart.data = this.filteredData();
                this.mychart.update();
            },
            filteredData: function() {
                var subset = this.data;
                subset = subset[this.selectedsetindex];
                var selectedsubset = this.selectedsubset;
                if(this.selectedsubset == "") {
                    return subset;
                }
                subset.datasets = [subset.datasets[selectedsubset]]
                return subset;
            }
        },
        mounted() {
            var url = 'https://' + document.location.hostname + '/api/votes?number=5&journal_id=' + this.id

            $.getJSON(url,function(result){
                    this.ctx = document.getElementById("voteChart");
                    this.original_data = $.extend(true, {}, result)
                    this.data = $.extend(true, {}, result);
                    if(this.data == "" || !this.data) {
                        $('#voteChart').hide();
                        $('#controls').hide();
                    } else {
                        $('#voteChart').show();
                        $('#controls').show();
                        this.selectedsetindex = 0;
                        this.mychart = new Chart(this.ctx, {
                            type: 'bar',
                            series: 'Vote Record',
                            data: this.data[0],
                            options: {
                                scales: {
                                    xAxes: [{
                                        stacked: true
                                    }],
                                    yAxes: [{
                                        stacked: true,
                                        ticks: {
                                            min: 0,
                                                callback: function(value, index, values) {
                                                if (Math.floor(value) === value) {
                                                    return value;
                                                }
                                            }      
                                        }
                                    }]
                                }
                            },
                            onAnimationComplete: function () {

                                ctx = this.ctx;
                                ctx.font = this.mychart.scale.font;
                                ctx.fillStyle = this.mychart.scale.textColor
                                ctx.textAlign = "center";
                                ctx.textBaseline = "bottom";

                                this.mychart.datasets.forEach(function (dataset) {
                                    dataset.bars.forEach(function (bar) {
                                        ctx.fillText(bar.value, bar.x, bar.y - 5);
                                    });
                                })
                            }
                            
                        });
                        this.dataloaded=true;
                    }
                }.bind(this));
            
        }
    }
</script>

