function loadContent(url, containerSelector, isDialog = false) {
    fetch(url)
        .then(response => {
            if (!response.ok)
                throw new Error('Network response was not ok');
            return response.text();
        })
        .then(data => {
            if (isDialog) {
                document.querySelector('.floatator-content').innerHTML = data;
                document.querySelector('.floatator').style.display = 'flex';
                setTimeout(() => {
                    document.querySelector('.floatator').classList.add('show');
                }, 10);
            } else {
                document.querySelector('.main-content').innerHTML = data;
            }
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}

document.querySelectorAll('.btn').forEach(button => {
    button.addEventListener('click', function () {
        this.innerHTML = 'รอซักครู่';
        this.disabled = true;
        window.location.href = this.getAttribute('data-url');
    });
});

const pointTransactionLink = document.getElementById('point-transaction-link');
if (pointTransactionLink) {
    pointTransactionLink.addEventListener('click', function (event) {
        event.preventDefault();
        const url = this.getAttribute('data-url');
        loadContent(url, '.main-content');
    });
}

const loginLink = document.getElementById('login-link');
if (loginLink) {
    loginLink.addEventListener('click', function (event) {
        event.preventDefault();
        const url = this.getAttribute('data-url');
        loadContent(url, '.floatator-content', true);
    });
}

const closeDialog = document.getElementById('close-dialog');
if (closeDialog) {
    closeDialog.addEventListener('click', function() {
        document.querySelector('.floatator').style.display = 'none';
    });
}