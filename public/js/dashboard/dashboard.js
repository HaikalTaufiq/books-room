document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        timeZone: 'Asia/Jakarta', 
        initialView: 'dayGridMonth',
        editable: false,
        selectable: false,
        events: '/calendar/events',
        eventDidMount: function(info) {
            info.el.setAttribute('title', info.event.title);
        },
        eventContent: function(arg) {
            const title = arg.event.title;
            return {
                html: `<div class="fc-event-custom">${title}</div>`
            };
        }
    });

    calendar.render();
});
