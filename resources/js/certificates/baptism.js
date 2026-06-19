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
        let searchRoute = window.certSearchRoutes[window.currentCertType]
            || window.certSearchRoutes['Baptism Certificate'];

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

// Redirect to correct certificate form based on type
$(document).on('click', '.select-member', function(){
    let memberId = $(this).data('id');

    let memberRoute = window.certMemberRoutes[window.currentCertType]
        || window.certMemberRoutes['Baptism Certificate'];

    window.location.href = memberRoute + memberId;
});
