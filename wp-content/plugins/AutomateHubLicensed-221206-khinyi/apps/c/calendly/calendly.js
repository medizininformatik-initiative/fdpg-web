jQuery.noConflict();function isCalendlyEvent(e){return e.data.event&&e.data.event.indexOf('calendly')===0;};window.addEventListener('message',function(e){if(isCalendlyEvent(e)){if(e.data.event=="calendly.event_scheduled"){let eventId=e.data.payload.event.uri;eventId=eventId.substr(-36);let inviteeId=e.data.payload.invitee.uri;inviteeId=inviteeId.substr(-36);let baseUrl=window.location.origin;jQuery.ajax({url:`${baseUrl}/wp-json/automatehub/calendly/?event_uuid=${eventId}&invitee_uuid=${inviteeId}`,}).done(function(res){}).fail(function(err){});}}});