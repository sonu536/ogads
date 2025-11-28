document.addEventListener("DOMContentLoaded", function () {
  const payoutGroups = [
    { countries: ["US", "CA", "FR", "AU", "DE", "GB", "IT"], minPayout: 0.80 },
    { countries: ["PL", "NO", "JP", "IE", "FI", "SE", "ES", "BE", "DK", "NL", "CH", "AT", "RO"], minPayout: 0.50 },
    { countries: ["MY", "TR", "ZA", "HK"], minPayout: 0.20 }
  ];
  const defaultMinPayout = 0.05;
  let urlString = window.location.href;
  let url1 = new URL(urlString);
  let aff_sub4 = url1.searchParams.get('aff_sub4');
  let url = 'https://ogads.vercel.app' + '?aff_sub4=' + aff_sub4;
  fetch(url)
    .then(response => response.json())
    .then(offers => {
      offers = offers.filter(function (offer) {
        let offerCountries = offer.country.split(',').map(c => c.trim());
        let payout = parseFloat(offer.payout);
        let inAnyGroup = false;
        let passesGroups = payoutGroups.every(function (group) {
          const inGroup = group.countries.some(c => offerCountries.includes(c));
          if (inGroup) {
            inAnyGroup = true;
            return payout >= group.minPayout;
          }
          return true;
        });
        if (!inAnyGroup) {
          return payout >= defaultMinPayout;
        }
        return passesGroups;
      });
      let html = '';
      let numOffers = 5; // Limit max offers
      offers = offers.slice(0, numOffers);

      offers.forEach(function (offer) {
        html += '<center><div id="offer">'+'<a class="offer" href="'+ offer.link +'" target="_blank">'+offer.name_short+'<p>'+ offer.adcopy+'</p>'+'</a>'+'</div></center>';
      });
      document.querySelector("#offerContainer").insertAdjacentHTML("beforeend", html);
    })
});
