<h2>Edit Book</h2>

<form method="post" action="index.php?c=books&a=update">
    <input type="hidden" name="id" value="<?= $book['id'] ?>">
    Title <input name="title" value="<?= $book['title'] ?>"><br>
    Author <input name="author" value="<?= $book['author'] ?>"><br>
    Price <input name="price" value="<?= $book['price'] ?>"><br>
    Qty <input name="qty" value="<?= $book['qty'] ?>"><br>
    <button>Update</button>
</form>
