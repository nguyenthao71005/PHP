<h2>Danh sách phiếu mượn</h2>

<a href="index.php?c=borrows&a=create" class="btn-secondary">+ Tạo phiếu mượn</a>

<table>
    <tr>
        <th>ID</th>
        <th>Người mượn</th>
        <th>Ngày mượn</th>
        <th>Ghi chú</th>
        <th>Chi tiết</th>
    </tr>

    <?php foreach ($borrows as $b): ?>
    <tr>
        <td><?= $b['id'] ?></td>
        <td><?= htmlspecialchars($b['full_name']) ?></td>
        <td><?= $b['borrow_date'] ?></td>
        <td><?= htmlspecialchars($b['note']) ?></td>
        <td>
            <a href="index.php?c=borrows&a=show&id=<?= $b['id'] ?>">
                Xem
            </a>
        </td>
    </tr>
    <?php endforeach ?>
</table>
