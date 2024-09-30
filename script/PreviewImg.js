function previewImage(event) {
    const fileName = event.target.files[0] ? event.target.files[0].name : 'No file chosen';
    document.querySelector('.file-name').textContent = fileName;

    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('preview');
    const file = event.target.files[0];

    if (file && file.type.match('image.*')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImage.src = e.target.result;
            imagePreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        previewImage.src = '';
        imagePreview.style.display = 'none';
    }
}
