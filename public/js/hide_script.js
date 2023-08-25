
$form = $('.container form');
$handler = $('.container h5');
$('.container h5').on('click', function() {
$form.is(':visible') ? $form.slideUp() : $form.slideDown();
})
var trainnum = ["001А","003А","049А","747А"];
$('#train').autocomplete({
  source: trainnum,
  minLength: 0,
  //source: 'stations.php'
 }).focus(function(){$(this).autocomplete("search","")});
