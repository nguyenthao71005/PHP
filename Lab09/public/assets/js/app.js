$(document).ready(function () {

    // TEST JS LOAD
    alert('JS RUNNING');

    // Load danh sách khi mở trang
    loadStudents();

    // ADD sinh viên
    $('#student-form').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: 'index.php?c=student&a=api&action=create',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if (res.success) {
                    loadStudents();
                    $('#student-form')[0].reset();
                } else {
                    alert('Thêm thất bại');
                }
            },
            error: function () {
                alert('Lỗi Ajax');
            }
        });
    });

    // DELETE sinh viên
    $(document).on('click', '.del', function () {
        if (!confirm('Xóa sinh viên này?')) return;

        let id = $(this).data('id');

        $.ajax({
            url: 'index.php?c=student&a=api&action=delete',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function () {
                loadStudents();
            }
        });
    });

});

// LOAD danh sách sinh viên
function loadStudents() {
    $.getJSON(
        'index.php?c=student&a=api&action=list',
        function (res) {
            let html = '';

            if (res.data.length === 0) {
                html = '<tr><td colspan="6">Chưa có dữ liệu</td></tr>';
            } else {
                $.each(res.data, function (i, s) {
                    html += `
                        <tr>
                            <td>${i + 1}</td>
                            <td>${s.code}</td>
                            <td>${s.full_name}</td>
                            <td>${s.email}</td>
                            <td>${s.dob ?? ''}</td>
                            <td>
                                <button class="del" data-id="${s.id}">Xóa</button>
                            </td>
                        </tr>
                    `;
                });
            }

            $('#student-list').html(html);
        }
    );
}
