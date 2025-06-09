import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Import SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;
