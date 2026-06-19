document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('witness-container');
    const addBtn = document.getElementById('addWitness');

    addBtn.addEventListener('click', function () {

        const row = document.createElement('div');

        row.className = 'input-group mb-2';

        row.innerHTML = `
            <input
                type="text"
                name="witnesses[]"
                class="form-control"
                placeholder="Enter witness name">

            <button
                type="button"
                class="btn btn-outline-danger remove-witness">
                Remove
            </button>
        `;

        container.appendChild(row);
    });

    document.addEventListener('click', function(e){

        if(e.target.classList.contains('remove-witness')){

            e.target.closest('.input-group').remove();

        }

    });

});