require('./vue-assets');

Vue.component('create-examinee', require('./components/CreateExaminee.vue'));
Vue.component('view-examinees', require('./components/ViewExaminees.vue'));

var app = new Vue({
    el: '#my-app'
});

