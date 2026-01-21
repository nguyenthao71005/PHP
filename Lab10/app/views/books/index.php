<h2>Danh sách sách</h2>

<form method="get" class="search-box">
    <input type="hidden" name="c" value="books">
    <input type="hidden" name="a" value="index">

    <input type="text" name="kw" placeholder="Tìm theo tên / tác giả"
           value="<?= htmlspecialchars($kw ?? '') ?>">

    <button type="submit">Tìm</button>
    <a href="index.php?c=books&a=create" class="btn-secondary">+ Thêm</a>
</form>

<table>
    <tr>
        <th><a href="?c=books&a=index&sort=title&dir=<?= $dir==='ASC'?'desc':'asc' ?>">Tên sách</a></th>
        <th>Tác giả</th>
        <th><a href="?c=books&a=index&sort=price&dir=<?= $dir==='ASC'?'desc':'asc' ?>">Giá</a></th>
        <th><a href="?c=books&a=index&sort=qty&dir=<?= $dir==='ASC'?'desc':'asc' ?>">Tồn</a></th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($books as $b): ?>
    <tr>
        <td><?= htmlspecialchars($b['title']) ?></td>
        <td><?= htmlspecialchars($b['author']) ?></td>
        <td><?= number_format($b['price']) ?></td>
        <td><?= $b['qty'] ?></td>
        <td class="actions">
            <a href="index.php?c=books&a=edit&id=<?= $b['id'] ?>">Sửa</a>

            <form method="post"
                  action="index.php?c=books&a=delete"
                  style="display:inline"
                  onsubmit="return confirm('Xóa sách này?')">
                <input type="hidden" name="id" value="<?= $b['id'] ?>">
                <button>Xóa</button>
            </form>
        </td>
    </tr>
    <?php endforeach ?>
</table>
