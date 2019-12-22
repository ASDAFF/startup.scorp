var getRandomInt = function(min, max){
	return Math.floor(Math.random() * (max - min)) + min;
}

var number_format = function(number, decimals, dec_point, thousands_sep) {
	number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
	var n = !isFinite(+number) ? 0 : +number,
	prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
	sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
	dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
	s = '',
	toFixedFix = function(n, prec){
		var k = Math.pow(10, prec);
		return '' + (Math.round(n*k)/k).toFixed(prec);
	};

	// Fix for IE parseFloat(0.55).toFixed(0) = 0;
	s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

	if (s[0].length > 3) {
		s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
	}

	if ((s[1] || '')
		.length < prec) {
		s[1] = s[1] || '';
		s[1] += new Array(prec - s[1].length + 1).join('0');
	}

	return s.join(dec);
}

var CheckTopMenuDotted = function(){
	var menu = $('nav.mega-menu');
	var menuMoreItem = menu.find('td.js-dropdown');
	if(menu.parents('.collapse').css('display') == 'none'){
		return false;
	}

	var block_w = menu.closest('div').actual('width');
	var	menu_w = menu.find('table').actual('outerWidth');
	var afterHide = false;

	while(menu_w > block_w) {
		menuItemOldSave = menu.find('td').not('.nosave').last();
		if(menuItemOldSave.length){
			menuMoreItem.show();
			menuItemNewSave = '<li class="' + (menuItemOldSave.hasClass('dropdown') ? 'dropdown-submenu ' : '') + (menuItemOldSave.hasClass('active') ? 'active ' : '') + '" data-hidewidth="' + menu_w + '">' + menuItemOldSave.find('.wrap').html() + '</li>';
			menuItemOldSave.remove();
			menuMoreItem.find('> .wrap > .dropdown-menu').prepend(menuItemNewSave);
			menu_w = menu.find('table').actual('outerWidth');
			afterHide = true;
		}
		else{
			break;
		}
	}

	if(!afterHide) {
		do {
			var menuItemOldSaveCnt = menuMoreItem.find('>.wrap>.dropdown-menu>li').length;
			if(!menuItemOldSaveCnt) {
				menuMoreItem.hide();
				break;
			}
			else {
				menuItemOldSave = menuMoreItem.find('>.wrap>.dropdown-menu>li').first();
				var hideWidth = menuItemOldSave.attr('data-hidewidth');
				if(hideWidth > block_w) {
					break
				}
				else {
					menuItemNewSave = '<td class="' + (menuItemOldSave.hasClass('dropdown-submenu') ? 'dropdown ' : '') + (menuItemOldSave.hasClass('active') ? 'active ' : '') + '" data-hidewidth="' + block_w + '"><div class="wrap">' + menuItemOldSave.html() + '</div></td>';
					menuItemOldSave.remove();
					$(menuItemNewSave).insertBefore(menu.find('td.js-dropdown'));
					if(!menuItemOldSaveCnt) {
						menuMoreItem.hide();
						break;
					}
				}
			}
			menu_w = menu.find('table').actual('outerWidth');
		}
		while(menu_w <= block_w);
	}

	menu.find('td').css('visibility', 'visible');

	return false;
}

var CheckTopVisibleMenu = function(that) {
	var dropdownMenu = $('.dropdown-menu:visible').last();
	if(dropdownMenu.length){
		dropdownMenu.find('a').css('white-space', '');
		dropdownMenu.css('left', '');
		dropdownMenu.css('right', '');
		dropdownMenu.removeClass('toright');

		var dropdownMenu_left = dropdownMenu.offset().left;
		if(typeof(dropdownMenu_left) != 'undefined'){
			var menu = dropdownMenu.parents('.mega-menu');
			var menu_width = menu.outerWidth();
			var menu_left = menu.offset().left;
			var menu_right = menu_left + menu_width;
			var isToRight = dropdownMenu.parents('.toright').length > 0;
			var parentsDropdownMenus = dropdownMenu.parents('.dropdown-menu');
			var isHasParentDropdownMenu = parentsDropdownMenus.length > 0;
			if(isHasParentDropdownMenu){
				var parentDropdownMenu_width = parentsDropdownMenus.first().outerWidth();
				var parentDropdownMenu_left = parentsDropdownMenus.first().offset().left;
				var parentDropdownMenu_right = parentDropdownMenu_width + parentDropdownMenu_left;
			}

			if(parentDropdownMenu_right + dropdownMenu.outerWidth() > menu_right){
				dropdownMenu.find('a').css('white-space', 'normal');
			}

			var dropdownMenu_width = dropdownMenu.outerWidth();
			var dropdownMenu_right = dropdownMenu_left + dropdownMenu_width;

			if(dropdownMenu_right > menu_right || isToRight){
				var addleft = 0;
				addleft = menu_right - dropdownMenu_right;
				if(isHasParentDropdownMenu || isToRight){
					dropdownMenu.css('left', 'auto');
					dropdownMenu.css('right', '100%');
					dropdownMenu.addClass('toright');
				}
				else{
					var dropdownMenu_curLeft = parseInt(dropdownMenu.css('left'));
					dropdownMenu.css('left', (dropdownMenu_curLeft + addleft) + 'px');
				}
			}
		}
	}
}

var CheckPopupTop = function(){
	var popup = $('.jqmWindow.show');
	if(popup.length){
		var windowHeight = $(window).height();
		var popupTop = parseInt(popup.css('top'));
		var popupHeight = popup.height();

		if(windowHeight >= popupHeight){
			// center
			popupTop = (windowHeight - popupHeight) / 2;
		}
		else{
			if(documentScrollTop > documentScrollTopLast){
				// up
				popupTop -= documentScrollTop - documentScrollTopLast;
			}
			else if(documentScrollTop < documentScrollTopLast){
				// down
				popupTop += documentScrollTopLast - documentScrollTop;
			}

			if(popupTop + popupHeight < windowHeight){
				// bottom
				popupTop = windowHeight - popupHeight;
			}
			else if(popupTop > 0){
				// top
				popupTop = 0;
			}
		}
		popup.css('top', popupTop + 'px');
	}
}

var CheckMainBannerSliderVText = function(slider){
	if(slider.parents('.banners-big').length){
		var sh = slider.height();
		slider.find('.item').each(function() {
			var curSlideTextInner = $(this).find('.text .inner');
			if(curSlideTextInner.length){
				var ith = curSlideTextInner.actual('height');
				var p = (ith >= sh ? 0 : Math.floor((sh - ith) / 2));
				curSlideTextInner.css('padding-top', p + 'px');
			}
		});
	}
}

var CheckStickyFooter = function() {
	$(window).resize(function() { //  BX.addCustomEvent('onWindowResize', function(eventdata) {
		try{
			var footerHeight = $('footer').outerHeight();
			ignoreResize.push(true);
			$('footer').css('margin-top', '-' + footerHeight + 'px');
			$('.body').css('margin-bottom', '-' + footerHeight + 'px');
			$('.main').css('padding-bottom', footerHeight + 25 + 'px');
			ignoreResize.pop();
		}
		catch(e){}
	});
}

var getGridSize = function(counts) {
	var z = parseInt($('.body_media').css('top'));
	return (z == 2 ? counts[0] : z == 1 ? counts[1] : counts[2]);
}

var CheckFlexSlider = function(){
	$('.flexslider:not(.thmb)').each(function(){
		var slider = $(this);
		slider.resize();
		var counts = slider.data('flexslider').vars.counts;
		if(typeof(counts) != 'undefined'){
			var cnt = getGridSize(counts);
			var to0 = (cnt != slider.data('flexslider').vars.minItems || cnt != slider.data('flexslider').vars.maxItems || cnt != slider.data('flexslider').vars.move);
			if(to0){
				slider.data('flexslider').vars.minItems = cnt;
				slider.data('flexslider').vars.maxItems = cnt;
				slider.data('flexslider').vars.move = cnt;
				slider.flexslider(0);
				slider.resize();
				slider.resize(); // twise!
			}
		}
	});
}

var CheckHeaderFixed = function(){
	var header = $('header.canfixed');
	if(header.length){
		var headerLogoAndMenuRow = header.find('.logo_and_menu-row');
		if(headerLogoAndMenuRow.length){
			var isHeaderFixed = false;
			var headerCanFix = true;
			var headerFixedHeight = 53;
			var headerNormalHeight = headerLogoAndMenuRow.actual('outerHeight');
			var headerDiffHeight = headerNormalHeight - headerFixedHeight;
			var mobileBtnMenu = $('.btn.btn-responsive-nav');
			$(window).scroll(function(){
				var headerTop = $('#panel:visible').outerHeight();
				var scrollTop = $(window).scrollTop();
				if(!isHeaderFixed){
					headerNormalHeight = headerLogoAndMenuRow.actual('outerHeight');
					headerDiffHeight = headerNormalHeight - headerFixedHeight;
				}

				headerCanFix = !mobileBtnMenu.is(':visible') && !$('.dropdown-menu:visible').length;

				if(!isHeaderFixed){
					if((scrollTop > headerNormalHeight + headerTop) && headerCanFix){
						isHeaderFixed = true;
						var headerNext = header.next();
						if(headerNext.length){
							var mt = parseInt(headerNext.css('margin-top'));
						}
						header.css('top', '-' + headerNormalHeight + 'px');
						header.addClass('fixed');
						if(headerNext.length){
							headerNext.css('margin-top', headerNormalHeight + mt + 'px');
						}
						header.delay(0).animate({top: '0'}, 300);
					}
				}
				else if(isHeaderFixed || !headerCanFix){
					if((scrollTop <= headerDiffHeight + headerTop) || !headerCanFix){
						isHeaderFixed = false;
						header.stop(true, true).css('top', '');
						header.removeClass('fixed');
						var headerNext = header.next();
						if(headerNext.length){
							headerNext.css('margin-top', '');
						}
						CheckTopMenuDotted();
					}
				}
			});
		}
	}
}

var CheckObjectsSizes = function() {
	$('.container iframe,.container object,.container video').each(function() {
		var height_attr = $(this).attr('height');
		var width_attr = $(this).attr('width');
		if (height_attr && width_attr) {
			$(this).css('height', $(this).outerWidth() * height_attr / width_attr);
		}
	});
}

var InitScrollToTop = function(){
	if(arScorpOptions.THEME.SCROLLTOTOP_TYPE !== 'NONE'){
		scrollToTopAnimateClassIn = arScorpOptions.THEME.SCROLLTOTOP_TYPE.indexOf('ROUND') !== -1 ? 'rotateIn' : 'rubberBand';
		scrollToTopAnimateClassOut = arScorpOptions.THEME.SCROLLTOTOP_TYPE.indexOf('ROUND') !== -1 ? 'rotateOut' : 'flipOutX';

		$('body').append($('<a />').addClass('scroll-to-top ' + arScorpOptions.THEME.SCROLLTOTOP_TYPE + ' ' + arScorpOptions.THEME.SCROLLTOTOP_POSITION).attr({'href': '#', 'id': 'scrollToTop'}));

		$scrolltotop = $('#scrollToTop');

		$scrolltotop.click(function(e){
			e.preventDefault();
			$('body, html').animate({scrollTop : 0}, 500);
			return false;
		});
	}
}

var CheckScrollToTop = function(){
	if($scrolltotop.length){
		if(documentScrollTop > 150){
			$scrolltotop.stop(true, true).addClass('visible').addClass('animated');
			if(scrollToTopAnimateClassOut){
				$scrolltotop.removeClass(scrollToTopAnimateClassOut);
			}
			if(scrollToTopAnimateClassIn){
				$scrolltotop.addClass(scrollToTopAnimateClassIn);
			}
		}
		else{
			$scrolltotop.stop(true, true).removeClass('visible');
			if(scrollToTopAnimateClassIn){
				$scrolltotop.removeClass(scrollToTopAnimateClassIn);
			}
			if(scrollToTopAnimateClassOut){
				$scrolltotop.addClass(scrollToTopAnimateClassOut);
			}
		}

		var bottom = 23;
		var scrollVal = $(window).scrollTop();
		var windowHeight = $(window).height();
		var footerOffset = $('footer').offset().top;

		if(scrollVal + windowHeight > footerOffset){
			$scrolltotop.css('bottom', bottom + scrollVal + windowHeight - footerOffset);
		}
		else if(parseInt($scrolltotop.css('bottom')) > bottom){
			$scrolltotop.css('bottom', bottom);
		}
	}
}

function pauseMainBanner(){
	$('.banners-big .flexslider').flexslider('pause');
}

function playMainBanner(){
	$('.banners-big .flexslider').flexslider('play');
}

function startMainBannerSlideVideo($slide){
	var slideActiveIndex = $slide.attr('data-slide_index')
	var $slides = $slide.closest('.items').find('.item[data-slide_index="'+ slideActiveIndex +'"]') // current slide & cloned
	var videoSource = $slide.attr('data-video_source')

	if(videoSource){
		$slides.addClass('loading')
		pauseMainBanner()

		var videoPlayerSrc = $slide.attr('data-video_src')
		var videoSoundDisabled = $slide.attr('data-video_disable_sound')
		var bVideoSoundDisabled = videoSoundDisabled == 1
		var videoLoop = $slide.attr('data-video_loop')
		var bVideoLoop = videoLoop == 1
		var videoCover = $slide.attr('data-video_cover')
		var bVideoCover = videoCover == 1 && !isMobile
		var videoUnderText = $slide.attr('data-video_under_text')
		var bVideoUnderText = videoUnderText == 1
		var videoPlayer = $slide.attr('data-video_player')
		var bVideoPlayerYoutube = videoPlayer === 'YOUTUBE'
		var bVideoPlayerVimeo = videoPlayer === 'VIMEO'
		var bVideoPlayerRutube = videoPlayer === 'RUTUBE'
		var bVideoPlayerHtml5 = videoPlayer === 'HTML5'

		if(videoPlayerSrc && !$slide.find('.video').length){

			var InitPlayer = function(){
				$slides.each(function(i, node){
					var $_slide = $(node);
					var videoID = getRandomInt(100, 1000);
					var bClone = $_slide.hasClass('clone')

					if(bVideoPlayerYoutube){
						$_slide.prepend('<iframe id="player_' + videoID + '" class="video' + (bVideoCover ? ' cover' : '') + '" src="'+ videoPlayerSrc +'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
					}
					else if(bVideoPlayerVimeo){
						$_slide.prepend('<iframe id="player_' + videoID + '" class="video' + (bVideoCover ? ' cover' : '') + '" src="'+ videoPlayerSrc +'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
					}
					else if(bVideoPlayerRutube){
						videoPlayerSrc = videoPlayerSrc + '&playerid=' + videoID;
						$_slide.prepend('<iframe id="player_' + videoID + '" class="video' + (bVideoCover ? ' cover' : '') + '" src="'+ videoPlayerSrc +'" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>');
					}
					else if(bVideoPlayerHtml5){
						$_slide.prepend('<video id="player_' + videoID + '" class="video' + (bVideoCover ? ' cover' : '') + '"' + (bVideoLoop ? ' loop ' : '') + (bVideoSoundDisabled || bClone ? ' muted ' : '') + '><source src="'+ videoPlayerSrc +'" type=\'video/mp4; codecs="avc1.42E01E, mp4a.40.2"\' /><p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that supports HTML5 video</p></iframe>');
					}

					if(typeof(players) !== 'undefined' && players){
						players[videoID] = {
							id: 'player_' + videoID,
							mute: bVideoSoundDisabled || bClone,
							loop: bVideoLoop,
							cover: bVideoCover,
							videoPlayer: videoPlayer,
							slideIndex: slideActiveIndex,
							clone: bClone,
							playing: false
						};

						if(bVideoPlayerYoutube){
							window[players[videoID].id] = new YT.Player(
								players[videoID].id, {
									events: {
										'onReady': onYoutubePlayerReady,
										'onStateChange': onYoutubePlayerStateChange
									}
								}
							);
						}
						else if(bVideoPlayerVimeo){
						    window[players[videoID].id] = new Vimeo.Player(document.getElementById(players[videoID].id), {autopause: false, byline: false, loop: false, title: false});
						    window[players[videoID].id].on('loaded', onVimeoPlayerReady)
						    window[players[videoID].id].on('play', onVimeoPlayerStateChange)
						    window[players[videoID].id].on('pause', onVimeoPlayerStateChange)
						    window[players[videoID].id].on('ended', onVimeoPlayerStateChange)
						}
						else if(bVideoPlayerRutube){
							document.getElementById(players[videoID].id).onload = function(e){
								var videoID = this.id.replace('player_', '')
								players[videoID].contentWindow = this.contentWindow
								onRutubePlayerReady(videoID)
							}
						}
						else if(bVideoPlayerHtml5){
							document.getElementById(players[videoID].id).addEventListener('loadeddata', onHtml5PlayerReady)
							document.getElementById(players[videoID].id).addEventListener('play', onHtml5PlayerStateChange)
							document.getElementById(players[videoID].id).addEventListener('pause', onHtml5PlayerStateChange)
							document.getElementById(players[videoID].id).addEventListener('ended', onHtml5PlayerStateChange)
						}
					}
				});
			}

			if(!bVideoPlayerHtml5){
				var obPlayerVariable = ''
				var fnPlayerVariable = ''
				if(typeof window['YoutubePlayerScriptLoaded'] === 'undefined'){
					window['YoutubePlayerScriptLoaded'] = false
				}
				if(typeof window['VimeoPlayerScriptLoaded'] === 'undefined'){
					window['VimeoPlayerScriptLoaded'] = false
				}
				if(typeof window['RutubePlayerListnersAdded'] === 'undefined'){
					window['RutubePlayerListnersAdded'] = false
				}

				// load script
				if(bVideoPlayerYoutube){
					obPlayerVariable = 'YT'
					fnPlayerVariable = 'Player'
					if(!window['YoutubePlayerScriptLoaded']){
						var script = document.createElement('script');
						script.src = "https://www.youtube.com/iframe_api";
						var firstScriptTag = document.getElementsByTagName('script')[0];
						firstScriptTag.parentNode.insertBefore(script, firstScriptTag);
						window['YoutubePlayerScriptLoaded'] = true;
					}
				}
				else if(bVideoPlayerVimeo){
					obPlayerVariable = 'Vimeo'
					if(!window['VimeoPlayerScriptLoaded']){
						var script = document.createElement('script');
						script.src = 'https://player.vimeo.com/api/player.js';
						(document.head || document.documentElement).appendChild(script);
						window['VimeoPlayerScriptLoaded'] = true
					}
				}
				else if(bVideoPlayerRutube){
					if(!window['RutubePlayerListnersAdded']){
						window.addEventListener('message', function(e){
							if(e.origin.indexOf('rutube.ru') !== -1){
							    var message = JSON.parse(e.data)
							    if(typeof message === 'object' && message){
							    	if(typeof message.type !== 'undefined' && message.type){
							    		var videoID = false

							    		for(var j in players){
									    	if(typeof players[j].contentWindow !== 'undefined'){
									    		if(players[j].contentWindow == e.source){
									    			videoID = j
									    			break
									    		}
									    	}
									    }

									    if(videoID){
										    switch (message.type) {
										        case 'player:changeState':
										            onRutubePlayerStateChange(videoID, message.data.state)
										            break
										        case 'player:currentTime':
										            onRutubePlayerCurrentTime(videoID, message.data.time)
										            break
										    }
										}
									}
							    }
							}
						});
					}
				}

				if(!obPlayerVariable.length){
					InitPlayer()
				}
				else{
					// wait player class
					if(typeof window[obPlayerVariable] === 'object'){
						if(!fnPlayerVariable.length || (fnPlayerVariable.length && typeof window[obPlayerVariable][fnPlayerVariable] === 'function')){

							InitPlayer()
						}
					}
					else{
						var waitPlayerInterval = setInterval(function(){
							if(typeof window[obPlayerVariable] === 'object'){
								if(!fnPlayerVariable.length || (fnPlayerVariable.length && typeof window[obPlayerVariable][fnPlayerVariable] === 'function')){

									clearInterval(waitPlayerInterval)

									InitPlayer()
								}
							}
						}, 50)
					}
				}

			}
			else{
				InitPlayer()
			}
		}
	}
}

var CoverPlayer = function(){
	var $videoCover = $('.video.cover')
	if($videoCover.length){
		var bannersHeight = $('.banners-big').height()
		var bannersWidth = $('.banners-big').width()
		var windowWidth = $(window).width()
		var height = windowWidth * 9 / 16
		$videoCover.css({'height': height + 'px', 'margin-top': ((bannersHeight - height) / 2) + 'px'})
	}
}

function onYoutubePlayerReady(e) {
	var videoID = e.target.a.id.replace('player_', '')
	if(videoID){
		var mute = players[videoID].mute
		var cover = players[videoID].cover
    	var clone = players[videoID].clone

    	// mute sound
		if(mute || clone){
			window[players[videoID].id].mute()
		}

    	// cover video
		if(cover){
	    	CoverPlayer()
	    }

    	// not start clone video playing
    	if(clone){
    		setTimeout(function(){
				e.target.pauseVideo()
    		}, 100)
    	}
    	else{
		    // stop slider
			pauseMainBanner()

		    e.target.playVideo()
    	}

    	// update slide class
		var $slide = $('#player_' + videoID).closest('.item')
		$slide.addClass('started')
		$slide.removeClass('loading')
    }
}

function onYoutubePlayerStateChange(e){
	var videoID = e.target.a.id.replace('player_', '')
    if(videoID){
    	var clone = players[videoID].clone
		var loop = players[videoID].loop
    	var slideIndex = players[videoID].slideIndex

    	if(!clone){
			if(e.data === YT.PlayerState.PLAYING){
				players[videoID].playing = true

				// stop slider
				pauseMainBanner()
			}
			else if(e.data === YT.PlayerState.PAUSED){
		    	players[videoID].playing = false

		    	// sync time in cloned players & pause
	    		var time = Math.floor(window[players[videoID].id].getCurrentTime() * 10) / 10
				window[players[videoID].id].seekTo(time, true)
				for(var j in players){
					if(players[j].slideIndex == slideIndex && players[j].clone){
						window[players[j].id].pauseVideo()
						window[players[j].id].seekTo(time, true)
					}
				}
			}
			else if(e.data === YT.PlayerState.ENDED){
				players[videoID].playing = false
		    	if(loop){
		    		e.target.playVideo()
		    	}
		    	else{
		    		// play slider
					playMainBanner()
		    	}
			}
		}
	}
}

function onVimeoPlayerReady(e){
	var videoID = this.element.id.replace('player_', '')
	if(videoID){
		var mute = players[videoID].mute
		var cover = players[videoID].cover
    	var clone = players[videoID].clone

    	// mute sound
		if(mute || clone){
			window[players[videoID].id].setVolume(0)
		}

    	// cover video
		if(cover){
	    	CoverPlayer()
	    }

    	// not start clone video playing
    	if(clone){
    		setTimeout(function(){
				window[players[videoID].id].pause()
    		}, 100)
    	}
    	else{
		    // stop slider
			pauseMainBanner()

		    window[players[videoID].id].play()
    	}

    	// update slide class
		var $slide = $('#player_' + videoID).closest('.item')
		$slide.addClass('started')
		$slide.removeClass('loading')
    }
}

function onVimeoPlayerStateChange(e){
	var videoID = this.element.id.replace('player_', '')
	if(videoID){
		var cover = players[videoID].cover
    	var clone = players[videoID].clone
		var loop = players[videoID].loop
    	var slideIndex = players[videoID].slideIndex

    	if(!clone){
    		window[players[videoID].id].getPaused().then(function(paused){
    			if(paused){
			    	players[videoID].playing = false

			    	// sync time in cloned players & pause
			    	window[players[videoID].id].getCurrentTime().then(function(seconds){
			    		var time = Math.floor(seconds * 10) / 10
			    		window[players[videoID].id].setCurrentTime(time).then(function(seconds){
							for(var j in players){
								if(players[j].slideIndex == slideIndex && players[j].clone){
									window[players[j].id].pause()
									window[players[j].id].setCurrentTime(time).then(function(seconds){})
								}
							}
			    		})
			    	})
    			}
    			else{
		    		window[players[videoID].id].getEnded().then(function(ended){
		    			if(ended){
							players[videoID].playing = false
					    	if(loop){
					    		window[players[videoID].id].play()
					    	}
					    	else{
					    		// play slider
								playMainBanner()
					    	}
		    			}
		    			else{
		    				players[videoID].playing = true

		    				// stop slider
							pauseMainBanner()
		    			}
		    		})
    			}
    		})
		}
	}
}

function onRutubePlayerReady(videoID){
	if(videoID){
		var mute = players[videoID].mute
		var cover = players[videoID].cover
    	var clone = players[videoID].clone
    	var player = document.getElementById(players[videoID].id)

    	// mute sound
		if(mute || clone){
			player.contentWindow.postMessage(JSON.stringify({
			    type: 'player:mute',
			    data: {}
			}), '*')
		}

    	// cover video
		if(cover){
	    	CoverPlayer()
	    }

    	// not start clone video playing
    	if(clone){
    		setTimeout(function(){
				player.contentWindow.postMessage(JSON.stringify({
				    type: 'player:pause',
				    data: {}
				}), '*')
    		}, 100)
    	}
    	else{
		    // stop slider
			pauseMainBanner()

		    player.contentWindow.postMessage(JSON.stringify({
			    type: 'player:play',
			    data: {}
			}), '*')
    	}

    	// update slide class
		var $slide = $('#player_' + videoID).closest('.item')
		$slide.addClass('started')
		$slide.removeClass('loading')
    }
}

function onRutubePlayerCurrentTime(videoID, time){
	if(videoID){
		players[videoID].time = time
	}
}

function onRutubePlayerStateChange(videoID, state){
	if(videoID){
    	var clone = players[videoID].clone
		var loop = players[videoID].loop
    	var slideIndex = players[videoID].slideIndex
    	var player = document.getElementById(players[videoID].id)

    	if(!clone){
			if(state === 'playing'){
				players[videoID].playing = true

				// stop slider
				pauseMainBanner()
			}
			else if(state === 'paused'){
		    	players[videoID].playing = false

		    	// sync time in cloned players & pause
	    		var time = Math.floor(players[videoID].time * 10) / 10
				player.contentWindow.postMessage(JSON.stringify({
				    type: 'player:setCurrentTime',
				    data: {time: time}
				}), '*')
				for(var j in players){
					if(players[j].slideIndex == slideIndex && players[j].clone){
						document.getElementById(players[j].id).contentWindow.postMessage(JSON.stringify({
						    type: 'player:pause',
						    data: {}
						}), '*')
						document.getElementById(players[j].id).contentWindow.postMessage(JSON.stringify({
						    type: 'player:setCurrentTime',
						    data: {time: time}
						}), '*')
					}
				}
			}
			else if(state === 'stopped'){
				players[videoID].playing = false
		    	if(loop){
		    		player.contentWindow.postMessage(JSON.stringify({
					    type: 'player:play',
					    data: {}
					}), '*')
		    	}
		    	else{
		    		// play slider
					playMainBanner()
		    	}
			}
		}
	}
}

function onHtml5PlayerReady(e){
	var videoID = e.target.id.replace('player_', '')
	if(videoID){
		var mute = players[videoID].mute
		var cover = players[videoID].cover
    	var clone = players[videoID].clone

    	// mute sound
		if(mute || clone){
			$('#' + players[videoID].id).prop('muted', true);
		}

    	// cover video
		if(cover){
	    	CoverPlayer()
	    }

    	// not start clone video playing
    	if(clone){
    		e.target.pause()
    	}
    	else{
		    // stop slider
			pauseMainBanner()

		    e.target.play()
    	}

    	// update slide class
		var $slide = $('#player_' + videoID).closest('.item')
		$slide.addClass('started')
		$slide.removeClass('loading')
    }
}

function onHtml5PlayerStateChange(e){
	var videoID = e.target.id.replace('player_', '')
	if(videoID){
    	var cover = players[videoID].cover
    	var clone = players[videoID].clone
		var loop = players[videoID].loop
    	var slideIndex = players[videoID].slideIndex

    	if(!clone){
			if(e.target.paused){
		    	players[videoID].playing = false

		    	// sync time in cloned players & pause
	    		var time = Math.floor(e.target.currentTime * 10) / 10
				e.target.currentTime = time
				for(var j in players){
					if(players[j].slideIndex == slideIndex && players[j].clone){
						document.getElementById(players[j].id).pause()
						document.getElementById(players[j].id).currentTime = time
					}
				}
			}
			else if(e.target.ended){
				players[videoID].playing = false
		    	if(loop){
		    		e.target.play()
		    	}
		    	else{
		    		// play slider
					playMainBanner()
		    	}
			}
			else{
				players[videoID].playing = true

				// stop slider
				pauseMainBanner()
			}
		}
	}
}

var waitingExists = function(selector, delay, callback){
	if(typeof(callback) !== 'undefined' && selector.length && delay > 0){
		delay = parseInt(delay);
		delay = (delay < 0 ? 0 : delay);

		if(!$(selector).length){
			setTimeout(function(){
				waitingExists(selector, delay, callback);
			}, delay);
		}
		else{
			callback();
		}
	}
}

var waitingNotExists = function(selectorExists, selectorNotExists, delay, callback){
	if(typeof(callback) !== 'undefined' && selectorExists.length && selectorNotExists.length && delay > 0){
		delay = parseInt(delay);
		delay = (delay < 0 ? 0 : delay);

		setTimeout(function(){
			if(selectorExists.length && !$(selectorNotExists).length){
				callback();
			}
		}, delay);
	}
}

function onLoadjqm(hash){
	var name = $(hash.t).data('name'),
		top = (($(window).height() > hash.w.height()) ? Math.floor(($(window).height() - hash.w.height()) / 2) : 0) + 'px';
	$.each($(hash.t).get(0).attributes, function(index, attr){
		if(/^data\-autoload\-(.+)$/.test(attr.nodeName)){
			var key = attr.nodeName.match(/^data\-autoload\-(.+)$/)[1];
			var el = $('input[name="'+key.toUpperCase()+'"]');
			el.val( $(hash.t).data('autoload-'+key) ).attr('readonly', 'readonly');
			el.attr('title', el.val());
		}
	});
	if($(hash.t).data('autohide')){
		$(hash.w).data('autohide', $(hash.t).data('autohide'));
	}
	if(name == 'order_product'){
		if($(hash.t).data('product')) {
			$('input[name="PRODUCT"]').val($(hash.t).data('product')).attr('readonly', 'readonly').attr('title', $('input[name="PRODUCT"]').val());
		}
	}
	if(name == 'question'){
		if($(hash.t).data('product')) {
			$('input[name="NEED_PRODUCT"]').val($(hash.t).data('product')).attr('readonly', 'readonly').attr('title', $('input[name="NEED_PRODUCT"]').val());
		}
	}
	hash.w.addClass('show').css({'margin-left': '-' + Math.floor(hash.w.width() / 2) + 'px', 'top': top, 'opacity': 1});
}

function onHide(hash){
	if($(hash.w).data('autohide')){
		eval($(hash.w).data('autohide'));
	}
	hash.w.css('opacity', 0).removeClass('show');
	hash.o.remove();
	setTimeout(function(){
		hash.w.empty();
	}, 200);
}

$.fn.equalizeHeights = function( outer ){
	var maxHeight = this.map( function( i, e ){
		$(e).css('height', '');
		if( outer == true ){
			return $(e).actual('outerHeight');
		}else{
			return $(e).actual('height');
		}
	}).get();

	for(var i = 0, c = maxHeight.length; i < c; ++i){
		if(maxHeight[i] % 2){
			--maxHeight[i];
		}
	}

	return this.height( Math.max.apply( this, maxHeight ) );
}

$.fn.sliceHeight = function( options ){
	function _slice(el){
		el.each(function() {
			$(this).css('line-height', '');
			$(this).css('height', '');
		});
		if(typeof(options.autoslicecount) == 'undefined' || options.autoslicecount !== false){
			var elw = (el.first().hasClass('item') ? el.first().outerWidth() : el.first().parents('.item').outerWidth());
			var elsw = el.first().parents('.items').outerWidth();
			if(!elsw){
				elsw = el.first().parents('.row').outerWidth();
			}
			if(elsw && elw){
				options.slice = Math.floor(elsw / elw);
			}
		}
		if(options.slice){
			for(var i = 0; i < el.length; i += options.slice){
				$(el.slice(i, i + options.slice)).equalizeHeights(options.outer);
			}
		}
		if(options.lineheight){
			var lineheightAdd = parseInt(options.lineheight);
			if(isNaN(lineheightAdd)){
				lineheightAdd = 0;
			}
			el.each(function() {
				$(this).css('line-height', ($(this).actual('height') + lineheightAdd) + 'px');
			});
		}
	}

	var options = $.extend({
		slice: null,
		outer: false,
		lineheight: false,
		autoslicecount: true
	}, options);

	var el = $(this);
	_slice(el);

	BX.addCustomEvent('onWindowResize', function(eventdata) {
		ignoreResize.push(true);
		_slice(el);
		ignoreResize.pop();
	});
}

$.fn.jqmEx = function(){
	$(this).each(function(){
		var _this = $(this);
		var name = _this.data('name');

		if(name.length){
			var script = arScorpOptions['SITE_DIR'] + 'ajax/form.php';
			var paramsStr = ''; var trigger = ''; var arTriggerAttrs = {};
			$.each(_this.get(0).attributes, function(index, attr){
				var attrName = attr.nodeName;
				var attrValue = _this.attr(attrName);
				trigger += '[' + attrName + '=\"' + attrValue + '\"]';
				arTriggerAttrs[attrName] = attrValue;
				if(/^data\-param\-(.+)$/.test(attrName)){
					var key = attrName.match(/^data\-param\-(.+)$/)[1];
					paramsStr += key + '=' + attrValue + '&';
				}
			});

			var triggerAttrs = JSON.stringify(arTriggerAttrs);
			var encTriggerAttrs = encodeURIComponent(triggerAttrs);
			script += '?' + paramsStr + 'data-trigger=' + encTriggerAttrs;

			if(!$('.' + name + '_frame[data-trigger="' + encTriggerAttrs + '"]').length){
				if(_this.attr('disabled') != 'disabled'){
					$('body').find('.' + name + '_frame[data-trigger="' + encTriggerAttrs + '"]').remove();
					$('body').append('<div class="' + name + '_frame jqmWindow" style="width:500px" data-trigger="' + encTriggerAttrs + '"></div>');
					$('.' + name + '_frame[data-trigger="' + encTriggerAttrs + '"]').jqm({trigger: trigger, onLoad: function(hash){onLoadjqm(hash);}, onHide: function(hash){onHide(hash);}, ajax:script});
				}
			}
		}
	});
}

var InitFlexSlider = function() {
	$('.flexslider:not(.thmb):not(.flexslider-init)').each(function(){
		var slider = $(this);
		var options;
		var defaults = {
			animationLoop: false,
			controlNav: false,
			directionNav: true,
			animation: "slide"
		}
		var config = $.extend({}, defaults, options, slider.data('plugin-options'));
		if(typeof(config.counts) != 'undefined' && config.direction !== 'vertical'){
			config.maxItems =  getGridSize(config.counts);
			config.minItems = getGridSize(config.counts);
			config.move = getGridSize(config.counts);
			config.itemWidth = 200;
		}

		config.after = config.start = function(slider){
			var eventdata = {slider: slider};
			BX.onCustomEvent('onSlide', [eventdata]);
		}

		config.end = function(slider){
			var eventdata = {slider: slider};
			BX.onCustomEvent('onSlideEnd', [eventdata]);
		}

		slider.flexslider(config).addClass('flexslider-init');
		if(config.controlNav)
			slider.addClass('flexslider-control-nav');
		if(config.directionNav)
			slider.addClass('flexslider-direction-nav');
	});
}


// BASKET
var setBasketItemsClasses = function(){
	if(typeof(arBasketItems) !== 'undefined' && Object.keys(arBasketItems).length){
		for(var key in arBasketItems){
			$('[data-item]').each(function(){
				if($(this).data('item').ID == key){
					$(this).find('.buy_block').addClass('in');
				}
			});
		}
	}
}

var Summ = function(el, counterInputValueNew, price){
	if(counterInputValueNew <= 0){
		counterInputValueNew = 1;
	}
	var summ = number_format(counterInputValueNew*price, 0, '.', ' ');
	var allSumm = 0;
	el.closest('.items').find('.item').each(function(){
		var $this = $(this),
			price = parseFloat($this.find('input[name=PRICE]').val()),
			count =  parseFloat($this.find('input.count').val());
		if(count <= 0){
			count = 1;
		}
		if(!isNaN(price) && !isNaN(count)){
			allSumm += count*price;
		}
	});
	allSumm = number_format(parseFloat(allSumm), 0, '.', ' ');
	el.closest('.item').find('.summ .price_val').text(summ);
	el.closest('.basket').find('.foot .total>span').text(allSumm);
}

var timerBasketUpdate = false;
var BasketCounter = function(el){
	var bClassMinus = (el.hasClass('minus')),
		bClassPlus = (el.hasClass('plus')),
		bClassCount = (el.hasClass('count')),
		$buyBlock = el.closest('.buy_block'),
		$counterInput = el.closest('.counter').find('input.count'),
		counterInputValue = parseFloat($.trim($counterInput.val())),
		price = parseFloat($buyBlock.find('input[name=PRICE]').val()),
		bAjax = (el.closest('.basket').length ? true : false);

	// class minus button
	if(bClassMinus){
		var counterInputValueNew = counterInputValue - 1;
		if(counterInputValueNew <= 0){
			counterInputValueNew = 1;
		}
		$counterInput.val(counterInputValueNew);
		if(bAjax){
			Summ(el, counterInputValueNew, price);
			if(timerBasketUpdate){
				clearTimeout(timerBasketUpdate);
				timerBasketUpdate = false;
			}
			timerBasketUpdate = setTimeout(function(){
				BasketUpdate(el, counterInputValueNew);
				timerBasketUpdate = false;
			}, 700);
		}
	}
	// class plus button
	else if(bClassPlus){
		var counterInputValueNew = counterInputValue + 1;
		var counterInputMaxCount = Math.pow(10, parseInt($counterInput.attr('maxlength'))) - 1;
		if(counterInputValueNew > counterInputMaxCount){
			counterInputValueNew = counterInputMaxCount;
		}
		$counterInput.val(counterInputValueNew);
		if(bAjax){
			Summ(el, counterInputValueNew, price);
			if(timerBasketUpdate){
				clearTimeout(timerBasketUpdate);
				timerBasketUpdate = false;
			}
			timerBasketUpdate = setTimeout(function(){
				BasketUpdate(el, counterInputValueNew);
				timerBasketUpdate = false;
			}, 700);
		}
	}
	// class input
	else if(bClassCount){
		var counterInputValueNew = counterInputValue;
		if(counterInputValueNew <= 0 || isNaN(counterInputValueNew)){
			counterInputValueNew = 1;
		}
		el.val(counterInputValueNew);
		if(bAjax){
			BasketUpdate(el, counterInputValueNew);
		}
	}

	var getCurUri = $.trim($('input[name=getPageUri]').val());
	if(!getCurUri && !el.closest('.basket.fly').length){
		$buyBlock.find('.to_cart').data('quantity', counterInputValueNew);
	}
}

var BasketUpdate = function(el, counterInputValueNew){
	var	itemData = el.closest('[data-item]').data('item'),
		itemData = (typeof(arBasketItems) === 'object' && typeof(arBasketItems[itemData.ID]) === 'object' ? arBasketItems[itemData.ID] : itemData),
		$buyBlock = el.closest('.buy_block'),
		scrollTop = ($('.basket.fly').length ? $('.basket.fly .items_wrap').scrollTop() : ($('.basket_top:visible').length ? $('.basket_top .items:visible').scrollTop() : ''));

	if(typeof(itemData) != 'undefined' && !isNaN(itemData.ID) && itemData.ID > 0 && !$buyBlock.hasClass('loading')){
		$.ajax({
			url: arScorpOptions['SITE_DIR'] + 'ajax/basket_items.php',
			data: {itemData: itemData, quantity: counterInputValueNew},
			beforeSend: function(){
				$buyBlock.addClass('loading');
			},
			complete: function(){
				$buyBlock.removeClass('loading');
			},
			success: function(data){
				if(typeof(data) === 'object'){
					arBasketItems = data;
				}

				var getCurUri = $.trim($('input[name=getPageUri]').val());

				if(typeof(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined' && arScorpOptions['THEME']['ORDER_BASKET_VIEW'] === 'HEADER' && $('.basket_top').length){
					$.ajax({
						url: arScorpOptions['SITE_DIR'] + 'ajax/basket_items.php',
						type: 'POST',
						beforeSend: function(){
							$buyBlock.addClass('loading');
						},
						complete: function(){
							$buyBlock.removeClass('loading');
						},
						success: function(html){
							$buyBlock.removeClass('in');

							$('.ajax_basket').html(html);
							$('.basket_top .items').scrollTop(scrollTop);

							if(!getCurUri){
								setTimeout(function(){
									$('.basket_top .dropdown').addClass('expanded');
								}, basketShowDelay);

								setTimeout(function(){
									$('.basket_top .dropdown').removeClass('expanded');
								}, basketHideDelay);
							}
						}
					});
				}

				if(typeof(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined' && arScorpOptions['THEME']['ORDER_BASKET_VIEW'] === 'FLY' && $('.basket.fly').length){
					$.ajax({
						url: arScorpOptions['SITE_DIR'] + 'ajax/basket_items.php',
						type: 'POST',
						beforeSend: function(){
							$buyBlock.addClass('loading');
						},
						complete: function(){
							$buyBlock.removeClass('loading');
						},
						success: function(html){
							$('.ajax_basket').html(html);
							$('.basket.fly .items_wrap').scrollTop(scrollTop);
						}
					});
				}

				if(getCurUri){
					$.ajax({
						url: getCurUri,
						type: 'POST',
						beforeSend: function(){
							$buyBlock.addClass('loading');
						},
						complete: function(){
							$buyBlock.removeClass('loading');
						},
						success: function(html){
							if($('.basket.default').length){
								$('.basket.default').html(html);
							}
						}
					});
				}
			}
		});
	}
}

var scrollToTopAnimateClassIn = false;
var scrollToTopAnimateClassOut = false;
var players = {}

var isMobile = jQuery.browser.mobile
if(isMobile){
	document.documentElement.className += ' mobile'
}

var $body = {}
var $scrolltotop = {}

$(document).ready(function(){
	$body = $('body')

	InitScrollToTop();
	CheckStickyFooter();

	if(!jQuery.browser.safari){
		CheckTopMenuDotted();
		CheckHeaderFixed();

		setTimeout(function() {
			$(window).resize(); // need to check resize flexslider & menu
		}, 350);

		$(window).scroll();
	}
	else{
		setTimeout(function() {
			$(window).resize(); // need to check resize flexslider & menu
			setTimeout(function(){
				CheckHeaderFixed();
				setTimeout(function(){
					$(window).scroll();
				}, 50);
			}, 50);
		}, 350);
	}

	setBasketItemsClasses();

	$('.blink img').blink();

	waitingNotExists('#bx-composite-banner .bx-composite-btn', '#footer .col-sm-3.hidden-md.hidden-lg #bx-composite-banner .bx-composite-btn', 500, function() {
		$('#footer .col-sm-3.hidden-md.hidden-lg #bx-composite-banner').html($('#bx-composite-banner .bx-composite-btn').parent().html());
	});

	if(arScorpOptions['THEME']['USE_DEBUG_GOALS'] === 'Y'){
		$.cookie('_ym_debug', 1, {path: '/',});
	}
	else{
		$.cookie('_ym_debug', null, {path: '/',});
	}

	$.extend( $.validator.messages, {
		required: BX.message('JS_REQUIRED'),
		email: BX.message('JS_FORMAT'),
		equalTo: BX.message('JS_PASSWORD_COPY'),
		minlength: BX.message('JS_PASSWORD_LENGTH'),
		remote: BX.message('JS_ERROR')
	});

	$.validator.addMethod(
		'regexp', function( value, element, regexp ){
			var re = new RegExp( regexp );
			return this.optional( element ) || re.test( value );
		},
		BX.message('JS_FORMAT')
	);

	$.validator.addMethod(
		'filesize', function( value, element, param ){
			return this.optional( element ) || ( element.files[0].size <= param )
		},
		BX.message('JS_FILE_SIZE')
	);

	$.validator.addMethod(
		'date', function( value, element, param ) {
			var status = false;
			if(!value || value.length <= 0){
				status = true;
			}
			else{
				var re = new RegExp('^([0-9]{2})(.)([0-9]{2})(.)([0-9]{4})$');
				var matches = re.exec(value);
				if(matches){
					var composedDate = new Date(matches[5], (matches[3] - 1), matches[1]);
					status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[1]) && (composedDate.getFullYear() == matches[5]));
				}
			}
			return status;
		}, BX.message('JS_DATE')
	);

	$.validator.addMethod(
		'datetime', function( value, element, param ) {
			var status = false;
			if(!value || value.length <= 0){
				status = true;
			}
			else{
				var re = new RegExp('^([0-9]{2})(.)([0-9]{2})(.)([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2})$');
				var matches = re.exec(value);
				if(matches){
					var composedDate = new Date(matches[5], (matches[3] - 1), matches[1], matches[6], matches[7]);
					status = ((composedDate.getMonth() == (matches[3] - 1)) && (composedDate.getDate() == matches[1]) && (composedDate.getFullYear() == matches[5]) && (composedDate.getHours() == matches[6]) && (composedDate.getMinutes() == matches[7]));
				}
			}
			return status;
		}, BX.message('JS_DATETIME')
	);

	$.validator.addMethod(
		'extension', function(value, element, param){
			param = typeof param === 'string' ? param.replace(/,/g, '|') : 'png|jpe?g|gif';
			return this.optional(element) || value.match(new RegExp('.(' + param + ')$', 'i'));
		}, BX.message('JS_FILE_EXT')
	);

	$.validator.addMethod(
		'captcha', function( value, element, params ){
			return $.validator.methods.remote.call(this, value, element,{
				url: arScorpOptions['SITE_DIR'] + 'ajax/check-captcha.php',
				type: 'post',
				data:{
					captcha_word: value,
					captcha_sid: function(){
						return $(element).closest('form').find('input[name="captcha_sid"]').val();
					}
				}
			});
		},
		BX.message('JS_ERROR')
    );

	/*reload captcha*/
	$('body').on( 'click', '.refresh', function(e){
		e.preventDefault();
		$.ajax({
			url: arScorpOptions['SITE_DIR'] + 'ajax/captcha.php'
		}).done(function(text){
			$('.captcha_sid').val(text);
			$('.captcha_img').attr('src', '/bitrix/tools/captcha.php?captcha_sid=' + text);
		});
	});

	$.validator.addMethod(
		'recaptcha', function(value, element, param){
			var id = $(element).closest('form').find('.g-recaptcha').attr('data-widgetid');
			if(typeof id !== 'undefined'){
				return grecaptcha.getResponse(id) != '';
			}
			else{
				return true;
			}
		}, BX.message('JS_RECAPTCHA_ERROR')
	);

	$.validator.addMethod(
		'processing_approval', function(value, element, param){
			return $(element).is(':checked');
		}, BX.message('JS_PROCESSING_ERROR')
	);

	$.validator.addClassRules({
		'phone':{
			regexp: arScorpOptions['THEME']['VALIDATE_PHONE_MASK']
		},
		'confirm_password':{
			equalTo: 'input[name="REGISTER\[PASSWORD\]"]',
			minlength: 6
		},
		'password':{
			minlength: 6
		},
		'inputfile':{
			extension: arScorpOptions['THEME']['VALIDATE_FILE_EXT'],
			filesize: 5000000
		},
		'datetime':{
			datetime: ''
		},
		'captcha':{
			captcha: ''
		},
		'recaptcha':{
			recaptcha: ''
		},
		'processing_approval':{
			processing_approval: ''
		}
	});

	InitFlexSlider();

	// for check flexslider bug in composite mode
	waitingNotExists('.detail .galery #slider', '.detail .galery #slider .flex-viewport', 1000, function() {
		InitFlexSlider();
		setTimeout(function() {
			$(window).resize();
		}, 350);
	});

	/*check mobile device*/
	if(jQuery.browser.mobile){
		$('.style-switcher').addClass('hidden');
		$('.hint span').remove();

		$('*[data-event="jqm"]').live('click', function(e){
			e.preventDefault();
			var _this = $(this);
			var name = _this.data('name');

			if(name.length){
				var script = arScorpOptions['SITE_DIR'] + 'form/';
				var paramsStr = ''; var arTriggerAttrs = {};
				$.each(_this.get(0).attributes, function(index, attr){
					var attrName = attr.nodeName;
					var attrValue = _this.attr(attrName);
					arTriggerAttrs[attrName] = attrValue;
					if(/^data\-param\-(.+)$/.test(attrName)){
						var key = attrName.match(/^data\-param\-(.+)$/)[1];
						paramsStr += key + '=' + attrValue + '&';
					}
				});

				var triggerAttrs = JSON.stringify(arTriggerAttrs);
				var encTriggerAttrs = encodeURIComponent(triggerAttrs);
				script += '?name=' + name + '&' + paramsStr + 'data-trigger=' + encTriggerAttrs;
				location.href = script;
			}
		});

		$('.fancybox').removeClass('fancybox');
	}
	else{
		$('*[data-event="jqm"]').live('click', function(e){
			e.preventDefault();
			$(this).jqmEx();
			$(this).trigger('click');
		});
	}

	$('a.fancybox:has(img)').fancybox();

	// Responsive Menu Events
	var addActiveClass = false;
	$('#mainMenu li.dropdown > a > i, #mainMenu li.dropdown-submenu > a > i').on('click', function(e){
		e.preventDefault();
		if($(window).width() > 979) return;
		addActiveClass = $(this).closest('li').hasClass('resp-active');
		// $('#mainMenu').find('.resp-active').removeClass('resp-active');
		if(!addActiveClass){
			$(this).closest("li").addClass("resp-active");
		}else{
			$(this).closest("li").removeClass("resp-active");
		}
	});

	if($('.styled-block .row > div.col-md-3').length){
		BX.addCustomEvent('onWindowResize', function(eventdata) {
			try{
				ignoreResize.push(true);
				$('.styled-block .row > div.col-md-3').each(function() {
					$(this).css({'height': '', 'line-height': ''});
					var z = parseInt($('.body_media').css('top'));
					if(z > 0){
						var rowHeight = $(this).parents('.row').first().actual('outerHeight');
						$(this).css({'height': rowHeight + 'px', 'line-height' : rowHeight + 'px'});
					}
				});
			}
			catch(e){}
			finally{
				ignoreResize.pop();
			}
		});
	}

	if($('.order-block').length){
		BX.addCustomEvent('onWindowResize', function(eventdata) {
			try{
				ignoreResize.push(true);
				$('.order-block').each(function() {
					var cols = $(this).find('.row > div');
					if(cols.length){
						var colFirst = cols.first();
						var colLast = cols.last();
						var colText = colLast.find('.text');
						var bText = colText.length;
						var bOnlyText = cols.length === 1 && bText;
						var bPrice = colFirst.find('.price').length;
						var z = parseInt($('.body_media').css('top'));

						cols.css({'height': '', 'padding-top': '', 'padding-bottom': ''});
						colText.css({'height': '', 'padding-top': '', 'padding-bottom': ''});
						if((!bPrice && z > 0) || (bPrice && z > 1)){
							var minHeight = 83;

							if(!bOnlyText){
								var colFirst_height = colFirst.outerHeight();
								colFirst_height = colFirst_height >= minHeight ? colFirst_height : minHeight;
							}

							if(bText){
								var colLast_height = colLast.outerHeight();
								colLast_height = colLast_height >= minHeight ? colLast_height : minHeight;
							}

							var colMax_height = (bText ? (!bOnlyText ? (colLast_height >= colFirst_height ? colLast_height : colFirst_height) : colLast_height) : colFirst_height);

							if(!bOnlyText){
								var textPadding = 22 + (colMax_height - colFirst.outerHeight()) / 2;
								colFirst.css({'padding-top': textPadding + 'px', 'padding-bottom': textPadding + 'px', 'height': colMax_height + 'px'});
							}
							if(bText){
								colLast.css({'height': colMax_height + 'px'});
								var textPadding = 22 + (colMax_height - colText.outerHeight()) / 2;
								colText.css({'padding-top': textPadding + 'px', 'padding-bottom': textPadding + 'px', 'height': colMax_height + 'px'});
							}
						}
					}
				});
			}
			catch(e){}
			finally{
				ignoreResize.pop();
			}
		});
	}

	$(document).on('click', '.mega-menu .dropdown-menu', function(e){
		e.stopPropagation()
	});

	$(document).on('click', '.mega-menu .dropdown-toggle.more-items', function(e){
		e.preventDefault();
	});

	$('.table-menu .dropdown,.table-menu .dropdown-submenu,.table-menu .dropdown-toggle').live('mouseenter', function() {
		CheckTopVisibleMenu();
	});

	$('.mega-menu .search-item .search-icon, .menu-row #title-search .fa-close').live('click', function(e) {
		e.preventDefault();
		$('.menu-row #title-search').toggleClass('hide');
	});

	$('.mega-menu ul.nav .search input').live('keyup', function(e) {
		var inputValue = $(this).val();
		$('.menu-row > .search input').val(inputValue);
		if(e.keyCode == 13){
			$('.menu-row > .search form').submit();
		}
	});

	$('.menu-row > .search input').live('keyup', function(e) {
		var inputValue = $(this).val();
		$('.mega-menu ul.nav .search input').val(inputValue);
		if(e.keyCode == 13){
			$('.menu-row > .search form').submit();
		}
	});

	$('.mega-menu ul.nav .search button').live('click', function(e) {
		e.preventDefault();
		var inputValue = $(this).parents('.search').find('input').val();
		$('.menu-and-search .search input').val(inputValue);
		$('.menu-row > .search form').submit();
	});

	$('.filter .calendar').live('click', function() {
		var button = $(this).next();
		if(button.hasClass('calendar-icon')){
			button.trigger('click');
		}
	});

	/* toggle */
	var $this = this,
		previewParClosedHeight = 25;

	$('section.toggle > label').prepend($('<i />').addClass('fa fa-plus'));
	$('section.toggle > label').prepend($('<i />').addClass('fa fa-minus'));
	$('section.toggle.active > p').addClass('preview-active');
	$('section.toggle.active > div.toggle-content').slideDown(350, function() {});

	$('section.toggle > label').click(function(e){
		var parentSection = $(this).parent(),
			parentWrapper = $(this).parents('div.toogle'),
			previewPar = false,
			isAccordion = parentWrapper.hasClass('toogle-accordion');

		if(isAccordion && typeof(e.originalEvent) != 'undefined') {
			parentWrapper.find('section.toggle.active > label').trigger('click');
		}

		parentSection.toggleClass('active');

		// Preview Paragraph
		if( parentSection.find('> p').get(0) ){
			previewPar = parentSection.find('> p');
			var previewParCurrentHeight = previewPar.css('height');
			previewPar.css('height', 'auto');
			var previewParAnimateHeight = previewPar.css('height');
			previewPar.css('height', previewParCurrentHeight);
		}

		// Content
		var toggleContent = parentSection.find('> div.toggle-content');

		if( parentSection.hasClass('active') ){
			$(previewPar).animate({
				height: previewParAnimateHeight
			}, 350, function() {
				$(this).addClass('preview-active');
			});
			toggleContent.slideDown(350, function() {});
		}
		else{
			$(previewPar).animate({
				height: previewParClosedHeight
			}, 350, function() {
				$(this).removeClass('preview-active');
			});
			toggleContent.slideUp(350, function() {});
		}
	});

	/* accordion */
	$('.accordion-head').on('click', function(e){
		e.preventDefault();
		if(!$(this).next().hasClass('collapsing')){
			$(this).toggleClass('accordion-open');
			$(this).toggleClass('accordion-close');
		}
	});

	/* progress bar */
	$('[data-appear-progress-animation]').each(function(){
		var $this = $(this);
		$this.appear(function(){
			var delay = ($this.attr('data-appear-animation-delay') ? $this.attr('data-appear-animation-delay') : 1);
			if( delay > 1 )
				$this.css('animation-delay', delay + 'ms');
			$this.addClass($this.attr('data-appear-animation'));

			setTimeout(function(){
				$this.animate({
					width: $this.attr('data-appear-progress-animation')
				}, 1500, 'easeOutQuad', function() {
					$this.find('.progress-bar-tooltip').animate({
						opacity: 1
					}, 500, 'easeOutQuad');
				});
			}, delay);
		}, {accX: 0, accY: -50});
	});

	$('a[rel=tooltip]').tooltip();
	$('span[data-toggle=tooltip]').tooltip();

	$('select.sort').live('change', function(){
		location.href = $(this).val();
	});

	setTimeout(function(th){
		$('.catalog.group.list .item').each(function(){
			var th = $(this);
			if((tmp = th.find('.image').outerHeight() - th.find('.text_info').outerHeight()) > 0){
				th.find('.text_info .titles').height(th.find('.text_info .titles').outerHeight() + tmp);
			}

		})
	}, 50);

	/*item galery*/
	$('.thumbs .item a').live('click', function(e){
		e.preventDefault();
		$('.thumbs .item').removeClass('current');
		$(this).closest('.item').toggleClass('current');
		$('.slides li' + $(this).attr('href')).addClass('current').siblings().removeClass('current');
	});

	$('header.fixed .btn-responsive-nav').live('click', function() {
		$('html, body').animate({scrollTop: 0}, 400);
	});

	$('body').on('click', '.form .refresh-page', function(){
		var href = $(this).attr('data-url');
		if(typeof href !== 'undefined'){
			location.href = href;
		}
		else{
			location.href = location.href;
		}
	});

	$(document).on('click', '.print', function(){
		window.print();
	});

	// click on HTML5 video
	$(document).on('click', 'video.video', function(e){
		var videoID = e.target.id.replace('player_', '')
	    if(videoID){
	    	if(players[videoID].playing){
				e.target.pause()
	    	}
	    	else{
	    		e.target.play()
	    	}
	    }
	})

	// START VIDEO BUTTON
	$(document).on('click', '.banners-big .item .btn-video', function(){
		$(this).addClass('loading');
		startMainBannerSlideVideo($(this).closest('.item'));
	});

	// basket fly auto close
	$(document).on('click', function(){
		if($('.basket.fly').length && $('.ajax_basket').hasClass('opened')){
			$('.ajax_basket').removeClass('opened');
		}
	});

	$(document).on('click', '.basket.fly', function(e){
		e.stopPropagation();
	});

	// counter input events
	$(document).on('keydown', '.count', function(e){
		// Allow: backspace, delete, tab, escape, enter and .
		if($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
			 // Allow: Ctrl+A, Command+A
			(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
			 // Allow: home, end, left, right, down, up
			(e.keyCode >= 35 && e.keyCode <= 40)) {
				 // let it happen, don't do anything
				 return;
		}
		// Ensure that it is a number and stop the keypress
		if((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)){
			e.preventDefault();
		}
	});

	$(document).on('keyup', '.count', function(e){
		var $this = $(this);
		Summ($this, $this.val(), $this.closest('.item').find('input[name=PRICE]').val());
	});

	$(document).on('blur', '.count', function(){
		BasketCounter($(this));
	});

	$(document).on('click', '.minus, .plus', function(e){
		e.stopPropagation();
		BasketCounter($(this));
	});

	var basketShowDelay = 100;
	var basketHideDelay = 1000;

	// Add2Basket
	$(document).on('click', '.to_cart', function(e){
		e.stopPropagation();

		var $item = $(this).closest('[data-item]'),
			$buyBlock = $item.find('.buy_block'),
			itemData = $item.data('item'),
			itemQuantity = parseFloat($buyBlock.find('.to_cart').data('quantity'));

		if(isNaN(itemQuantity) || itemQuantity <= 0){
			itemQuantity = 1;
		}

		if(!isNaN(itemData.ID) && parseInt(itemData.ID) > 0 && !$buyBlock.hasClass('loading')){
			$.ajax({
				url: arScorpOptions['SITE_DIR'] + 'ajax/basket_items.php',
				type: 'POST',
				data: {itemData: itemData, quantity: itemQuantity},
				beforeSend: function(){
					$buyBlock.addClass('loading');
				},
				complete: function(){
					$buyBlock.removeClass('loading');
				},
				success: function(html){
					$buyBlock.addClass('in');

					var countItem = ($('.basket').length ? parseInt($('.basket .count').text()) : parseInt($('.basket_top:visible .count').text()));
					++countItem;
					$('.basket_top .count, .basket .count').text(countItem).removeClass('empted');
					$('.ajax_basket').html(html);

					if(arScorpOptions['THEME']['USE_SALE_GOALS'] !== 'N'){
						var eventdata = {goal: 'goal_basket_add', params: {itemData: itemData, quantity: itemQuantity}};
						BX.onCustomEvent('onCounterGoals', [eventdata]);
					}

					if(typeof(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined' && $.trim(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) === 'HEADER' && $('.basket_top').length){
						if(!$('.basket_top .dropdown').hasClass('expanded')){
							setTimeout(function(){
								$('.basket_top .dropdown').addClass('expanded');
							}, basketShowDelay);
							setTimeout(function(){
								$('.basket_top .dropdown').removeClass('expanded');
							}, basketHideDelay);
						}
					}
					else if(typeof(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined' && $.trim(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) === 'FLY' && $('.basket.fly').length){
						setTimeout(function(){
							if(!$('.ajax_basket').hasClass('opened')){
								$('.ajax_basket').addClass('opened');
							}
						}, basketShowDelay);
					}
				}
			});
		}
	});

	// remove from basket
	$(document).on('click', '.remove', function(){
		var $item = $(this).closest('[data-item]'),
			$buyBlock = $item.find('.buy_block'),
			itemData = $item.data('item'),
			bRemove = 'Y',
			bRemoveAll = ($.trim($(this).closest('[data-remove_all]').data('remove_all')) === 'Y' ? 'Y' : false);
			countItem = ($('.basket').length ? parseInt($('.basket .item').length) : parseInt($('.basket_top:visible .item').length)),
			bOneItem = (countItem - 1 <= 0),
			scrollTop = ($('.basket.fly').length ? $('.basket.fly .items_wrap').scrollTop() : ($('.basket_top:visible').length ? $('.basket_top .items:visible').scrollTop() : ''));

		var _ajax = function(){
			$.ajax({
				url: arScorpOptions['SITE_DIR'] + 'ajax/basket_items.php',
				data: {itemData: itemData, remove: bRemove, removeAll: bRemoveAll},
				beforeSend: function(){
					$buyBlock.addClass('loading');
				},
				complete: function(){
					$buyBlock.removeClass('loading');
				},
				success: function(html){
					if(bRemoveAll){
						$('.buy_block').removeClass('in');
						$('.basket .count, .basket_top .count').text(0).addClass('empted');
					}
					else{
						$('[data-item]').each(function(){
							if($(this).data('item').ID == itemData.ID){
								$(this).find('.buy_block').removeClass('in');
							}
						});

						var countItem = ($('.basket').length ? parseInt($('.basket .count').text()) : parseInt($('.basket_top:visible .count').text()));
						--countItem;
						$('.basket .count, .basket_top .count').text(countItem);
						if(!countItem){
							$('.basket .count, .basket_top .count').addClass('empted');
						}
					}

					$('.ajax_basket').html(html);

					if(typeof(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined' && $.trim(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) === 'HEADER' && $('.basket_top').length){
						$('.basket_top .items').scrollTop(scrollTop);
					}

					if(typeof(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) !== 'undefined' && $.trim(arScorpOptions['THEME']['ORDER_BASKET_VIEW']) === 'FLY' && $('.basket.fly').length){
						$('.ajax_basket').addClass('opened');
						$('.basket.fly .items_wrap').scrollTop(scrollTop);
					}

					if(arScorpOptions['THEME']['USE_SALE_GOALS'] !== 'N'){
						var eventdata = {goal: 'goal_basket_remove', params: {itemData: itemData, remove: bRemove, removeAll: bRemoveAll}};
						BX.onCustomEvent('onCounterGoals', [eventdata]);
					}

					var getCurUri = $.trim($('input[name=getPageUri]').val());
					if(getCurUri){
						$.ajax({
							url: getCurUri,
							type: 'POST',
							beforeSend: function(){
								$buyBlock.addClass('loading');
							},
							complete: function(){
								$buyBlock.removeClass('loading');
							},
							success: function(html){
								if($('.basket.default').length){
									$('.basket.default').html(html);
								}
							}
						});
					}

				}
			});
		}

		if(bRemoveAll || (typeof(itemData) !== 'undefined' && (!isNaN(itemData.ID) && itemData.ID > 0) && !$buyBlock.hasClass('loading'))){
			if(bRemoveAll){
				$('.basket_wrap').fadeOut(200, function(){
					$('.basket').find('.basket_empty').fadeIn(200, function(){
						_ajax();
					});
				});
			}
			else{
				if(bOneItem){
					if($item.closest('.basket_top').length){
						$item.closest('.dropdown').animate({opacity: 0}, 200, function(){
							_ajax();
						});
					}
					else{
						$item.closest('.basket_wrap').fadeOut(200, function(){
							$item.closest('.basket').find('.basket_empty').fadeIn(200, function(){
								_ajax();
							});
						});
					}
				}
				else{
					$item.animate({opacity: 0}, 200).slideUp(200, function(){
						_ajax();
					});
				}
			}
		}
	});
});


var waitCounter = function(idCounter, delay, callback){
	var obCounter = window['yaCounter' + idCounter];
	if(typeof obCounter == 'object'){
		if(typeof callback == 'function'){
			callback();
		}
	}
	else{
		setTimeout(function(){
			waitCounter(idCounter, delay, callback);
		}, delay);
	}
}

var waitReCaptcha = function(delay, callback){
	if(typeof grecaptcha == 'object'){
		if(typeof callback == 'function'){
			callback();
		}
	}
	else{
		setTimeout(function(){
			waitReCaptcha(delay, callback);
		}, delay);
	}
}

var reCaptchaRender = function(response){
	if($('.g-recaptcha:not(.rendered)').length){
		waitReCaptcha(50, function(){
			$('.g-recaptcha:not(.rendered)').each(function(){
				$this = $(this);
				$this.addClass('rendered')
				var id = grecaptcha.render($this[0], {
					sitekey: $this.attr('data-sitekey'),
					theme: $this.attr('data-theme'),
					size: $this.attr('data-size'),
					callback: $this.attr('data-callback'),
				});
				$this.attr('data-widgetid', id);
			});
		});
	}
}

var reCaptchaVerify = function(response){
	$('.g-recaptcha.rendered').each(function(){
		var id = $(this).attr('data-widgetid');
		if(typeof(id) !== 'undefined'){
			if(grecaptcha.getResponse(id) != ''){
				$(this).closest('form').find('.recaptcha').valid();
			}
		}
	});
}

// Events
var timerScroll = false, ignoreScroll = [], documentScrollTopLast = $(document).scrollTop();
$(window).scroll(function(){
	documentScrollTop = $(document).scrollTop();

	CheckPopupTop();
	CheckScrollToTop();

	if(!ignoreScroll.length){
		if(timerScroll){
			clearTimeout(timerScroll);
			timerScroll = false;
		}
		timerScroll = setTimeout(function(){
			BX.onCustomEvent('onWindowScroll', false);
		}, 100);
	}

	documentScrollTopLast = documentScrollTop;
});

var timerResize = false, ignoreResize = [];
$(window).resize(function(){
	documentScrollTop = $(document).scrollTop();

	CheckPopupTop();
	CheckScrollToTop();

	if(!ignoreResize.length){
		if(timerResize){
			clearTimeout(timerResize);
			timerResize = false;
		}
		timerResize = setTimeout(function(){
			BX.onCustomEvent('onWindowResize', false);
		}, 100);
	}

	documentScrollTopLast = $(document).scrollTop();
});

BX.addCustomEvent('onWindowScroll', function(eventdata) {
	try{
		ignoreScroll.push(true);
	}
	catch(e){}
	finally{
		ignoreScroll.pop();
	}
});

BX.addCustomEvent('onWindowResize', function(eventdata) {
	try{
		ignoreResize.push(true);
		CheckTopMenuDotted();
		CheckTopVisibleMenu();
		CheckFlexSlider();
		CheckMainBannerSliderVText($('.banners-big .flexslider'));
		CheckObjectsSizes();
		CoverPlayer();
	}
	catch(e){}
	finally{
		ignoreResize.pop();
	}
});

BX.addCustomEvent('onSlide', function(eventdata) {
	try{
		ignoreResize.push(true);
		if(eventdata){
			var slider = eventdata.slider;
			if(slider){
				// add classes .curent & .shown to slide
				slider.find('.item').removeClass('current');
				var curSlide = slider.find('.item.flex-active-slide');
				var curSlideIndex = curSlide.attr('data-slide_index');
				if(curSlideIndex.length){
					curSlide.addClass('current');
					slider.find('.item[data-slide_index=' + curSlideIndex + ']').addClass('shown');
					slider.resize();

					// set main banners text vertical center
					CheckMainBannerSliderVText(slider);

					// pause play video
					if(typeof(players) !== 'undefined' && players){
						for(var j in players){
							if(players[j].playing && !players[j].clone && (players[j].slideIndex != curSlideIndex)){
								if((typeof window[players[j].id] == 'object')){
									if(players[j].videoPlayer === 'YOUTUBE'){
										window[players[j].id].pauseVideo()
									}
									else if(players[j].videoPlayer === 'VIMEO'){
										window[players[j].id].pause()
									}
									else if(players[j].videoPlayer === 'RUTUBE'){
										document.getElementById(players[j].id).contentWindow.postMessage(JSON.stringify({
										    type: 'player:pause',
										    data: {}
										}), '*')
									}
									else if(players[j].videoPlayer === 'HTML5'){
										document.getElementById(players[j].id).pause()
									}
								}
							}
						}
					}

					// autoplay video
					var bVideoAutoPlay = curSlide.attr('data-video_autoplay') == 1
					if(bVideoAutoPlay){
						startMainBannerSlideVideo(curSlide)
					}
				}
			}
		}
	}
	catch(e){}
	finally{
		ignoreResize.pop();
	}
});

BX.addCustomEvent('onSlideEnd', function(eventdata) {
	try{
		ignoreResize.push(true);
		if(eventdata){
			var slider = eventdata.slider;
			if(slider){

			}
		}
	}
	catch(e){}
	finally{
		ignoreResize.pop();
	}
});

BX.addCustomEvent('onCounterGoals', function(eventdata){
	if(arScorpOptions['THEME']['USE_YA_COUNTER'] === 'Y'){
		var idCounter = arScorpOptions['THEME']['YA_COUNTER_ID'];
		idCounter = parseInt(idCounter);

		if(typeof eventdata != 'object'){
			eventdata = {goal: 'undefined'};
		}
		if(typeof eventdata.goal != 'string'){
			eventdata.goal = 'undefined';
		}

		if(idCounter){
			try{
				waitCounter(idCounter, 50, function(){
					var obCounter = window['yaCounter' + idCounter];
					if(typeof obCounter == 'object'){
						obCounter.reachGoal(eventdata.goal);
					}
				});
			}
			catch(e){
				console.error(e)
			}
		}
		else{
			console.info('Bad counter id!', idCounter);
		}
	}
})