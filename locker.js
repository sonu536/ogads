$(function () {
  // Find the script tag that loaded locker.js
  const scripts = document.getElementsByTagName("script");
  let thisScript = null;

  for (let s of scripts) {
    if (s.src.includes("locker.js")) {
      thisScript = s;
      break;
    }
  }

  let aff_sub4 = null;
  if (thisScript) {
    const urlObj = new URL(thisScript.src);
    aff_sub4 = urlObj.searchParams.get("aff_sub4");
  }

  // Fallback: also check page URL if not found
  if (!aff_sub4) {
    const pageUrl = new URL(window.location.href);
    aff_sub4 = pageUrl.searchParams.get("aff_sub4");
  }

  // Build request URL
  const url = "https://ogads.vercel.app/?aff_sub4=123";

  $.getJSON(url, null, function (offers) {
    var html = "";
    var numOffers = 5; // Change to trim offers. Max is 10.
    offers = offers.splice(0, numOffers);
    $.each(offers, function (key, offer) {
      html +=
        '<center><div id="offer"><a class="offer" href="' +
        offer.link +
        '" target="_blank">' +
        offer.name_short +
        "<p>" +
        offer.adcopy +
        "</p></a></div></center>";
    });
    $("#offerContainer").append(html);
  });
});

