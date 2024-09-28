const successMessage = "<?php echo isset($_GET['profile-handle']) ? addslashes($_GET['profile-handle']) : ''; ?>";

if (successMessage) {
    sessionStorage.setItem('successMessage', successMessage);
}

window.onload = function () {
    const message = sessionStorage.getItem('successMessage');
    if (message) {
        document.getElementById('notification-message').innerText = message;
        document.getElementById('notification').style.display = 'block';

        setTimeout(() => {
            document.getElementById('notification').style.display = 'none';
        }, 3000);

        sessionStorage.removeItem('successMessage');
    }
};