import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import frLocale from '@fullcalendar/core/locales/fr';



// Assurez-vous que Tippy.js est chargé pour afficher les tooltips
import tippy from 'tippy.js';
import 'tippy.js/dist/tippy.css'; // N'oubliez pas d'importer le style de Tippy.js

document.addEventListener('DOMContentLoaded', function () {

    // Calendrier pour la vue semaine (Cours)
    var calendarElSemaine = document.getElementById('calendar-semaine');
    if (calendarElSemaine) {
        var calendarSemaine = new Calendar(calendarElSemaine, {
            plugins: [dayGridPlugin, timeGridPlugin],
            initialView: 'timeGridWeek',  // Vue semaine
            locale: frLocale,
            eventDisplay: 'block',
            slotMinTime: "07:00:00", // 🔥 ajout ici
            slotMaxTime: "23:00:00", // 🔥 ajout ici
            allDaySlot: false,
            eventSources: [{
                url: '/api/cours', // API des cours
                method: 'GET',
                success: function(data) {
                    console.log("Événements chargés: ", data);
                },
                failure: function() {
                    console.log("Échec du chargement des événements.");
                }
            }],
            displayEventTime: false,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridWeek',
            },
            dayHeaderFormat: { weekday: 'long' },
            views: {
                timeGridWeek: {
                    displayEventTime: true,
                    titleFormat: { weekday: 'long' },
                }
            },
            eventClick: function (info) {
                console.log('Événement cliqué :', info.event);
                const pdfUrl = info.event.extendedProps.pdfUrl;
                if (pdfUrl) {
                    window.open(pdfUrl, '_blank');
                }
            },
            eventDidMount: function (info) {
                if (info.event.extendedProps.description && typeof tippy === 'function') {
                    console.log('Création du tooltip pour:', info.el);
                    tippy(info.el, {
                        content: info.event.extendedProps.description,
                        theme: 'light',
                        animation: 'scale',
                        delay: [100, 0],
                        placement: 'top',
                        trigger: 'mouseenter focus',
                    });
                } else {
                    console.log('Pas de description ou Tippy.js n\'est pas chargé');
                }
            }
        });

        console.log('Calendrier Semaine prêt');
        calendarSemaine.render();
    }

    // Calendrier pour la vue mois (Événements)
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin],
            initialView: 'dayGridMonth',
            locale: frLocale,
            eventDisplay: 'block',
            displayEventTime: false,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            eventSources: [
                // ✅ Opérations en fond
                {
                    url: '/api/operations',
                    method: 'GET',
                    extraParams: {},
                    failure: function () {
                        console.log("Échec du chargement des opérations.");
                    },

                    eventDataTransform: function(eventData) {
            eventData.display = 'background';
            return eventData;
        }
                    
                },
                // 🎯 Événements classiques
                {
                    url: '/api/evenements',
                    method: 'GET',
                    failure: function () {
                        console.log("Échec du chargement des événements.");
                    }
                }
            ],
            views: {
                dayGridMonth: {
                    displayEventTime: false,
                    eventTimeFormat: { hour: 'numeric', minute: '2-digit', hour12: false },
                    eventContent: function (arg) {
                        const backgroundColor = arg.event.backgroundColor || arg.event.extendedProps.color || '#3788d8';

                        const innerDiv = document.createElement('div');
                        innerDiv.textContent = arg.event.title;
                        innerDiv.style.backgroundColor = backgroundColor;
                        innerDiv.style.color = 'white';
                        innerDiv.style.padding = '2px';
                        innerDiv.style.borderRadius = '3px';

                        return { domNodes: [innerDiv] };
                    }
                }
            },
            eventClick(info) {
                const pdfUrl = info.event.extendedProps.pdfUrl;
                if (pdfUrl) window.open(pdfUrl, '_blank');
            },
            eventDidMount(info) {
                if (info.event.extendedProps.description && typeof tippy === 'function') {
                    tippy(info.el, {
                        content: info.event.extendedProps.description,
                        theme: 'light',
                        animation: 'fade',
                        delay: [100, 0],
                        placement: 'top',
                        trigger: 'mouseenter focus',
                        maxWidth: '250px',
                        interactive: true,
                        arrow: true,
                        zIndex: 9999,
                    });
                }
            }
        });

        calendar.render();
    }

});
