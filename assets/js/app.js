/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.css';
// import $ from 'jquery';
// global.$ = $;
import infosUser from './pageLogin';

let infos = infosUser();
// console.log(infos);
document.getElementById('infosUser').value = JSON.stringify(infos);