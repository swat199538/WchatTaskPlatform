function listtutorials(){
		$(".wrap").append("<div class='js_mag_flow1'><div class='flowtinymask'></div><div class='listmotion2'></div><div class='rouse_right'></div><div class='rouse_right2'></div></div><div class='listmotion1 js_mag_flow2'><span></span><p></p></div>")
		
		$(".js_mag_flow2").hide();
		document.ontouchmove=function(){
					return false;
				}
		$(".rouse_right").on('touchend',function(){
			$(this).fadeOut(400);
			$('.rouse_right2').fadeIn(400);
//			$(this).addClass('rouse_right2');
			setTimeout(function () {
//				$(".wrap").append("<div class='listmotion1 js_mag_flow2'></div>");
				$(".js_mag_flow1").remove()
				$(".js_mag_flow2").show();
				$('.listguide .trial:first-child').addClass('first_r')
				$("body,html").css('overflow','hidden')
			},800);
			$(".js_mag_flow2").click(function(){
				$('.listmotion1').remove();
				$('.listguide .trial:first-child').removeClass('first_r')
				$("body,html").removeAttr("style")
				document.ontouchmove=function(){
					false;
				}
			});
		});
	};
function detailstutorials(){
		$('.app_detail').append("<div class='detailsmotion1'></div>");
		$('body').append("<div class='flowtinymask'></div>");
		$("body,html").css('overflow','hidden');
		$('#parent').css({"position":"relative","z-index":"950",'background':'#fff'});
		document.ontouchmove=function(){
					return false;
				}
		$(document).bind('touchend',function(){
			$('.detailsmotion1').remove();
			$('#parent').removeAttr('style');
			$('.info_list').append("<span class='appranking_ts'></span><div class='appranking_bj'></div>");
			$('.t_ranking,.appicon').css({"position":"relative","z-index":"950"});
			$('.app_detail').append("<div class='detailsmotion2'></div>");
			$(document).bind('touchend',function(){
				$(".t_ranking,.appicon").removeAttr("style")
				$(".detailsmotion1,.appranking_ts,.appranking_bj,.detailsmotion2").remove();
				$('.trial_info').append("<div class='detailsmotion3'></div>");
				$('.info_list').css("z-index","950");
				$(document).bind('touchend',function(){
					$("body,html").removeAttr("style")
					document.ontouchmove=function(){
						false;
						}
					$('.trial_info').append("<div class='detailsmotion3'></div>");
					$('.info_list').removeAttr('style');
					$('.detailsmotion3').remove();
					$('.flowtinymask').remove();
				});
			});
		});		
	};
	function detailstutorials2(){
		$("body,html").css('overflow','hidden');
		$('body').append("<div class='flowtinymask'></div>");
		document.ontouchmove=function(){
					return false;
				}
		$('.info_list').append("<span class='appranking_ts'></span><div class='appranking_bj'></div>");
		$('.t_ranking,.appicon').css({"position":"relative","z-index":"950"});
		$('.app_detail').append("<div class='detailsmotion2'></div>");
		$(document).bind('touchend',function(){
			$(".t_ranking,.appicon").removeAttr("style")
			$(".detailsmotion1,.appranking_ts,.appranking_bj,.detailsmotion2").remove();
			$('.app_detail').append("<div class='detailsmotion1'></div>");
			$('#parent').css({"position":"relative","z-index":"950",'background':'#fff'});
			$(document).bind('touchend',function(){
				$("body,html").removeAttr("style")
				document.ontouchmove=function(){
					false;
				}
				$('.detailsmotion1').remove();
				$('.flowtinymask').remove();
			});
		});
	};