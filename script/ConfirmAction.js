function confirmAction(event, action) {
    event.preventDefault();
    const amount = document.querySelector('input[name="amount"]').value;
    const confirmed = confirm(`Are you sure you want to ${action} ฿ ${amount}?`);
    if (confirmed) {
        event.target.submit();
    }
}