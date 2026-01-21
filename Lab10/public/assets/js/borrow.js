document.addEventListener('submit', function (e) {
    if (!e.target.closest('form')) return;

    let total = 0;
    document.querySelectorAll('input[type=number]').forEach(i => {
        total += parseInt(i.value || 0);
    });

    if (total === 0) {
        alert('Phải chọn ít nhất 1 quyển sách');
        e.preventDefault();
    }
});
