$(function() {
    // 1. TRIGGER IMMEDIATELY: Drop the overlay so the user sees the instructions
    $('#overlay').addClass('active');

    // 2. Define variables
    const params = new URLSearchParams(window.location.search);
    const affSub4 = params.get('aff_sub4') || 'default';
    const url = `https://ogads.vercel.app?aff_sub4=${affSub4}`;

    const tiers = {
        0.70: ["US", "GB", "CA", "AU", "NZ", "DE", "FR", "NL", "CH", "NO", "SE", "DK"],
        0.40: ["ES", "IT", "IE", "JP", "KR", "SG", "HK", "AE", "SA", "PL", "CZ", "MY", "TH", "TW"],
        0.12: ["PH", "ID", "VN", "NG", "KE", "GH", "EG", "BR", "MX", "TR"],
        0.01: ["IN", "PK"]
    };

    // 3. Fetch the offers with Error Handling
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(offers) {
            console.log("Offers received:", offers); // Check your console (F12) to see this

            if (!offers || offers.length === 0) {
                $("#offerContainer").html("<p style='color:red;'>No offers available for your region.</p>");
                return;
            }

            const html = offers.filter(o => {
                const tierMin = Object.keys(tiers).find(min => tiers[min].includes(o.country)) || 0.07;
                return parseFloat(o.payout) >= tierMin;
            }).slice(0, 4).map(o => `
                <center>
                    <div class="offer-item">
                        <a class="offer" href="${o.link}" target="_blank">
                            ${o.name_short}<p>${o.adcopy}</p>
                        </a>
                    </div>
                </center>
            `).join('');

            // Clear loading text and inject offers
            $("#offerContainer").html(html);
        },
        error: function(xhr, status, error) {
            console.error("API Error:", error);
            $("#offerContainer").html("<p style='color:red;'>Failed to load offers. Please refresh the page.</p>");
        }
    });
});
