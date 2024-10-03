function loadContent(url) {
    fetch(url)
        .then(response => {
            if (!response.ok)
                throw new Error('Network response was not ok');
            return response.text();
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
}

document.querySelectorAll('.btn').forEach(button => {
    button.addEventListener('click', function () {
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