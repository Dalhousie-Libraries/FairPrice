<template>
    <div>
        <div v-if='(distance < (15 * 60 * 1000)) && (distance > 0)'>
            <div class='panel panel-warning'>
                <div class='panel-heading'>
                    <h3>Warning: Session Expiring Soon</h3>
                </div>
                <div class='panel-body'>
                    <p>For security reasons, your session will expire in: {{timer}}. Please save your work and refresh this page to continue using Dalhousie Journal Assessment Database.</p>
                </div>
            </div>
        </div>
        <div v-if='distance <= 0'>
            <div class='panel panel-danger'>
                <div class='panel-heading'>
                    <h3>Session Expired</h3>
                </div>
                <div class='panel-body'>
                    <p>You have been logged out of the Dalhousie Journal Assessment Database. Please refresh this page to log in again.</p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['expiry'],
        data: function() {
            return {
                countdowndate: this.expiry,
                distance: 999999999999999999999999999,
                timer: null,
                starttime: null,
                timerinterval: null,
            }
        },
        created: function() {
            // Set the date we're counting down to
            this.countdowndate = new Date(this.expiry / 1).getTime();
            
            // Update the count down every 1 second
            this.timerinterval = setInterval(function() {
                
                // Get todays date and time
                this.starttime = new Date().getTime();

                // Find the distance between now an the count down date
                this.distance = this.countdowndate - this.starttime;

                // Time calculations for days, hours, minutes and seconds
                var days = Math.floor(this.distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((this.distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((this.distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((this.distance % (1000 * 60)) / 1000);

                // Display the result in the element with id="demo"
                this.timer = minutes + "m " + seconds + "s ";

                // If the count down is finished, write some text 
                if (this.distance <= 0) {
                    console.log("Countdown expired");
                    clearInterval(this.timerinterval);
                }
            }.bind(this), 1000);
        }
                    
    }

</script>