function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('preview');
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
        previewImg.src = e.target.result;
        imagePreview.style.display = 'block'; // Show the preview
    }

    if (file) {
        reader.readAsDataURL(file); // Read the file as Data URL
    }
}