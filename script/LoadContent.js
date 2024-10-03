function loadContent(url) {
    fetch(url)
        .then(response => {
            if (!response.ok)
                throw new Error('Network response was not ok');
            return response.text();
        })
        .then(data => {
            document.querySelector('.main-content').innerHTML = data;
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
function animateNumber(from, to, duration) {
    const startTime = performance.now();
    function update() {
        const currentTime = performance.now();
        const elapsed = currentTime - startTime;

        const progress = Math.min(elapsed / duration, 1);
        const currentValue = Math.floor(from + (to - from) * progress);

        const formattedValue = currentValue.toLocaleString(undefined, {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        document.getElementById('wallet-balance').innerHTML ='฿' + formattedValue;
        if (progress < 1) {
            requestAnimationFrame(update);
        }
    }

    requestAnimationFrame(update);
}

function updateWalletBalance() {
    fetch('utility/get_balance.php')
        .then(response => response.json())
        .then(data => {
            const newBalance = Number(data.balance);
            const currentBalanceElement = document.getElementById('wallet-balance');
            const currentBalance = parseFloat(currentBalanceElement.innerText.replace(/[^0-9.-]+/g, ""));
            animateNumber(currentBalance, newBalance, 1000);
        })
        .catch(error => console.error('Error fetching balance:', error));
}

setInterval(updateWalletBalance, 5000);