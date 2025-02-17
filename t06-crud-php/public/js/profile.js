function previewImage(event) {
    const file = event.target.files[0];
    const reader = new FileReader();
    
    reader.onload = function(e) {
        const preview = document.getElementById('photo-preview');
        const selectedPhoto = document.getElementById('selected-photo');
        const labelPhoto = document.getElementById('label-photo');
        const iconPhoto = document.getElementById('icon-photo');

        preview.style.display = 'block'; 
        selectedPhoto.src = e.target.result;

        labelPhoto.style.display = 'none';

        const btnUpload = document.getElementById('btn-upload');
        btnUpload.disabled = false;  
    };

    reader.readAsDataURL(file);
}
