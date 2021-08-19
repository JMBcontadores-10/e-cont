/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

const bootstrap = require('./bootstrap');
window.axios = require('axios');

import {Calendar} from '@fullcalendar/core'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

// let addEventModal = new bootstrap.Modal(document.querySelector("#saveEventModal"))
// let infoEvent;

window.onload = function(){

    var calendarE1 = document.getElementById('calender')

    var calendar = new Calendar(calendarE1, {
        selectable:true,
        editable:true,
        locale: 'es',

        plugins: [dayGridPlugin, interactionPlugin],
        events: [

        ],

    select: function(arg){

        let infoEvent = arg
        addEventModal.show()
    },

    eventClick:function(info){
        console.log("Click" +info)
        console.log("Click" +info.event.title)
        console.log("Click" +info.event.start)
        info.el.style.backgroundColor= 'blue'
        info.el.style.borderColor= '#FF0000'
    },

    eventDragStart:function(info){
        console.log("eventDragStart" +info)
        console.log("eventDragStart" +info.event.title)
        console.log("eventDragStart" +info.event.start)
        info.el.style.backgroundColor= 'red'
    },

    eventDragStop:function(info){
        console.log("eventDragStop" +info)
        console.log("eventDragStop" +info.event.title)
        console.log("eventDragStop" +info.event.start)

    },

    eventDrop:function(info){
        console.log("eventDrop" +info)
        console.log("eventDrop" +info.event.title)
        console.log("eventDrop" +info.event.start)

    },

    eventResize:function(info){
        console.log("eventResize" +info.event.start)
        console.log("eventResize" +info.event.end)

        if(!confirm("Esta ok?")){
            info.revert()
        }

    },

    })

    calendar.render()

    calendar.addEvent({

        title:'Evento prueba',
        start : '2021-07-20',
        end: '2021-07-22',
        color: '#18D915'
    })

    document.getElementById("addEvent").addEventListener('click', ()=>{
        calendar.addEvent({
            title:'Evento prueba',
            start : infoEvent.start,
            end: infoEvent.end,
            color: '#18D915'
        })
    })
}

// window.Vue = require('vue').default;

// /**
//  * The following block of code may be used to automatically register your
//  * Vue components. It will recursively scan this directory for the Vue
//  * components and automatically register them with their "basename".
//  *
//  * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
//  */

// // const files = require.context('./', true, /\.vue$/i)
// // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

// /**
//  * Next, we will create a fresh Vue application instance and attach it to
//  * the page. Then, you may begin adding components to this application
//  * or customize the JavaScript scaffolding to fit your unique needs.
//  */

// const app = new Vue({
//     el: '#app',
// });
