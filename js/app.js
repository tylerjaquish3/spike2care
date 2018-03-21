(function($) {

	"use strict";

	var options = {
        events_source: 'getEvents.php',
        view: 'month',
        tmpl_path: 'tmpls/',
        tmpl_cache: false,
        day: 'now',
        onAfterEventsLoad: function(events) {
            if(!events) {
                return;
            }
            var list = $('#eventlist');
            list.html('');

            $.each(events, function(key, val) {
                $(document.createElement('li'))
                    .html('<a href="' + val.url + '">' + val.title + '</a>')
                    .appendTo(list);
            });
        },
        onAfterViewLoad: function(view) {
            $('.page-header h3').text(this.getTitle());
            $('.btn-group button').removeClass('active');
            $('button[data-calendar-view="' + view + '"]').addClass('active');
        },
        classes: {
            months: {
                general: 'label'
            }
        }
    };

    var calendar = $('#calendar').calendar(options);

    $('.btn-group button[data-calendar-nav]').each(function() {
        var $this = $(this);
        $this.click(function() {
            calendar.navigate($this.data('calendar-nav'));
        });
    });

    $('.btn-group button[data-calendar-view]').each(function() {
        var $this = $(this);
        $this.click(function() {
            calendar.view($this.data('calendar-view'));
        });
    }); 

	$('#first_day').change(function(){
		var value = $(this).val();
		value = value.length ? parseInt(value) : null;
		calendar.setOptions({first_day: value});
		calendar.view();
	});

	$('#language').change(function(){
		calendar.setLanguage($(this).val());
		calendar.view();
	});

	$('#events-in-modal').change(function(){
		var val = $(this).is(':checked') ? $(this).val() : null;
		calendar.setOptions({modal: val});
	});
	$('#format-12-hours').change(function(){
		var val = $(this).is(':checked') ? true : false;
		calendar.setOptions({format12: val});
		calendar.view();
	});
	$('#show_wbn').change(function(){
		var val = $(this).is(':checked') ? true : false;
		calendar.setOptions({display_week_numbers: val});
		calendar.view();
	});
	$('#show_wb').change(function(){
		var val = $(this).is(':checked') ? true : false;
		calendar.setOptions({weekbox: val});
		calendar.view();
	});
	// $('#events-modal .modal-header, #events-modal .modal-footer').click(function(e){
		//e.preventDefault();
		//e.stopPropagation();
	// });
}(jQuery));

// Show the modal for the event. This is called from tmpls/month-day.html onclick event
function showModal(eventId) {

    $.ajax({
        cache: true,
        type: 'GET',
        url: 'includes/getEventDetails.php',
        data: {
            EID: eventId
        },
        success: function(data) 
        {
            var response = jQuery.parseJSON(data);

            var formattedDate = new Date(response.event_date);
            var d = formattedDate.getDate();
            var m =  formattedDate.getMonth()+1;
            var y = formattedDate.getFullYear();

            $('#eventsModal').modal('show')
            $('#eventTitle').html(response.title);
            $('#price').html('$ '+response.price+' <small>(per team)</small>');
            $('#eventDate').html(m+"/"+d+"/"+y);
            $('#location').html(response.location);
            $('#address').html('<small>'+response.address+', '+response.city+'</small>');
            $('#format').html(response.format);
            $('#description').html(response.description);
            $("#eventImage").attr("src","images/events/"+response.image_path);
            $('#meetingTime').html("Captain's Meeting: "+response.meeting_time);
            $('#playTime').html("Play Starts: "+response.play_time);
            $('#maxTeams').html("Max Teams: "+response.max_teams);
            $("#register").attr("href","register.php?id="+eventId);
        }
    });
};
