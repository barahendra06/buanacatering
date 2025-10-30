<script>
	var take={{NOTIFICATION_LIMIT}}, skip={{NOTIFICATION_LIMIT}};
	$('#notificationMenu').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

			var data = { "take":take, "skip":skip }
			$.get("{{ route('get-notification-more', $currentMember->id) }}", data, function( data ) 
	        {
	        	console.log("take : " + take + " skip : " + skip);
	        	skip = skip + {{NOTIFICATION_LIMIT}};    

	        	for (var i = 0 ; i < data.notification.length; i++) 
	        	{
	        		// $('#notificationMenu').find(' > li:nth-last-child(1)').before(addNotification(data.notification[i]));
	        		$('#notificationMenu').find(' > li:nth-last-child(1)').append(addNotification(data.notification[i]));
	        		
	        		if(data.notification[i].is_read == 0)
	        		{
	        			$("#conversationRecipientItemWrapper"+data.notification[i].id).addClass('menu-notification-not-read');
	        		}

	        		if(data.notification[i].activity_type_id == {{ ACTIVITY_PREMIUM_COMMENT }})
	        		{
	                   	$("#imageNotifItemWrapper"+data.notification[i].id).html('<i class="fa fa-star fa-2x"></i>');
	        		}
	                else if(data.notification[i].activity_type_id == {{ ACTIVITY_PREMIUM }})
	                {
	                    $("#imageNotifItemWrapper"+data.notification[i].id).html('<i class="fa fa-star fa-2x star-silver"></i>');
	                }
	                else if(data.notification[i].activity_type_id == {{ ACTIVITY_WINNER }})
	                {
	                 	$("#imageNotifItemWrapper"+data.notification[i].id).html('<i class="fa fa-star fa-2x star-gold"></i>');
	                }
	                else
	                {
	                    $("#imageNotifItemWrapper"+data.notification[i].id).html('<img src="' + baseURL +"/"+ data.notification[i].sender.avatar_path + '" class="img-responsive">');
	                    $("#senderName"+data.notification[i].id).html(data.notification[i].sender.name);
	                }
	        	};
	        }).fail(function(){
	        	alert("Sorry.. Something wrong !! ");
	        	console.log();
	        });
	    }
	});

	function addNotification(notif)
	{
		var notification = "<li>"+
                                '<div id="conversationRecipientItemWrapper' + notif.id + '" class="row no-margin item-notification vertical-align">'+
                                    '<div id="imageNotifItemWrapper' + notif.id + '" class="col-xs-3 no-padding text-center ">'+
                                        
                                    '</div>'+
                                    '<div class="col-xs-9 no-padding">'+
                                        '<ul class="menu custom-menu">'+
                                            '<li>'+
                                                '<a href="' + notif.link + '" data-id="' + notif.id + '"  data-type="' + notif.activity_type_id + '" class="no-border notification-link" style="white-space:normal">'+
                                                    '<b id="senderName' + notif.id + '"></b> ' + notif.text +
                                                    '<div class="notif-date">' + notif.created_at_view + '</div>'+
                                                '</a>'+
                                            '</li>'+
                                        '</ul>'+
                                    '</div>'+
                                '</div>'+
                            '</li>';

		return notification;
	}

	$("#notificationMenu").on('click', 'a.notification-link', function(e){
	  	
		e.preventDefault();
		var type = $(this).data('type');
		var id = $(this).data('id');
        var href = $(this).data('href');
        console.log("read");
		$.get(baseURL + "/notification/{{ $currentMember->id }}/read/" + $(this).data('id'), function(data){

			if(type != {{ ACTIVITY_PREMIUM_COMMENT }} && 
				type != {{ ACTIVITY_PHOTO_APPROVED }} && 
				type != {{ ACTIVITY_GRAPHIC_APPROVED }} && 
				type != {{ ACTIVITY_POST_APPROVED }} && 
				type != {{ ACTIVITY_PREMIUM }} && 
				type != {{ ACTIVITY_WINNER }})   
    		{
                window.location.href = href;
            }
            $('#conversationRecipientItemWrapper'+id).removeClass('menu-notification-not-read')

		})

	});
</script>


@if(0)
<!-- Message Notification -->
<script>
	var takeConversation={{CONVERSATION_NOTIFICATION_LIMIT}}, skipConversation={{CONVERSATION_NOTIFICATION_LIMIT}};
	$('#newConversationRecipientMenu').on('scroll', function() {
        if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {

			var data = { "take":takeConversation, "skip":skipConversation }
			$.get("{{ route('get-conversation-more', $currentMember->id) }}", data, function( data ) 
	        {
	        	console.log("take : " + takeConversation + " skip : " + skipConversation);
	        	skipConversation = skipConversation + {{CONVERSATION_NOTIFICATION_LIMIT}};    

	        	for (var i = 0 ; i < data.conversationRecipients.length; i++) 
	        	{
	        		$('#newConversationRecipientMenu').find(' > li:nth-last-child(1)').append(addNewConversationRecipient(data.conversationRecipients[i]));
					

                    // $("#imageNotifItemWrapper"+data.notification[i].id).html('<img src="' + baseURL +"/"+ data.notification[i].sender.avatar_path + '" class="img-responsive">');
                    // $("#senderName"+data.conversationRecipients[i].id).html(data.notification[i].sender.name);

	        	};
	        }).fail(function(){
	        	alert("Sorry.. Something wrong !! ");
	        	console.log();
	        });
	    }
	});

	function addNewConversationRecipient(convRecipient)
	{
		var conversationRecipient = "<li>"+
		                                '<div id="conversationRecipientItemWrapper' + convRecipient.id + '" class="row no-margin item-notification vertical-align">'+
		                                    '<div id="imageConversationRecipientItemWrapper' + convRecipient.id + '" class="col-xs-3 no-padding text-center ">'+
		                                        '<img src="'+convRecipient.conversation.conversation_participant[0].member.avatar_path+'"'+
                                                        'class="img-responsive center-block image-sender-notification">'+
		                                    '</div>'+
		                                    '<div class="col-xs-9 no-padding">'+
		                                        '<ul class="menu custom-menu">'+
		                                            '<li>'+
		                                                '<a href="' + convRecipient.link + '" class="no-border notification-link" style="white-space:normal">'+
		                                                    '<b>'+convRecipient.conversation.conversation_participant[0].member.name+'</b> sent new message to you.'+
                                                            '<div class="notif-date">'+convRecipient.conversation.updated_at_view+'</div>'+
                                                            '<div class="badge bg-blue message-count">'+convRecipient.unreadMessageCount+'</div>'+
		                                                '</a>'+
		                                            '</li>'+
		                                        '</ul>'+
		                                    '</div>'+
		                                '</div>'+
		                            '</li>';
		return conversationRecipient;
	}

</script>
@endif