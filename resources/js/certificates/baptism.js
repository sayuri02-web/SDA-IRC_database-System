$(document).ready(function(){

    // Capture which certificate type was clicked
    $(document).on('click', '.print-btn', function(){
        window.currentCertType = $(this).data('certificate');
    });

    $('#memberSearch').on('keyup', function(){

        let search = $(this).val();

        if(search.length < 1){
            $('#searchResults').html('');
            return;
        }

        // Determine search route based on certificate type
        let searchRoute = resolveRoute(window.certSearchRoutes, window.currentCertType);

        $.ajax({
            url: searchRoute,
            type: "GET",
            data: { search: search },

            success: function(response){

                let html = '';

                if(response.length === 0){
                    html = `<div class="text-center text-muted p-3">No members found</div>`;
                } else {
                    response.forEach(member => {
                        html += `
                            <a href="javascript:void(0)"
                                class="text-decoration-none select-member"
                                data-id="${member.id}">
                                <div class="border rounded p-3 mb-2 search-member-card d-flex align-items-center">
                                    <div class="mr-3">
                                        <img src="${member.photo ? '/uploads/' + member.photo : '/assets/images/default.png'}"
                                            style="width:55px; height:55px; border-radius:50%; object-fit:cover; border:2px solid #dff5e4;">
                                    </div>
                                    <div>
                                        <strong class="text-dark">
                                            ${member.first_name} ${member.middle_initial ?? ''} ${member.last_name}
                                        </strong><br>
                                        <small class="text-muted">${member.church_name ?? 'No Church'}</small>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                }

                $('#searchResults').html(html);
            },

            error: function(error){
                console.log(error);
            }
        });
    });

});

// Resolve route with matching (handles exact match, then best partial match)
function resolveRoute(routeMap, certType) {
    if (!certType) return Object.values(routeMap)[0];

    // Exact match first
    if (routeMap[certType]) return routeMap[certType];

    // Case-insensitive exact match
    let lowerType = certType.toLowerCase().trim();
    for (let key in routeMap) {
        if (key.toLowerCase() === lowerType) return routeMap[key];
    }

    // Best partial match — find the key with the longest overlap
    // Score each key by how many words match
    let bestMatch = null;
    let bestScore = 0;

    let typeWords = lowerType.replace(/certificate/gi, '').trim().split(/\s+/);

    for (let key in routeMap) {
        let keyWords = key.toLowerCase().replace(/certificate/gi, '').trim().split(/\s+/);

        // Count matching words
        let score = 0;
        for (let word of typeWords) {
            if (word.length < 3) continue; // skip short words
            for (let kw of keyWords) {
                if (kw === word || kw.includes(word) || word.includes(kw)) {
                    score++;
                    break;
                }
            }
        }

        // Penalize if key has words NOT in type (prevents "membership" matching "members affiliate")
        for (let kw of keyWords) {
            if (kw.length < 3) continue;
            let found = typeWords.some(tw => tw === kw || tw.includes(kw) || kw.includes(tw));
            if (!found) score -= 0.5;
        }

        if (score > bestScore) {
            bestScore = score;
            bestMatch = routeMap[key];
        }
    }

    if (bestMatch && bestScore > 0) return bestMatch;

    // Final fallback: first route in the map
    return Object.values(routeMap)[0];
}

// Redirect to correct certificate form based on type
$(document).on('click', '.select-member', function(){
    let memberId = $(this).data('id');

    let memberRoute = resolveRoute(window.certMemberRoutes, window.currentCertType);

    window.location.href = memberRoute + memberId;
});
