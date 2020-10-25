document.addEventListener('click',(ev) => {
    if (ev.target.classList.contains('id-check')) return;
    if (ev.target.classList.contains('id-check-td')) return;
    let that = ev.target.closest('.link-row');
    if (!that) return;
    window.location = that.dataset.href;
});

if (document.querySelector('.all-ids-check'))
    document.querySelector('.all-ids-check').onclick = (ev) => {
        document.querySelectorAll('.id-check').forEach((el) => {
            el.checked = ev.target.checked;
        });
    }
if (document.querySelector('.telegram-table')) {
    let dataTable = new DataTable('.telegram-table', {});
}