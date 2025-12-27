<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    let url_string = window.location.href;
    let url1 = new URL(url_string);
    let aff_sub4 = url1.searchParams.get('aff_sub4');
    var url = 'https://ogads.vercel.app' + '?aff_sub4=' + (aff_sub4 || '');
    const tierSettings = {
        tier1: { countries: ["US", "CA", "FR", "AU", "DE", "GB", "NL", "IT"], min: 0.45 },
        tier2: { countries: ["MY", "TR", "ZA", "PL", "NO", "JP", "HK", "MY"], min: 0.20 },
        tier3: { countries: ["BR", "MX", "ES", "PT"], min: 0.10 }
    };
    const defaultMin = 0.05;
    const numOffers = 3; 
    // Use Fetch API instead of $.getJSON
    fetch(url)
        .then(response => response.json())
        .then(offers => {
            let html = ''; 
            // 1. Filter the offers based on payout logic
            let filteredOffers = offers.filter(offer => {
                let country = offer.country;
                let payout = parseFloat(offer.payout);
                let requiredPayout = defaultMin;
                for (const tier in tierSettings) {
                    if (tierSettings[tier].countries.includes(country)) {
                        requiredPayout = tierSettings[tier].min;
                        break; 
                    }
                }
                return payout >= requiredPayout;
            });
            // 2. Limit the number of offers
            filteredOffers = filteredOffers.slice(0, numOffers);
            // 3. Build the HTML string
            filteredOffers.forEach(offer => {
                html += `<center><div id="offer"><a class="offer" href="${offer.link}" target="_blank">${offer.name_short}<p>${offer.adcopy}</p></a></div></center>`;});
            // 4. Append to the container using standard JS
            const container = document.getElementById("offerContainer");
            if (container) {
                container.innerHTML += html;
            }
        })
        .catch(error => console.error('Error fetching offers:', error));
});
</script>
