<script type="text/javascript">
document.addEventListener("DOMContentLoaded", function() {
    let url_string = window.location.href;
    let url1 = new URL(url_string);
    let aff_sub4 = url1.searchParams.get('aff_sub4');
    var url = 'https://ogads.vercel.app' + '?aff_sub4=' + (aff_sub4 || '');
    const tierSettings = {
        tier1: { countries: ["US", "GB", "CA", "AU", "NZ", "DE", "FR", "NL", "CH", "NO", "SE", "DK"], min: 0.70 },
        tier2: { countries: ["ES", "IT", "IE", "JP", "KR", "SG", "HK", "AE", "SA", "PL", "CZ", "MY", "TH", "TW"], min: 0.40 },
        tier3: { countries: ["PH", "ID", "VN", "NG", "KE", "GH", "EG", "BR", "MX"], min: 0.15 }
    };
    const defaultMin = 0.08;
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
