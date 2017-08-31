function rotateBanners(elem) {
  var active = $(elem+" img.active");
  var next = active.parent().next().children('img');
  if (next.length == 0) 
    next = $(elem+" img:first");
  active.removeClass("active").fadeOut(500);
  next.addClass("active").fadeIn(500);
}
 
function prepareRotator(elem) {
  $(elem+" img").fadeOut(0);
  $(elem+" img:first").fadeIn(0).addClass("active");
}
 
function startRotator(elem) {
  prepareRotator(elem);
  setInterval("rotateBanners('"+elem+"')", 7000);
}