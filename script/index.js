const dialog = document.getElementById('iframe-dialog')
function Iframe(select) {
    dialog.src = select
    dialog.showModal()
}
function closeIframe() {
    dialog.close()
}