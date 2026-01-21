<h2>Chi tiết phiếu mượn #<?= $borrow['id'] ?></h2>

<p><b>Người mượn:</b> <?= htmlspecialchars($borrow['full_name']) ?></p>
<p><b>Ngày mượn:</b> <?= $borrow['borrow_date'] ?></p>
<p><b>Ghi chú:</b> <?= htmlspecialchars($borrow['note']) ?></p>

<table>
    <tr>
        <th>Sách</th>
        <th>Số lượng</th>
    </tr>

    <?php foreach ($items as $i): ?>
    <tr>
        <td><?= htmlspecialchars($i['title']) ?></td>
        <td><?= $i['qty'] ?></td>
    </tr>
    <?php endforeach ?>
</table>
