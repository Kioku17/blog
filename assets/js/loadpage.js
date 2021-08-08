$('body').on("click", 'a', function(loadpage) {
$(".loadPage").show("slow"); 
$(".displayPage").hide("slow");
loadpage.preventDefault();
var url = $(this).attr('href');
BlogPage(url, false);
});

var state = {
name: location.href,
page: document.title
};
history.pushState(state, document.title, location.href);
$(window).on("popstate", function(){
if(history.state){
BlogPage(history.state.name, true);}});
var BlogPage = function(link,pop = false){
$.fn.url = link;
var request = {
type: 'GET',
dataType: 'html',
url: link
};
$.ajax(request).done(function(data){
var title = data.split('<title>')[1].split('</title>')[0];
var body = data.split('<BlogPage>')[1].split('</BlogPage>')[0];
if(pop != true){
var state = {
name: link,
page: ''
};
history.pushState(state, null, state.name);
}
$("title").text(title);
$("BlogPage").html(body);
$('html,body').animate({scrollTop:0},200);
}).fail(function(){
$("BlogPage").html(body);
})
}
