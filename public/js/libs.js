function vote_widget(){$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/vote/show",dataType:"json",success:function(e){if(1!=e.error){if($("#vote-header").html(e.vote.title),"close"==e.access){$html1='<div id="error-vote-msg"></div><form id="widget-form" method="post">',$html2=[];for(var t=0;t<e.vote.options.length;t++)1==e.vote.type?$html2[t]='<div class="checkbox-line"><input type="radio" name="vote_options" value="'+e.vote.options[t].id+'">'+e.vote.options[t].title+"</div>":$html2[t]='<div class="checkbox-line"><input type="checkbox" name="vote_options[]" value="'+e.vote.options[t].id+'">'+e.vote.options[t].title+"</div>";$html2=$html2.join(""),$html3='<input id="token-input" type="hidden" name="_csrf_token" value=""><input id="widget-vote-send" type="submit" value="Голосовать"></form>'}else if("open"==e.access){$html1='<div class="vote-result-bar"><div class="vote-result-name">ВСЕГО</div><div class="vote-result-info">'+e.count+'</div><div class="vote-result-percent"></div></div>',$html2=[];var s=e.count;for(t=0;t<e.sort_options.length;t++){if(0==s)var n=0;else n=100*e.sort_options[t].users.length/s;if(0!=e.count?percent=100*e.sort_options[t].users.length/e.count:percent=0,100!=percent)var a=percent.toFixed(1);else a=percent;$html2[t]='<div class="vote-option-result"><div class="vote-option-bar"><div class="vote-option-bar-name">'+e.sort_options[t].title+'</div><div class="vote-option-bar-info">'+e.sort_options[t].users.length+'</div><div class="vote-option-bar-percent">'+a+'%</div></div><div class="vote-progress-bar"><div class="progress-line" style="width: '+n+'%"></div></div></div>'}$html2=$html2.join(""),$html3=""}$("#vote-content").html($html1+$html2+$html3+"<br>")}}})}$(function(){$(".admin-panel-users").on("click",function(){$(this).next().slideToggle(300)})}),$(function(){$('[id ^= "set-permissions"]').on("click",function(){var t=$(this).attr("id").substr(16),e=$("#form-per-"+t+' input[name="_csrf_token"]').val(),s=[];$("#form-per-"+t+" input:checkbox:checked").each(function(){s.push($(this).val())});var n="user="+escape(t)+"&permissions="+escape(s)+"&_csrf_token="+escape(e);$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/admin/permissions",data:n,type:"POST",dataType:"json",success:function(e){1==e.success?$(".success-pfield-"+t).html(e.message):$(".error-pfield-"+t).html(e)}})})}),$(function(){$('[id ^= "set-roles"]').on("click",function(){var t=$(this).attr("id").substr(10),e=$("#form-rol-"+t+' input[name="_csrf_token"]').val(),s=[];$("#form-rol-"+t+" input:checkbox:checked").each(function(){s.push($(this).val())}),console.log(s);var n="user="+escape(t)+"&roles="+escape(s)+"&_csrf_token="+escape(e);$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/admin/roles",data:n,type:"POST",dataType:"json",success:function(e){1==e.success?$(".success-rfield-"+t).html(e.message):$(".error-rfield-"+t).html(e)}})})}),$(function(){$(".bbimg").on("click",function(){var e=$(this).attr("id"),t=$("textarea");if("post"==e)var s="post:",n="";else s="["+e+"]",n="[/"+e+"]";var a=t[0].selectionStart,o=t[0].selectionEnd,i=t.val(),r=t.val().substr(a,o-a),l=s+r+n,c=t.val().length,u=i.substr(0,a),d=i.substr(o,c);t.val(u+l+d);t.val().length;t.focus(),0==r.length?t[0].setSelectionRange(a+s.length,a+s.length):t[0].setSelectionRange(a,o+s.length+n.length)})}),$(function(){$(".quote").on("click",function(){var e=$(this).attr("id").substr(5),t="[quote author="+$(this).parent().parent().parent().children().children().html().trim()+" date="+$("#hidden-date-"+e).text().trim()+" post="+e+"]\n"+$("#hidden-message-"+e).text().trim()+"\n[/quote]\n\n",s=$("textarea"),n=s[0].selectionStart,a=s[0].selectionEnd,o=s.val(),i=o.substr(0,n),r=s.val().length,l=o.substr(a,r);return s.val(i+t+l),s.focus(),s[0].setSelectionRange(n+t.length,n+t.length),!1})}),$(function(){$(".headsmile").on("click",function(){$(".smilepanel").slideToggle(200)})}),$(function(){$(".smiles").on("click",function(){var e=$(this).attr("id"),t=$("textarea"),s=e,n=t[0].selectionStart,a=t[0].selectionEnd,o=t.val(),i=t.val().substr(n,a-n),r=s+i+"",l=t.val().length,c=o.substr(0,n),u=o.substr(a,l);t.val(c+r+u);t.val().length;t.focus(),0==i.length?t[0].setSelectionRange(n+s.length,n+s.length):t[0].setSelectionRange(n,a+s.length+"".length)})}),$(function(){$("nav li").on("mouseenter",function(e){e.preventDefault(),$("ul.down-menu",this).css({display:"flex"})})}),$(function(){$("nav li").on("mouseleave",function(e){e.preventDefault(),$("ul.down-menu",this).css({display:"none"}).hide().slideUp()})}),$(function(){$(".tumbler i").on("click",function(){var e=$(this).parent().attr("id"),t=e.substr(0,1),s=e.substr(1),n=$("#token").attr("data-token");$("#u"+s).html(""),$("#d"+s).html("");var a="id="+escape(s)+"&sign="+escape(t)+"&csrf_token="+escape(n);$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/guestbook/rate",data:a,type:"POST",dataType:"json",success:function(e){1==e.error?alert(e.error_message):(0<e.message_sum_rates?$("#l"+s).html("<span class='rate-high'>+"+e.message_sum_rates+"</span>"):0==e.message_sum_rates?$("#l"+s).html("<span class='rate-middle'>"+e.message_sum_rates+"</span>"):e.message_sum_rates<0&&$("#l"+s).html("<span class='rate-low'>"+e.message_sum_rates+"</span>"),0<e.author_sum_rates?$(".r"+e.user).html("<div class='rate-high'><i class='fa fa-plus-circle' aria-hidden='true'></i> "+e.author_sum_rates+"</div>"):0==e.author_sum_rates?$(".r"+e.user).html("<div class='rate-middle'><i class='fa fa-circle' aria-hidden='true'></i> "+e.author_sum_rates+"</div>"):e.author_sum_rates<0&&$(".r"+e.user).html("<div class='rate-low'><i class='fa fa-minus-circle' aria-hidden='true'></i> "+Math.abs(e.author_sum_rates)+"</div>"),$(".rate-users-up-"+s).html(e.plus_users),$(".rate-users-down-"+s).html(e.minus_users),$(".rate-users-no-"+s).html(""))}})})}),$(function(){$(".rate-level").on("mouseenter mouseleave",function(){var e=$(this).parent();$(".rate-panel-users",e).stop(!0,!1).slideToggle(400)})}),$(function(){$("#add_option").on("click",function(){$(".vote-options > input").length;$(".vote-options").append('<input type="text" name="vote_options[]">')})}),$(function(){$("#remove_option").on("click",function(){2<$(".vote-options > input").length?$(".vote-options input:last").remove():alert("Должно быть минимум два варианта ответа")})}),$(document).ready(function(){vote_widget()}),$(document).ready(function(){$(document).on("submit","#widget-form",function(e){e.preventDefault();var t=$("#vote-content").attr("data-token");$("#token-input").val(t);var s=$("#widget-form").serialize();$.ajaxSetup({cache:!1}),$.ajax({url:"/ajax/vote/send",type:"POST",data:s,dataType:"json",success:function(e){e?$("#error-vote-msg").html(e):vote_widget()}})})});