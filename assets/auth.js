!function d(i,a,c){function u(t,e){if(!a[t]){if(!i[t]){var n="function"==typeof require&&require;if(!e&&n)return n(t,!0);if(s)return s(t,!0);var r=new Error("Cannot find module '"+t+"'");throw r.code="MODULE_NOT_FOUND",r}var o=a[t]={exports:{}};i[t][0].call(o.exports,function(e){return u(i[t][1][e]||e)},o,o.exports,d,i,a,c)}return a[t].exports}for(var s="function"==typeof require&&require,e=0;e<c.length;e++)u(c[e]);return u}({1:[function(e,t,n){"use strict";document.addEventListener("DOMContentLoaded",function(e){var t=document.getElementById("church-show-auth-form"),n=document.getElementById("church-auth-container"),r=document.getElementById("church-auth-close");t.addEventListener("click",function(){n.classList.add("show"),t.parentElement.classList.add("hide")}),r.addEventListener("click",function(){n.classList.remove("show"),t.parentElement.classList.remove("hide")})})},{}]},{},[1]);
//# sourceMappingURL=auth.js.map
// document.addEventListener('DOMContentLoaded', function (e) {
//     const showAuthBtn = document.getElementById('church-show-auth-form'),
//         authContainer = document.getElementById('church-auth-container'),
//         close = document.getElementById('church-auth-close');
        
//         authForm = document.getElementById('church-show-form'),
//         status = document.getElementById('data-message="status"'),

//         showAuthBtn.addEventListener('click', () => {
//         authContainer.classList.add('show');        
//         showAuthBtn.parentElement.classList.add('hide');
//     });

//     close.addEventListener('click', () => {
//         authContainer.classList.remove('show');
//         showAuthBtn.parentElement.classList.remove('hide');
//     });

//     authForm.addEventListener('submit', e =>{
//         e.preventDefault();


//         resetMessages();

//         let data = {

//             name: authForm.querySelector('[name="username"]').value,
//             password: authForm.querySelector('[name="password"]').value,
//             nonce: authForm.querySelector('[name="nonce"]').value,
//         }

//         if( !data.name || !data.password){
//             status.innerHTML = "Missing data";
//             status.classList.add('error');
//         }

//         let url = authForm.dataset.url;
//         let params = new URLSearchParams( new FromData(authForm));

//         authForm.querySelector('[name="submit"]').value = "loggin...";
//         authForm.querySelector('[name="submit"]').disabled = true;

//         fetch(url, {
//             method: "POST",
//             body: params
//         }).then(res =>res.json())
//             .catch(error => {
//                 resetMesseges()
//             })
//             .then(response => {
//                 resetMessages();

//                 if(response === 0 || !response.status){
//                     status.innerHTML = response.message;
//                     status.classList.add('error');

//                     return;
//                 }
//                 status.innerHTML = response.message;
//                 status.classList.add('sucess');
//                 authForm.reset();

//                 window.location.reload();
//             })
//     });


//     function resetMesseges(){

//         status.innerHTML = "";
//         status.classList.remove('sucess', 'error');

//         authForm.querySelector('[name="submit"]').value = "Login";
//         authForm.querySelector('[name="submit"]').disabled = false;


//     }
// });