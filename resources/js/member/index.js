const searchInput = document.getElementById('searchInput');

function filterMembers() {

    const searchValue =
        searchInput.value.toLowerCase();

    const members =
        document.querySelectorAll('.member-item');

    members.forEach(member => {

        const memberName =
            member.dataset.name;

        if (memberName.includes(searchValue)) {

            member.style.display = '';

        } else {

            member.style.display = 'none';

        }

    });

}


// LIVE SEARCH
if (searchInput) {

    searchInput.addEventListener('keyup', filterMembers);

}