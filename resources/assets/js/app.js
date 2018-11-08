/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
Vue.use(require('vue-resource'))
    /**
     * Next, we will create a fresh Vue application instance and attach it to
     * the page. Then, you may begin adding components to this application
     * or customize the JavaScript scaffolding to fit your unique needs.
     */

Vue.component('pricechart', require('./components/PriceChart.vue'));
Vue.component('reportrow', require('./components/ReportRow.vue'));
Vue.component('editjournalform', require('./components/EditJournal.vue'));
Vue.component('editplatformform', require('./components/EditPlatform.vue'));
Vue.component('editpriceform', require('./components/EditPrice.vue'));
Vue.component('votechart', require('./components/VoteChart.vue'));
Vue.component('votelist', require('./components/JournalVoteList.vue'));
Vue.component('journalselect', require('./components/JournalSelect.vue'));
Vue.component('deleteplatform', require('./components/DeletePlatform.vue'));
Vue.component('userwizard', require('./components/UserWizard.vue'));
Vue.component('minivote', require('./components/MiniVote.vue'));
Vue.component('sessioncountdown', require('./components/SessionCountDown.vue'));
Vue.component('votecontrols', require('./components/VoteControls.vue'));
Vue.component('commentpanel', require('./components/CommentPanel.vue'));


const app = new Vue({
    el: '#app'
});