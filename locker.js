$(function() {
  // get the <script> tag that loaded this file
  const thisScript = document.currentScript;
  const urlObj = new URL(thisScript.src);

  // read aff_sub4 from the script's query string
  const aff_sub4 = urlObj.searchParams.get("aff_sub4");

  // now use it in your request
  var url = 'https://ogads.vercel.app' + '?aff_sub4=' + aff_sub4;

  $.getJSON(url, null, function(offers){
    var html = '';
    var numOffers = 5; //Change to trim offers. Max is 10.
    offers = offers.splice(0,numOffers);
    $.each(offers, function(key, offer){
      html += '<center><div id="offer"><a class="offer" href="'+offer.link+'" target="_blank">'+offer.name_short+'<p>'+offer.adcopy+'</p></a></div></center>';
    });
    $("#offerContainer").append(html);
  });
});
