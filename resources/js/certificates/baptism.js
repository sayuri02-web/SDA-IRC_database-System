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

// Resolve route with fuzzy matching (handles exact match, then partial keyword match)
function resolveRoute(routeMap, certType) {
    if (!certType) return Object.values(routeMap)[0];

    // Exact match first
    if (routeMap[certType]) return routeMap[certType];

    // Case-insensitive exact match
    let lowerType = certType.toLowerCase();
    for (let key in routeMap) {
        if (key.toLowerCase() === lowerType) return routeMap[key];
    }

    // Partial keyword match (e.g. "Counseling" matches "Counseling Certificate")
    for (let key in routeMap) {
        if (key.toLowerCase().includes(lowerType) || lowerType.includes(key.toLowerCase().replace(' certificate', ''))) {
            return routeMap[key];
        }
    }

    // Final fallback: first route in the map
    return Object.values(routeMap)[0];
}

// Redirect to correct certificate form based on type
$(document).on('click', '.select-member', function(){
    let memberId = $(this).data('id');

    let memberRoute = resolveRoute(window.certMemberRoutes, window.currentCertType);

    window.location.href = memberRoute + memberId;
});
