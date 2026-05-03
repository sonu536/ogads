$(function() {
    const params = new URLSearchParams(window.location.search);
    const url = `https://ogads.vercel.app?aff_sub4=${params.get('aff_sub4')}`;
    const tiers = {
        0.50: ["US", "GB", "CA", "AU", "NZ", "DE", "FR", "NL", "CH", "NO", "SE", "DK"],
        0.30: ["ES", "IT", "IE", "JP", "KR", "SG", "HK", "AE", "SA", "PL", "CZ", "MY", "TH", "TW"],
        0.10: ["PH", "ID", "VN", "NG", "KE", "GH", "EG", "BR", "MX", "TR"]
    };
    $.getJSON(url, function(offers) {
        const html = offers.filter(o => {
            // Find the tier min by checking if the country exists in any tier array
            const tierMin = Object.keys(tiers).find(min => tiers[min].includes(o.country)) || 0.07;
            return parseFloat(o.payout) >= tierMin;
        }).slice(0, 4).map(o => `
            <center><div id="offer">
                <a class="offer" href="${o.link}" target="_blank">
                    ${o.name_short}<p>${o.adcopy}</p>
                </a>
            </div></center>
        `).join('');

        $("#offerContainer").append(html);
    });
});
