(function () {
  function initLocker() {
    // get aff_sub4 either from page URL or from script src
    let aff_sub4 = null;

    // 1. from page URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("aff_sub4")) {
      aff_sub4 = urlParams.get("aff_sub4");
    }

    // 2. from the script tag src (e.g., locker.js?aff_sub4=123)
    if (!aff_sub4) {
      const scripts = document.getElementsByTagName("script");
      for (let s of scripts) {
        if (s.src.includes("locker.js") && s.src.includes("aff_sub4=")) {
          const srcParams = new URL(s.src).searchParams;
          aff_sub4 = srcParams.get("aff_sub4");
          break;
        }
      }
    }

    // build endpoint with aff_sub4
    const endpoint = `https://ogads.vercel.app?aff_sub4=${encodeURIComponent(aff_sub4 || "")}`;
    console.log("Fetching offers from:", endpoint);

    fetch(endpoint)
      .then((res) => {
        if (!res.ok) throw new Error("Network response was not ok");
        return res.json();
      })
      .then((offers) => {
        let html = "";
        const numOffers = 5; // max 10
        if (Array.isArray(offers)) {
          offers.slice(0, numOffers).forEach((offer) => {
            html += `
              <a href="${offer.url}" target="_blank" class="offer">
                <p>${offer.title}</p>
                <p>${offer.desc}</p>
              </a>
            `;
          });
        }
        const container = document.querySelector("#offerContainer");
        if (container) {
          container.innerHTML = html;
        } else {
          console.warn("Offer container not found to insert offers!");
        }
      })
      .catch((error) => console.error("Failed to fetch or render offers:", error));
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initLocker);
  } else {
    initLocker();
  }
})();
