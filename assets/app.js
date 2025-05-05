import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import frLocale from '@fullcalendar/core/locales/fr';

document.addEventListener('DOMContentLoaded', function () {
    // Calendrier pour la vue semaine (Cours)
    var calendarElSemaine = document.getElementById('calendar-semaine');
    if (calendarElSemaine) {
        var calendarSemaine = new Calendar(calendarElSemaine, {
            plugins: [dayGridPlugin, timeGridPlugin],
            initialView: 'timeGridWeek',  // Vue semaine
            locale: frLocale,
            // events: '/api/cours', // ❌ SUPPRIMÉ car doublon
            eventDisplay: 'block',
            slotMinTime: "07:00:00", // 🔥 ajout ici
            slotMaxTime: "23:00:00", // 🔥 ajout ici
            eventSources: [{
                url: '/api/cours', // API des cours
                method: 'GET',
                success: function(data) {
                    console.log("Events loaded: ", data);
                },
                failure: function() {
                    console.log("Failed to load events.");
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
                    tippy(info.el, {
                        content: info.event.extendedProps.description,
                        theme: 'light',
                        animation: 'scale',
                        delay: [100, 0],
                        placement: 'top',
                        trigger: 'mouseenter focus',
                    });
                }
            }
        });

        console.log('Calendar ready');
        calendarSemaine.render();
    }

    // Calendrier pour la vue mois (événements) - tu peux laisser tel quel
    var calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        var calendar = new Calendar(calendarEl, {
            plugins: [dayGridPlugin, timeGridPlugin],
            initialView: 'dayGridMonth',
            locale: frLocale,
            events: '/api/evenements',
            eventDisplay: 'block',
            displayEventTime: false,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            views: {
                dayGridMonth: {
                    displayEventTime: false,
                    eventTimeFormat: { hour: 'numeric', minute: '2-digit', hour12: false },
                    eventContent: function(arg) {
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
            eventClick: function (info) {
                console.log('Événement cliqué :', info.event);
                const pdfUrl = info.event.extendedProps.pdfUrl;
                if (pdfUrl) {
                    window.open(pdfUrl, '_blank');
                }
            },
            eventDidMount: function (info) {
                if (info.event.extendedProps.description && typeof tippy === 'function') {
                    tippy(info.el, {
                        content: info.event.extendedProps.description,
                        theme: 'light',
                        animation: 'scale',
                        delay: [100, 0],
                        placement: 'top',
                        trigger: 'mouseenter focus',
                    });
                }
            }
        });

        console.log('Calendar ready');
        calendar.render();
    }

});
