<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Database.php';

require_once __DIR__ . '/../models/BorrowRepository.php';
require_once __DIR__ . '/../models/BooksRepository.php';
require_once __DIR__ . '/../models/BorrowerRepository.php';

class BorrowsController extends Controller {

    private $borrowRepo;
    private $bookRepo;
    private $borrowerRepo;

    public function __construct() {
        $pdo = Database::getConnection(); // PDO chuẩn

        $this->borrowRepo   = new BorrowRepository($pdo);
        $this->bookRepo     = new BooksRepository($pdo);
        $this->borrowerRepo = new BorrowerRepository($pdo);
    }

    // ==============================
    // DANH SÁCH PHIẾU MƯỢN
    // ==============================
    public function index() {
        $borrows = $this->borrowRepo->all();
        $this->view('borrows/index', compact('borrows'));
    }

    // ==============================
    // FORM TẠO PHIẾU MƯỢN
    // ==============================
    public function create() {
        $books = $this->bookRepo->all();
        $borrowers = $this->borrowerRepo->all();

        $this->view('borrows/create', compact('books', 'borrowers'));
    }

    // ==============================
    // LƯU PHIẾU MƯỢN
    // ==============================
    public function store() {
        $borrowerId = (int)($_POST['borrower_id'] ?? 0);
        $borrowDate = $_POST['borrow_date'] ?? date('Y-m-d');
        $note       = $_POST['note'] ?? '';
        $items      = $_POST['items'] ?? [];

        if ($borrowerId <= 0 || empty($items)) {
            $this->redirect('c=borrows&a=create&error=1');
        }

        $result = $this->borrowRepo->createBorrow(
            $borrowerId,
            $borrowDate,
            $note,
            $items
        );

        if ($result === true) {
            $this->redirect('c=borrows&a=index&success=1');
        } else {
            $this->redirect('c=borrows&a=create&error=' . urlencode($result));
        }
    }

    // ==============================
    // XEM CHI TIẾT PHIẾU MƯỢN
    // ==============================
    public function show() {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) die('INVALID ID');

        $borrow = $this->borrowRepo->find($id);
        if (!$borrow) die('BORROW NOT FOUND');

        $items = $this->borrowRepo->items($id);

        $this->view('borrows/show', compact('borrow', 'items'));
    }

    // ==============================
    // XÓA PHIẾU MƯỢN (OPTIONAL)
    // ==============================
    public function delete() {
        $id = (int)($_POST['id'] ?? 0);

        if ($id <= 0) {
            $this->redirect('c=borrows&a=index&error=1');
        }

        $this->borrowRepo->delete($id);
        $this->redirect('c=borrows&a=index&deleted=1');
    }
}
