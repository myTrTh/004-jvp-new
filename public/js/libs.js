function vote_widget(){$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/vote/show",dataType:"json",success:function(t){if(1!=t.error){if($("#vote-header").html(t.vote.title),"close"==t.access){$html1='<div id="error-vote-msg"></div><form id="widget-form" method="post">',$html2=[];for(var e=0;e<t.vote.options.length;e++)1==t.vote.type?$html2[e]='<div class="checkbox-line"><input type="radio" name="vote_options" value="'+t.vote.options[e].id+'">'+t.vote.options[e].title+"</div>":$html2[e]='<div class="checkbox-line"><input type="checkbox" name="vote_options[]" value="'+t.vote.options[e].id+'">'+t.vote.options[e].title+"</div>";$html2=$html2.join(""),$html3='<input id="token-input" type="hidden" name="_csrf_token" value=""><input id="widget-vote-send" type="submit" value="Голосовать"></form>'}else if("open"==t.access){$html1='<div class="vote-result-bar"><div class="vote-result-name">ВСЕГО</div><div class="vote-result-info">'+t.count+'</div><div class="vote-result-percent"></div></div>',$html2=[];var n=t.count;for(e=0;e<t.sort_options.length;e++){if(0==n)var s=0;else s=100*t.sort_options[e].users.length/n;if(0!=t.count?percent=100*t.sort_options[e].users.length/t.count:percent=0,100!=percent)var o=percent.toFixed(1);else o=percent;$html2[e]='<div class="vote-option-result"><div class="vote-option-bar"><div class="vote-option-bar-name">'+t.sort_options[e].title+'</div><div class="vote-option-bar-info">'+t.sort_options[e].users.length+'</div><div class="vote-option-bar-percent">'+o+'%</div></div><div class="vote-progress-bar"><div class="progress-line" style="width: '+s+'%"></div></div></div>'}$html2=$html2.join(""),$html3=""}$("#vote-content").html($html1+$html2+$html3+"<br>")}}})}$(function(){$(".admin-panel-users").on("click",function(){$(this).next().slideToggle(300)})}),$(function(){$('[id ^= "set-permissions"]').on("click",function(){var e=$(this).attr("id").substr(16),t=$("#form-per-"+e+' input[name="_csrf_token"]').val(),n=[];$("#form-per-"+e+" input:checkbox:checked").each(function(){n.push($(this).val())});var s="user="+encodeURIComponent(e)+"&permissions="+encodeURIComponent(n)+"&_csrf_token="+encodeURIComponent(t);$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/admin/permissions",data:s,type:"POST",dataType:"json",success:function(t){1==t.success?($(".error-pfield-"+e).html(""),$(".success-pfield-"+e).html(t.message)):($(".success-pfield-"+e).html(""),$(".error-pfield-"+e).html(t))}})})}),$(function(){$('[id ^= "set-roles"]').on("click",function(){var e=$(this).attr("id").substr(10),t=$("#form-rol-"+e+' input[name="_csrf_token"]').val(),n=[];$("#form-rol-"+e+" input:checkbox:checked").each(function(){n.push($(this).val())}),console.log(n);var s="user="+encodeURIComponent(e)+"&roles="+encodeURIComponent(n)+"&_csrf_token="+encodeURIComponent(t);$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/admin/roles",data:s,type:"POST",dataType:"json",success:function(t){1==t.success?($(".error-rfield-"+e).html(""),$(".success-rfield-"+e).html(t.message)):($(".success-rfield-"+e).html(""),$(".error-rfield-"+e).html(t))}})})}),$(function(){$(".bbimg").on("click",function(){var t=$(this).attr("id"),e=$("textarea");if("post"==t)var n="post:",s="";else n="["+t+"]",s="[/"+t+"]";var o=e[0].selectionStart,i=e[0].selectionEnd,a=e.val(),r=e.val().substr(o,i-o),l=n+r+s,c=e.val().length,u=a.substr(0,o),d=a.substr(i,c);e.val(u+l+d);e.val().length;e.focus(),0==r.length?e[0].setSelectionRange(o+n.length,o+n.length):e[0].setSelectionRange(o,i+n.length+s.length)})}),$(function(){$(".quote").on("click",function(){var t=$(this).attr("id").substr(5),e="[quote author="+$(this).parent().parent().parent().children().children().children().html().trim()+" date="+$("#hidden-date-"+t).text().trim()+" post="+t+"]\n"+$("#hidden-message-"+t).text().trim()+"\n[/quote]\n\n",n=$("textarea"),s=n[0].selectionStart,o=n[0].selectionEnd,i=n.val(),a=i.substr(0,s),r=n.val().length,l=i.substr(o,r);return n.val(a+e+l),n.focus(),n[0].setSelectionRange(s+e.length,s+e.length),!1})}),$(function(){$(".headsmile").on("click",function(){$(".smilepanel").slideToggle(200)})}),$(function(){$(".smiles").on("click",function(){var t=$(this).attr("id"),e=$("textarea"),n=t,s=e[0].selectionStart,o=e[0].selectionEnd,i=e.val(),a=e.val().substr(s,o-s),r=n+a+"",l=e.val().length,c=i.substr(0,s),u=i.substr(o,l);e.val(c+r+u);e.val().length;e.focus(),0==a.length?e[0].setSelectionRange(s+n.length,s+n.length):e[0].setSelectionRange(s,o+n.length+"".length)})}),$(function(){$(document).on("click",".spoiler-name",function(){var t=$(this).parent();t.next().slideToggle(300),"+"==$(".sign:first",t).html()?$(".sign:first",t).html("−"):$(".sign:first",t).html("+")})}),$(function(){$('div[id^="nach"]').on("mouseenter",function(t){t.preventDefault(),$(".toolkit",this).stop(!0,!1).slideDown(200)})}),$(function(){$('div[id^="nach"]').on("mouseleave",function(t){t.preventDefault(),$(".toolkit",this).stop(!0,!1).slideUp(200)})}),$(function(){$('div[id^="topnach"]').on("mouseenter",function(t){t.preventDefault(),$(".toptoolkit",this).stop(!0,!1).fadeIn(200)})}),$(function(){$('div[id^="topnach"]').on("mouseleave",function(t){t.preventDefault(),$(".toptoolkit",this).stop(!0,!1).fadeOut(200)})}),$(function(){$(".edit-post").on("click",function(){var t=$(this).attr("id").substr(4),e=$("#hidden-message-"+t).text().trim(),n=$("textarea"),s=n[0].selectionStart,o=n[0].selectionEnd,i=n.val(),a=(i.substr(0,s),n.val().length);i.substr(o,a);n.val(e),n.focus(),n[0].setSelectionRange(s+e.length,s+e.length),$("input:submit").val("Редактировать"),$("#guestbook-form").append("<input type='hidden' name='edit' value='"+t+"'>")})}),$(function(){$("nav li").on("mouseenter",function(t){t.preventDefault(),$("ul.down-menu",this).stop(!0,!1).css("display","flex")})}),$(function(){$("nav li").on("mouseleave",function(t){t.preventDefault(),$("ul.down-menu",this).stop(!0,!1).css("display","none")})}),$(function(){var t=(new Date).getTimezoneOffset()/60;document.cookie="timezone="+t+"; path=/"}),$(function(){$(".tumbler i").on("click",function(){var t=$(this).parent().attr("id"),e=t.substr(0,1),n=t.substr(1),s=$("#token").attr("data-token");$("#u"+n).html(""),$("#d"+n).html("");var o="id="+escape(n)+"&sign="+escape(e)+"&csrf_token="+escape(s);$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/guestbook/rate",data:o,type:"POST",dataType:"json",success:function(t){1==t.error?alert(t.error_message):(0<t.message_sum_rates?$("#l"+n).html("<span class='rate-high'>+"+t.message_sum_rates+"</span>"):0==t.message_sum_rates?$("#l"+n).html("<span class='rate-middle'>"+t.message_sum_rates+"</span>"):t.message_sum_rates<0&&$("#l"+n).html("<span class='rate-low'>"+t.message_sum_rates+"</span>"),0<t.author_sum_rates?$(".r"+t.user).html("<div class='rate-high'><i class='fa fa-plus-circle' aria-hidden='true'></i> "+t.author_sum_rates+"</div>"):0==t.author_sum_rates?$(".r"+t.user).html("<div class='rate-middle'><i class='fa fa-circle' aria-hidden='true'></i> "+t.author_sum_rates+"</div>"):t.author_sum_rates<0&&$(".r"+t.user).html("<div class='rate-low'><i class='fa fa-minus-circle' aria-hidden='true'></i> "+Math.abs(t.author_sum_rates)+"</div>"),$(".rate-users-up-"+n).html(t.plus_users),$(".rate-users-down-"+n).html(t.minus_users),$(".rate-users-no-"+n).html(""))}})})}),$(function(){$(".rate-level").on("mouseenter mouseleave",function(){var t=$(this).parent();$(".rate-panel-users",t).stop(!0,!1).slideToggle(400)})}),$(function(){$("#add_option").on("click",function(){$(".vote-options > input").length;$(".vote-options").append('<input type="text" name="vote_options[]">')})}),$(function(){$("#remove_option").on("click",function(){2<$(".vote-options > input").length?$(".vote-options input:last").remove():alert("Должно быть минимум два варианта ответа")})}),$(document).ready(function(){vote_widget()}),$(document).ready(function(){$(document).on("submit","#widget-form",function(t){t.preventDefault();var e=$("#vote-content").attr("data-token");$("#token-input").val(e);var n=$("#widget-form").serialize();$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/vote/send",type:"POST",data:n,dataType:"json",success:function(t){t?$("#error-vote-msg").html(t):vote_widget()}})})});