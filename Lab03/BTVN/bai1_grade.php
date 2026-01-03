<?php
$score = isset($_GET["score"]) ? (float)$_GET["score"] : null;

if ($score === null) {
    echo "Hãy truyền ?score=...";
    exit;
}

if ($score < 0 || $score > 10) {
    echo "Điểm không hợp lệ (0 ≤ score ≤ 10)";
} elseif ($score >= 8.5) {
    echo "Điểm: $score – Xếp loại: Giỏi";
} elseif ($score >= 7.0) {
    echo "Điểm: $score – Xếp loại: Khá";
} elseif ($score >= 5.0) {
    echo "Điểm: $score – Xếp loại: Trung bình";
} else {
    echo "Điểm: $score – Xếp loại: Yếu";
}
?>
