// app.js
import './styles/app.scss';
import 'bootstrap/dist/js/bootstrap.bundle.min';

const $ = require('jquery');
// this "modifies" the jquery module: adding behavior to it
// the bootstrap module doesn't export/return anything


// or you can include specific pieces
// require('bootstrap/js/dist/tooltip');
// require('bootstrap/js/dist/popover');
import './bootstrap';

import '@popperjs/core'
let  $j= jQuery.noConflict();

$(document).ready(function() {
/*    $('[data-toggle="popover"]').popover();*/
});

// path is relative to this file - e.g. assets/images/logo.png
import logoPath from '/assets/img/logo.png';

let html = `<img src="${logoPath}" alt="logo">`;