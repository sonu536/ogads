$(function() {
  let url_string = window.location.href;
  let url1 = new URL(url_string);
  let aff_sub4 = url1.searchParams.get('aff_sub4');
  var url = 'https://ogads.vercel.app' + '?aff_sub4=' + aff_sub4;
  $.getJSON(url, null,
    function(offers){
      var html = '';
      var numOffers=5; //Change to trim offers. Max is 10.
      offers=offers.splice(0,numOffers);
      $.each(offers, function(key, offer){
        html += '<center><div id="offer"><a class="offer" href="'+offer.link+'" target="_blank">'+offer.name_short+'<p>'+offer.adcopy+'</p></a></div></center>';
      });
      $("#offerContainer").append(html);
    });
});
