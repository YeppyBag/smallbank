const dialog = document.getElementById('iframe-dialog');
const iframe = document.getElementById('iframe-content');

function Iframe(select) {
    console.log("Iframe function called with URL: " + select); // Debugging
    iframe.src = select;
    dialog.showModal();
}

function closeIframe() {
    console.log("Close iframe called"); // Debugging
    dialog.close();
}