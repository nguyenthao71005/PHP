<h2>Tạo phiếu mượn</h2>

<form method="post" action="index.php?c=borrows&a=store">
    <label>Người mượn</label>
    <select name="borrower_id" required>
        <option value="">-- Chọn --</option>
        <?php foreach ($borrowers as $b): ?>
            <option value="<?= $b['id'] ?>">
                <?= htmlspecialchars($b['full_name']) ?>
            </option>
        <?php endforeach ?>
    </select>

    <label>Ngày mượn</label>
    <input type="date" name="borrow_date" required>

    <label>Ghi chú</label>
    <input type="text" name="note">

    <h3>Sách mượn</h3>

    <table>
        <tr>
            <th>Sách</th>
            <th>Tồn</th>
            <th>Số lượng mượn</th>
        </tr>

        <?php foreach ($books as $book): ?>
        <tr>
            <td><?= htmlspecialchars($book['title']) ?></td>
            <td><?= $book['qty'] ?></td>
            <td>
                <input type="number"
                       name="items[<?= $book['id'] ?>]"
                       min="0"
                       max="<?= $book['qty'] ?>"
                       value="0">
            </td>
        </tr>
        <?php endforeach ?>
    </table>

    <button type="submit">Lưu phiếu mượn</button>
</form>
