// import axios from 'axios';
// window.axios = axios;

// window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'; 
import _ from 'lodash';
import axios from 'axios';
import $ from 'jquery';

// Ensure jQuery is loaded
if (typeof $ === 'undefined') {
    throw new Error('jQuery is required.');
}

// Ensure Axios is loaded
if (typeof axios === 'undefined') {
    throw new Error('Axios is required.');
}

window._ = _; // Only if absolutely needed globally
window.axios = axios; // Only if absolutely needed globally

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
// window.axios.defaults.withCredentials = true;
// window.axios.defaults.withXSRFToken = true;

