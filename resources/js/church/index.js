const searchInput = document.getElementById('searchInput');
const clusterFilter = document.getElementById('clusterFilter');

function filterChurches() {

    const searchValue = searchInput.value.toLowerCase();
    const clusterValue = clusterFilter.value;

    const churches = document.querySelectorAll('.church-item');

    churches.forEach(church => {

        const churchName = church.dataset.name;
        const churchCluster = church.dataset.cluster;

        const matchSearch =
            churchName.includes(searchValue);

        const matchCluster =
            clusterValue === '' ||
            churchCluster === clusterValue;

        if (matchSearch && matchCluster) {

            church.style.display = 'block';

        } else {

            church.style.display = 'none';

        }

    });

}


// LIVE SEARCH
if (searchInput) {

    searchInput.addEventListener('keyup', filterChurches);

}


// DROPDOWN FILTER
if (clusterFilter) {

    clusterFilter.addEventListener('change', filterChurches);

}