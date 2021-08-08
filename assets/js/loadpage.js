$('body').on("click", 'a', function(loadpage) { 
$(".effect").show("slow"); 
$(".left_load").show("slow"); 
$(".left").hide("slow"); loadpage.preventDefault(); 
url = $(this).attr('href'); BlogPage(url, false); });
$(".effect").hide("slow"); 
$(".left_load").hide("slow"); 
var state = {name: location.href, page: document.title}; 
window.history.pushState(state, document.title, location.href); 
$(window).on("popstate", function(){ if(history.state){ 
BlogPage(history.state.name, true); 
} }); 
function BlogPage(link,pop){ $("BlogPage").append('
'); 
$.get(link,"", function(data_html){ 
var title = data_html.split('')[0]; 
var body = data_html.split('')[1].split('')[0]; 
$("title").text(title); 
$("BlogPage").html(body); 
$(".effect").hide("slow"); 
$(".left_load").hide("slow"); 
$(".right").show("slow"); 
$(".left").show("slow"); 
if(pop != true){ 
var state = {name: link, page: title}; 
window.history.pushState(state, title, link+'#me'); 
} 
$('html,body').animate({scrollTop:0},200); }); } 
$(".left").show("slow"); 
$(".effect").hide("slow"); 
$(".left_load").hide("slow");
