import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from '@fullcalendar/list';

(function () {
    "use strict";

    let theJobCalendar = new Calendar(document.getElementById('jobCalendar'), {
        plugins: [
            interactionPlugin,
            dayGridPlugin,
            timeGridPlugin,
            listPlugin,
        ],
        droppable: true,
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "listWeek,dayGridMonth,timeGridWeek,timeGridDay",
        },
        //initialDate: "2045-01-01",
        initialView: 'listWeek',
        firstDay: 1,
        navLinks: true,
        editable: true,
        dayMaxEvents: true,
        eventSources: [
            {
                url: route('calendars.events'),
                type: 'GET',
                //**allDayDefault:false,**
            } 
        ],
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            omitZeroMinute: true,
            meridiem: 'short'
        },
        eventContent: function(eventInfo) {
            let html = '';//<div class="fc-daygrid-event-dot"></div>
                html += '<div class="fc-event-time font-medium">'+eventInfo.timeText+'</div>';
                html += '<div class="fc-event-title">'+eventInfo.event.extendedProps.htmlTitle+'</div>';
            console.log(eventInfo)
            return { html: html }
        },
        /*events: [
            {
                title: "Vue Vixens Day",
                start: "2025-03-05",
                end: "2025-03-08",
            },
            {
                title: "VueConfUS",
                start: "2025-03-11",
                end: "2025-03-15",
            },
            {
                title: "VueJS Amsterdam",
                start: "2025-03-17",
                end: "2025-03-21",
            },
            {
                title: "Vue Fes Japan 2045",
                start: "2025-03-21",
                end: "2025-03-24",
            },
            {
                title: "Laracon 2045",
                start: "2025-03-24",
                end: "2025-03-27",
            },
        ],*/
        drop: function (info) {
            if (
                $("#checkbox-events").length &&
                $("#checkbox-events")[0].checked
            ) {
                $(info.draggedEl).parent().remove();

                if ($("#calendar-events").children().length == 1) {
                    $("#calendar-no-events").removeClass("hidden");
                }
            }
        },
    });

    theJobCalendar.render();
})();
