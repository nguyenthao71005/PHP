<?php
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../models/BooksRepository.php';

class BooksController extends Controller {
    private $repo;

    public function __construct() {
        $this->repo = new BooksRepository(Database::getConnection());
    }

    public function index() {
        $kw = $_GET['kw'] ?? '';
        $sort = $_GET['sort'] ?? 'created_at';
        $dir  = ($_GET['dir'] ?? 'desc') === 'asc' ? 'ASC' : 'DESC';

        $allowed = ['title','price','qty','created_at'];
        if (!in_array($sort, $allowed)) $sort = 'created_at';

        $books = $this->repo->all($kw, $sort, $dir);
        $this->view('books/index', compact('books','kw','sort','dir'));
    }

    public function create() {
        $this->view('books/create');
    }

    public function store() {
        if (empty($_POST['title']) || empty($_POST['author'])) {
            $this->redirect('c=books&a=create&error=1');
        }

        $this->repo->create($_POST);
        $this->redirect('c=books&a=index&success=1');
    }

    public function edit() {
        $book = $this->repo->find((int)$_GET['id']);
        if (!$book) die('NOT FOUND');
        $this->view('books/edit', compact('book'));
    }

    public function update() {
        $this->repo->update((int)$_POST['id'], $_POST);
        $this->redirect('c=books&a=index&updated=1');
    }

    public function delete() {
        $this->repo->delete((int)$_POST['id']);
        $this->redirect('c=books&a=index&deleted=1');
    }
}
