/**
*
* @package mChat JavaScript Code mini
* @version 1.4.2 of 2011-01-10
* @copyright (c) 2010 By Rich McGirr (RMcGirr83) http://rmcgirr83.org
* @copyright (c) 2009 By Shapoval Andrey Vladimirovich (AllCity) ~ http://allcity.net.ru/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
**/

var $jQ=jQuery;

$jQ(function()
{
	if(!mChatArchiveMode)
	{	
		var scrH=$jQ('#mChatmain')[0].scrollHeight;
		$jQ('#mChatmain').animate({scrollTop:scrH},1000,'swing');
		if(mChatPause)
		{
			// remove refresh on input keypress
			$jQ('#mChatMessage').bind('keypress', function(){
				clearInterval(interval);
				$jQ('#mChatLoadIMG,#mChatOkIMG,#mChatErrorIMG').hide();
				$jQ('#mChatRefreshText').html(mChatRefreshNo).addClass('mchat-alert');
				$jQ('#mChatPauseIMG').show();

			});
		}

		// Prevent double submit
		//http://greatwebguy.com/programming/dom/prevent-double-submit-with-jquery/
		$jQ.fn.preventDoubleSubmit=function(){
			var alreadySubmitted = false;
			return $jQ(this).submit(function(){
				if (alreadySubmitted)
				{
					return false;
				}
				else
				{
					alreadySubmitted = true;
				}
			});
		};
		//http://stackoverflow.com/questions/931207/is-there-a-jquery-autogrow-plugin-for-text-fields
		//allows the input area to "grow"
		$jQ.fn.autoGrowInput=function(o){
				var width = $jQ('.mChatPanel').width();
				o = $jQ.extend({
					maxWidth: width - 20,
					minWidth: 0,
					comfortZone: 20
				}, o);

				this.filter('input:text').each(function(){
					var minWidth = o.minWidth || $jQ(this).width(),
					val = '',
					input = $jQ(this),					
					testSubject = $jQ('<div/>').css({
						position: 'absolute',
						top: -9999,
						left: -9999,
						width: 'auto',
						fontSize: input.css('fontSize'),
						fontFamily: input.css('fontFamily'),
						fontWeight: input.css('fontWeight'),
						letterSpacing: input.css('letterSpacing'),
						whiteSpace: 'nowrap'
					}),
					check = function()
					{
						if (val === (val = input.val())) 
						{
							return;
						}

						// Enter new content into testSubject
						var escaped = val.replace(/&/g, '&amp;').replace(/\s/g,' ').replace(/</g, '&lt;').replace(/>/g, '&gt;');
						testSubject.html(escaped);

						// Calculate new width + whether to change
						var testerWidth = testSubject.width(),
							newWidth = (testerWidth + o.comfortZone) >= minWidth ? testerWidth + o.comfortZone : minWidth,
							currentWidth = input.width(),
							isValidWidthChange = (newWidth < currentWidth && newWidth >= minWidth)
												 || (newWidth > minWidth && newWidth < o.maxWidth);

						// Animate width
						if (isValidWidthChange) 
						{
							input.width(newWidth);
						}
					};

				testSubject.insertAfter(input);
				
				$jQ(this).bind('keypress blur change submit focus', check);

			});

			return this;
		};
		$jQ('input.mChatText').autoGrowInput();
		$jQ('#postform').preventDoubleSubmit();
		// Sound cookie check (user prefs)
		if (mChatSound && $jQ.cookie('mChatNoSound')!='yes')
		{
			$jQ.cookie('mChatNoSound',null);
			$jQ('#mChatUseSound').attr('checked','checked');
		}
		else
		{
			$jQ.cookie('mChatNoSound','yes');
			$jQ('#mChatUseSound').removeAttr('checked');
		}	
		if($jQ('#mChatUserList').length && ($jQ.cookie('mChatShowUserList')=='yes' || mChatCustomPage))
		{
			$jQ('#mChatUserList').show();
		}
	
	}
});

// mChat AJAX function
var mChat =	{
	//session countdown
	countDown:function()
	{
		if($jQ('#mChatSessMess').hasClass('mchat-alert'))
		{
			$jQ('#mChatSessMess').removeClass('mchat-alert');
		}	
		session_time = session_time-1;

		var sec = Math.floor(session_time);
		var min = Math.floor(sec/60);
		var hrs = Math.floor(min/60);
		sec = (sec % 60);
		if (sec <= 9)
		{
			sec = "0" + sec;
		}
		min = (min % 60);
		if (min <= 9)
		{
			min = "0" + min;
		}
		hrs = (hrs % 60);
		if (hrs <= 9)
		{
			hrs = "0" + hrs;
		}
		var time_left = hrs + ":" + min + ":" + sec;				
		$jQ('#mChatSessMess').html(mChatSessEnds + ' ' + time_left);

		if (session_time <= 0)
		{
			clearInterval(counter);
			$jQ('#mChatSessMess').html(mChatSessOut).addClass('mchat-alert');
		}
	},			
	// clear input box
	clear:function()
	{
		if($jQ('#mChatMessage').val()=='')
		{
			return false;
		}
	
		var answer = confirm(mChatReset);
		if (answer)
		{
			if($jQ('#mChatRefreshText').hasClass('mchat-alert'))
			{
				$jQ('#mChatRefreshText').removeClass('mchat-alert');
			}
			if(mChatPause)
			{
				interval=setInterval(function(){mChat.refresh()},mChatRefresh);
			}
			$jQ('#mChatOkIMG').show();
			$jQ('#mChatLoadIMG, #mChatErrorIMG, #mChatPauseIMG').hide();
			$jQ('#mChatRefreshText').html(mChatRefreshYes);		
			$jQ('#mChatMessage').val('').focus();
		}
		else
		{
			$jQ('#mChatMessage').focus();
		}		
	},
	// Sound function
	sound:function(file)
	{
		if($jQ.cookie('mChatNoSound')=='yes')
		{
			// Stop
			return;
		}
		if($jQ.browser.msie)
		{
			// For IE ;)
			$jQ('#mChatSound').html('<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" height="0" width="0" type="application/x-shockwave-flash"><param name="movie" value="'+file+'"></object>');
		}
		else
		{
			// For FireFox, Opera, Safari...
			$jQ('#mChatSound').html('<embed src="'+file+'" width="0" height="0" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>');
		}
	},

	// Toggle function
	toggle:function(id)
	{
		// Toggle :)
		$jQ('#mChat'+id).slideToggle('normal',function(){
			// Cookie set
			if($jQ('#mChat'+id).is(':visible'))
			{
				$jQ.cookie('mChatShow'+id,'yes');
			}
			// Cookie delete
			if($jQ('#mChat'+id).is(':hidden'))
			{
				$jQ.cookie('mChatShow'+id,null);
			}
		});
	},

	// Send function
	add:function()
	{
		// If message input empty stop Send function
		if($jQ('#mChatMessage').val()=='')
		{
			return false;
		}
		var mChatMessChars = $jQ('#mChatMessage').val().replace(/ /g,'');

		if(mChatMessChars.length > mChatMssgLngth && mChatMssgLngth)
		{
			alert(mChatMssgLngthLong);
			return;
		}

		$jQ.ajax({
			url:mChatFile,
			timeout:10000,
			type:'POST',
			data:$jQ('#postform').serialize(),
			dataType:'text',
			beforeSend:function()
			{		
				$jQ('#submit_button').attr('disabled','disabled');

				if (mChatUserTimeout)
				{
					clearInterval(activeinterval);
					clearInterval(counter);
				}
				clearInterval(interval);
			},			
			success:function()
			{
          		// Run refresh function
				mChat.refresh();
				
			},
			error:function(XMLHttpRequest)
			{
				if(XMLHttpRequest.status==400)
				{
            		// Flood alert
					alert(mChatFlood);
				}
				else if(XMLHttpRequest.status==403)
				{
            		// No access alert
					alert(mChatNoAccess);
				}
				else if(XMLHttpRequest.status==501)
				{
            		// No message alert
					alert(mChatNoMessageInput);
				}
			},
			complete:function()
			{			
				if($jQ('#mChatData').children('#mChatNoMessage :last'))
				{
					$jQ('#mChatNoMessage').remove();
				}			
				$jQ('#submit_button').attr('disabled','');
				interval=setInterval(function(){mChat.refresh()},mChatRefresh);
				if (mChatUserTimeout)
				{
					session_time = mChatUserTimeout ? mChatUserTimeout / 1000 : false;
					counter = setInterval(function(){mChat.countDown()},1000);
					activeinterval = setInterval(function(){mChat.active()}, mChatUserTimeout);
				}
				// Empty message input
				$jQ('#mChatMessage').val('').focus();
			}
		});
	},

  	// Edit function
	edit:function(id)
	{
		var message=$jQ('#edit'+id).val();
		var data=prompt(mChatEditInfo,message);
		if(data)
		{
        	// AJAX request
			$jQ.ajax({
				url:mChatFile,
				timeout:10000,
				type:'POST',
				data:{mode:'edit',message_id:id,message:data},
				dataType:'text',
				beforeSend:function()
				{
					clearInterval(interval);
            		// Refresh stop
					if (mChatUserTimeout)
					{
						clearInterval(activeinterval);
						clearInterval(counter);
						$jQ('#mChatSessTimer').html(mChatRefreshing);
					}
				},				
				success:function(html)
				{
            		// Replace old edited message to new with animation
					$jQ('#mess'+id).fadeOut('slow',function(){
						$jQ(this).replaceWith(html);
              			// Animation
						$jQ('#mess'+id).css('display','none').fadeIn('slow');
					});
				},
				error:function(XMLHttpRequest)
				{
					if(XMLHttpRequest.status==403)
					{
              			// No access alert
						alert(mChatNoAccess);
					}
					else if(XMLHttpRequest.status==501)
					{
              			// No message alert
						alert(mChatNoMessageInput);
					}
				},
				complete:function()
				{
					interval=setInterval(function(){mChat.refresh()},mChatRefresh);
					if (mChatUserTimeout)
					{
						session_time = mChatUserTimeout ? mChatUserTimeout / 1000 : false;
						counter = setInterval(function(){mChat.countDown()},1000);
						activeinterval=setInterval(function() {mChat.active()}, mChatUserTimeout);
					}
					if(!mChatArchiveMode)
					{
						scrH=$jQ('#mChatmain')[0].scrollHeight;
						window.setTimeout(function(){$jQ('#mChatmain').animate({scrollTop:scrH},1000,'swing')},1500);
					}
				}
			});
		}
	},

	// Delete function
	del:function(id)
	{
		// Confirm to delete
		if(confirm(mChatDelConfirm))
		{
			// AJAX request
			$jQ.ajax({
				url:mChatFile,
				timeout:10000,
				type:'POST',
				data:{mode:'delete',message_id:id},
				beforeSend:function()
				{
					clearInterval(interval);
					// Refresh stop
					if (mChatUserTimeout)
					{
						clearInterval(activeinterval);
						clearInterval(counter);
						$jQ('#mChatSessTimer').html(mChatRefreshing);
					}					
				},				
				success:function()
				{
					// Animation ;)
					$jQ('#mess'+id).fadeOut('slow',function(){
						// Remove message
						$jQ(this).remove();
					});
					// Sound
					mChat.sound(mChatForumRoot+'mchat/del.swf');
				},
				error:function()
				{
					// Not Extended alert
					alert(mChatNoAccess);
				},
				complete:function()
				{
					interval=setInterval(function(){mChat.refresh()},mChatRefresh);
					// Start refresh
					if (mChatUserTimeout)
					{
						session_time = mChatUserTimeout ? mChatUserTimeout / 1000 : false;
						counter = setInterval(function(){mChat.countDown()},1000);
						activeinterval=setInterval(function() {mChat.active()}, mChatUserTimeout);
					}					
				}
			});
		}
	},

	// Refresh function
	refresh:function()
	{
		// If archive page
		if(mChatArchiveMode)
		{
			// Stop
			return;
		}
		
		var mess_id=0;

		if($jQ('#mChatData').children().not('#mChatNoMessage').length)
		{
			if($jQ('#mChatNoMessage'))
			{
				$jQ('#mChatNoMessage').remove();
			}
			//var mess_cnt=$jQ('#mChatData').children().length;
			mess_id=$jQ('#mChatData').children(':last-child').attr('id').replace('mess','');
		}

		var oldScrH=$jQ('#mChatmain')[0].scrollHeight;

		$jQ.ajax({
			url:mChatFile,
			timeout:10000,
			type:'POST',
			data:{mode:'read',message_last_id:mess_id},
			dataType:'html',
			beforeSend:function()
			{		
				$jQ('#mChatOkIMG,#mChatErrorIMG,#mChatPauseIMG').hide();
				$jQ('#mChatLoadIMG').show();				
			},
			success:function(html)
			{
				if(html!='' && html!=0)
				{
					if($jQ('#mChatRefreshText').hasClass('mchat-alert'))
					{
						$jQ('#mChatRefreshText').removeClass('mchat-alert');
					}					
					$jQ('#mChatData').append(html).children(':last').not('#mChatNoMessage');
					var newInner=$jQ('#mChatData').children().not('#mChatNoMessage').innerHeight();
					var newH=oldScrH+newInner;
					$jQ('#mChatmain').animate({scrollTop:newH},'slow');
					mChat.sound(mChatForumRoot+'mchat/add.swf');			
				}
				setTimeout(function(){
					$jQ('#mChatLoadIMG,#mChatErrorIMG,#mChatPauseIMG').hide();
					$jQ('#mChatOkIMG').show();
					$jQ('#mChatRefreshText').html(mChatRefreshYes);
				},500);					
			},
			error:function()
			{
				$jQ('#mChatLoadIMG,#mChatOkIMG,#mChatPauseIMG,#mChatRefreshTextNo,#mChatPauseIMG,').hide();
				$jQ('#mChatErrorIMG').show();
				mChat.sound(mChatForumRoot+'mchat/error.swf');
			},
			complete:function()
			{
				if(!$jQ('#mChatData').children(':last').length)
				{
					$jQ('#mChatData').append('<div id="mChatNoMessage">'+mChatNoMessage+'</div>').show('slow');
				}
			}
		});
	},
	//whois chatting function
	stats:function()
	{
		if(!mChatWhois)
		{
			return;
		}
				
		$jQ.ajax({
			url:mChatFile,
			timeout:10000,
			type:'POST',
			data:{mode:'stats'},
			dataType:'html',
			beforeSend:function()
			{
				if(mChatCustomPage)
				{
					$jQ('#mChatRefreshN').show();
					$jQ('#mChatRefresh').hide();
				}
			},
			success:function(stats)
			{
				$jQ('#mChatStats').replaceWith(stats);
						
				if(mChatCustomPage)
				{				
					setTimeout(function(){
						$jQ('#mChatRefreshN').hide();
						$jQ('#mChatRefresh').show();
					},500);
				}				
			},
			error:function()
			{
				mChat.sound(mChatForumRoot+'mchat/error.swf');
			},
			complete:function()
			{
				if($jQ('#mChatUserList').length && ($jQ.cookie('mChatShowUserList')=='yes' || mChatCustomPage))
				{
					$jQ('#mChatUserList').css('display', 'block');
				}				
			}
		});
	},
	// test for user being active
	active:function()
	{
		if(mChatArchiveMode || !mChatUserTimeout)
		{
			// Stop
			return;
		}
		// clear refresh interval
		clearInterval(interval);
		$jQ('#mChatLoadIMG,#mChatOkIMG,#mChatErrorIMG').hide();
		$jQ('#mChatPauseIMG').show();
		$jQ('#mChatRefreshText').html(mChatRefreshNo).addClass('mchat-alert');
		$jQ('#mChatSessMess').html(mChatSessOut).addClass('mchat-alert');
	}
};

// timeouts
var interval=setInterval(function(){mChat.refresh()},mChatRefresh);
var statsinterval=setInterval(function(){mChat.stats()},mChatWhoisRefresh);
var activeinterval=setInterval(function(){mChat.active()},mChatUserTimeout);
//session countdown
var session_time = mChatUserTimeout ? mChatUserTimeout / 1000 : false;
if(mChatUserTimeout)
{
	var counter = setInterval(function(){mChat.countDown()},1000); //1000 will  run it every 1 second	
}

// yeah for cookies
if($jQ.cookie('mChatShowSmiles')=='yes' &&  $jQ('#mChatSmiles').css('display', 'none'))
{
	$jQ('#mChatSmiles').slideToggle('slow');
}
if($jQ.cookie('mChatShowBBCodes')=='yes' && $jQ('#mChatBBCodes').css('display', 'none'))
{
	$jQ('#mChatBBCodes').slideToggle('slow');
}
if($jQ.cookie('mChatShowUserList')=='yes' && $jQ('#mChatUserList').length)
{
	$jQ('#mChatUserList').slideToggle('slow');
}
if($jQ.cookie('mChatShowColour')=='yes' && $jQ('#mChatColour').css('display', 'none'))
{
	$jQ('#mChatColour').slideToggle('slow');
}

//sound change
$jQ('#mChatUseSound').change(function(){
	if($jQ(this).is(':checked'))
	{
		$jQ.cookie('mChatNoSound',null);
	}
	else
	{
		$jQ.cookie('mChatNoSound','yes');
	}
});