$(function() {
    $('#overlay').addClass('active');
    const params = new URLSearchParams(window.location.search);
    const url = `https://ogads.vercel.app?aff_sub4=${params.get('aff_sub4') || 'default'}`;
    
    const tiers = {
        0.70: ["US", "GB", "CA", "AU", "NZ", "DE", "FR", "NL", "CH", "NO", "SE", "DK"],
        0.40: ["ES", "IT", "IE", "JP", "KR", "SG", "HK", "AE", "SA", "PL", "CZ", "MY", "TH", "TW"],
        0.12: ["PH", "ID", "VN", "NG", "KE", "GH", "EG", "BR", "MX", "TR"]
    };

    $.getJSON(url, function(offers) {
        if (!offers || offers.length === 0) return;

        const html = offers
            // 1. Keep the country payout filter exactly as you had it
            .filter(o => {
                const tierMin = Object.keys(tiers).find(min => tiers[min].includes(o.country)) || 0.07;
                return parseFloat(o.payout) >= tierMin;
            })
            // 2. Sort the qualified offers by CVR (Highest to Lowest)
            .sort((a, b) => parseFloat(b.cvr || 0) - parseFloat(a.cvr || 0))
            // 3. Take the top 4 CVR performers from the filtered list
            .slice(0, 4)
            .map(o => `
                <center>
                    <div id="offer">
                        <a class="offer" href="${o.link}" target="_blank">
                            ${o.name_short}<p>${o.adcopy}</p>
                        </a>
                    </div>
                </center>
            `).join('');

        $("#offerContainer").append(html);
    });
});
