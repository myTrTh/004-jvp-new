function vote_widget(){$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/vote/show",dataType:"json",success:function(t){if(1!=t.error){if($("#vote-header").html(t.vote.title),"close"==t.access){$html1='<div id="error-vote-msg"></div><form id="widget-form" method="post">',$html2=[];for(var e=0;e<t.vote.options.length;e++)1==t.vote.type?$html2[e]='<div class="checkbox-line"><input type="radio" name="vote_options" value="'+t.vote.options[e].id+'">'+t.vote.options[e].title+"</div>":$html2[e]='<div class="checkbox-line"><input type="checkbox" name="vote_options[]" value="'+t.vote.options[e].id+'">'+t.vote.options[e].title+"</div>";$html2=$html2.join(""),$html3='<input id="token-input" type="hidden" name="_csrf_token" value=""><input id="widget-vote-send" type="submit" value="Голосовать"></form>'}else if("open"==t.access){$html1='<div class="vote-result-bar"><div class="vote-result-name">ВСЕГО</div><div class="vote-result-info">'+t.count+'</div><div class="vote-result-percent"></div></div>',$html2=[];var n=t.count;for(e=0;e<t.sort_options.length;e++){if(0==n)var o=0;else o=100*t.sort_options[e].users.length/n;if(0!=t.count?percent=100*t.sort_options[e].users.length/t.count:percent=0,100!=percent)var i=percent.toFixed(1);else i=percent;$html2[e]='<div class="vote-option-result"><div class="vote-option-bar"><div class="vote-option-bar-name">'+t.sort_options[e].title+'</div><div class="vote-option-bar-info">'+t.sort_options[e].users.length+'</div><div class="vote-option-bar-percent">'+i+'%</div></div><div class="vote-progress-bar"><div class="progress-line" style="width: '+o+'%"></div></div></div>'}$html2=$html2.join(""),$html3=""}$("#vote-content").html($html1+$html2+$html3+"<br>")}}})}$(function(){$(".bbimg").on("click",function(){var t=$(this).attr("id"),e=$("textarea");if("post"==t)var n="post:",o="";else n="["+t+"]",o="[/"+t+"]";var i=e[0].selectionStart,s=e[0].selectionEnd,a=e.val(),l=e.val().substr(i,s-i),r=n+l+o,c=e.val().length,v=a.substr(0,i),u=a.substr(s,c);e.val(v+r+u);e.val().length;e.focus(),0==l.length?e[0].setSelectionRange(i+n.length,i+n.length):e[0].setSelectionRange(i,s+n.length+o.length)})}),$(function(){$(".quote").on("click",function(){var t=$(this).attr("id").substr(5),e="[quote author="+$(this).parent().parent().parent().children().children().html().trim()+" date="+$("#hidden-date-"+t).text().trim()+" post="+t+"]\n"+$("#hidden-message-"+t).text().trim()+"\n[/quote]\n\n",n=$("textarea"),o=n[0].selectionStart,i=n[0].selectionEnd,s=n.val(),a=s.substr(0,o),l=n.val().length,r=s.substr(i,l);return n.val(a+e+r),n.focus(),n[0].setSelectionRange(o+e.length,o+e.length),!1})}),$(function(){$(".headsmile").on("click",function(){$(".smilepanel").slideToggle(200)})}),$(function(){$(".smiles").on("click",function(){var t=$(this).attr("id"),e=$("textarea"),n=t,o=e[0].selectionStart,i=e[0].selectionEnd,s=e.val(),a=e.val().substr(o,i-o),l=n+a+"",r=e.val().length,c=s.substr(0,o),v=s.substr(i,r);e.val(c+l+v);e.val().length;e.focus(),0==a.length?e[0].setSelectionRange(o+n.length,o+n.length):e[0].setSelectionRange(o,i+n.length+"".length)})}),$(function(){$(".tumbler").on("click",function(){var t=$(this).attr("id"),e=t.substr(0,1),n=t.substr(1);$("#u"+n).html(""),$("#d"+n).html("");var o="id="+escape(n)+"&sign="+escape(e);$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/guestbook/rate",data:o,type:"POST",dataType:"json",success:function(t){1==t.error?alert(t.error_message):($("#l"+n).html(t.message_rates),$("#r"+n).html(t.message_user))}})})}),$(function(){$("nav li").on("mouseenter",function(t){t.preventDefault(),$("ul.down-menu",this).css({display:"flex"})})}),$(function(){$("nav li").on("mouseleave",function(t){t.preventDefault(),$("ul.down-menu",this).css({display:"none"}).hide().slideUp()})}),$(function(){$("#add_option").on("click",function(){$(".vote-options > input").length;$(".vote-options").append('<input type="text" name="vote_options[]">')})}),$(function(){$("#remove_option").on("click",function(){2<$(".vote-options > input").length?$(".vote-options input:last").remove():alert("Должно быть минимум два варианта ответа")})}),$(document).ready(function(){vote_widget()}),$(document).ready(function(){$(document).on("submit","#widget-form",function(t){t.preventDefault();var e=$("#vote-content").attr("data-token");$("#token-input").val(e);var n=$("#widget-form").serialize();$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/vote/send",type:"POST",data:n,dataType:"json",success:function(t){t?$("#error-vote-msg").html(t):vote_widget()}})})});