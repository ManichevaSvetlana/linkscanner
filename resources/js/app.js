
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import Notifications from 'vue-notification'
import Vue from 'vue';
import { Table } from 'ant-design-vue';
import { Button } from 'ant-design-vue';
import { Icon } from 'ant-design-vue';
import { Popconfirm } from 'ant-design-vue';
import { Input } from 'ant-design-vue';
import { Pagination } from 'ant-design-vue';
import { Checkbox } from 'ant-design-vue';
import { Tag } from 'ant-design-vue';
Vue.use(Table);
Vue.use(Button);
Vue.use(Icon);
Vue.use(Popconfirm);
Vue.use(Input);
Vue.use(Notifications);
Vue.use(Pagination);
Vue.use(Checkbox);
Vue.use(Tag);

import 'ant-design-vue/dist/antd.css';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('links-resource', require('./components/app/links/Index.vue').default);
Vue.component('links-table', require('./components/app/links/Table.vue').default);
Vue.component('links-add-form', require('./components/app/links/NewElement.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.prototype.notifyError = function (title, message) {
    this.$notify({
        group: 'notify',
        title: title,
        text: message,
        type: 'warn'
    });
};

const app = new Vue({
    el: '#app'
});
