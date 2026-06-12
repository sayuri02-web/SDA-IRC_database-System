import Cropper from 'cropperjs';
import 'cropperjs/dist/cropper.css';

document.addEventListener("DOMContentLoaded", function () {

        // RUN ONLY IN MEMBERS CREATE PAGE
        if(!document.getElementById('photoInput')) return;

        // ================= PHOTO + CROPPER =================
        const input = document.getElementById('photoInput');
        const browseBtn = document.getElementById('browseBtn');
    
        const fileName = document.getElementById('fileName');
    
        const cropImage = document.getElementById('cropImage');
        const cropBtn = document.getElementById('cropBtn');
    
        const preview = document.getElementById('preview');
    
        const croppedPhoto = document.getElementById('cropped_photo');
    
        const removeBtn = document.getElementById('remove-photo');
    
        let cropper;
    
        // OPEN FILE PICKER
        browseBtn.addEventListener('click', () => {
            input.click();
        });
    
        // WHEN IMAGE SELECTED
        input.addEventListener('change', function (e) {
    
            const file = e.target.files[0];
    
            if (!file) return;
    
            fileName.value = file.name;
    
            const reader = new FileReader();
    
            reader.onload = function (event) {
    
                cropImage.src = event.target.result;
    
                cropImage.style.display = 'block';
    
                cropBtn.style.display = 'inline-block';
    
                preview.style.display = 'none';
    
                removeBtn.style.display = 'none';
    
                // DESTROY OLD CROPPER
                if (cropper) {
                    cropper.destroy();
                }
    
                // INIT CROPPER
                cropper = new Cropper(cropImage, {
                    aspectRatio: 1,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                });
            };
    
            reader.readAsDataURL(file);
        });
    
        // CROP BUTTON
        cropBtn.addEventListener('click', function () {
    
            if (!cropper) return;
    
            const canvas = cropper.getCroppedCanvas({
                width: 300,
                height: 300,
            });
    
            const croppedData = canvas.toDataURL('image/png');
    
            // SAVE TO HIDDEN INPUT
            croppedPhoto.value = croppedData;
    
            // SHOW PREVIEW
            preview.src = croppedData;
    
            preview.style.display = 'block';
    
            removeBtn.style.display = 'block';
    
            // HIDE CROPPER
            cropImage.style.display = 'none';
    
            cropBtn.style.display = 'none';
    
            cropper.destroy();
        });
    
        // REMOVE IMAGE
        removeBtn.addEventListener('click', function () {
    
            input.value = '';
    
            fileName.value = '';
    
            preview.src = '';
    
            preview.style.display = 'none';
    
            removeBtn.style.display = 'none';
    
            cropImage.src = '';
    
            cropImage.style.display = 'none';
    
            cropBtn.style.display = 'none';
    
            croppedPhoto.value = '';
    
            if (cropper) {
                cropper.destroy();
            }
        });
    
        // ================= AGE =================
        const birthdateInput = document.getElementById('birthdate');
        const ageInput = document.getElementById('age');
    
        birthdateInput.addEventListener('change', function () {
    
            const birthdate = new Date(this.value);
            const today = new Date();
    
            let age = today.getFullYear() - birthdate.getFullYear();
            const month = today.getMonth() - birthdate.getMonth();
    
            if (month < 0 || (month === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }
    
            ageInput.value = age >= 0 ? age : '';
        });
        
        // ================= MEMBERSHIP STATUS =================

        const statusBaptized = document.getElementById('statusBaptized');
        const statusDedicated = document.getElementById('statusDedicated');
        const statusNA = document.getElementById('statusNA');

        const baptismFields = document.getElementById('baptismFields');
        const dedicationFields = document.getElementById('dedicationFields');

        function hideAllMembershipForms() {

            baptismFields.style.display = 'none';
            dedicationFields.style.display = 'none';
        }

        function toggleMembershipFields() {

            hideAllMembershipForms();

            // BAPTIZED
            if (statusBaptized.checked) {

                baptismFields.style.display = 'block';

                baptismFields.classList.add('fade-in');

            }

            // DEDICATED
            else if (statusDedicated.checked) {

                dedicationFields.style.display = 'block';

                dedicationFields.classList.add('fade-in');

            }
        }

        // EVENTS
        statusBaptized.addEventListener('change', toggleMembershipFields);

        statusDedicated.addEventListener('change', toggleMembershipFields);

        statusNA.addEventListener('change', toggleMembershipFields);

        // INITIAL
        toggleMembershipFields();

        
    }
);