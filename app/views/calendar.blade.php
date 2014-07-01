@extends('layouts.default')

@section('content')

<script>
	$.post('https://www.googleapis.com/calendar/v3/freeBusy?key={AIzaSyA-B7NcVuhuJLedq4hVcgtcsXMopusz6oY}', {
		"timeMin" : "2014-06-20T10:00:00-05:00",
		"timeMax" : "2014-06-20T12:30:00-05:00",
		"items" : [{
			"id" : "dosomething.org_3732343239383232313533@resource.calendar.google.com"
		}],
		"timeZone" : "EST"
	})
</script>

@stop