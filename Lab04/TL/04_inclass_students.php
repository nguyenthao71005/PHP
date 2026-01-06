<?php
$input = "SV001-An-3.2;SV002-Binh-2.6;SV003-Chi-3.5";

class Student {
    private string $id;
    private string $name;
    private float $gpa;

    public function __construct(string $id, string $name, float $gpa) {
        $this->id = $id;
        $this->name = $name;
        $this->gpa = $gpa;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getGpa(): float {
        return $this->gpa;
    }

    public function getRank(): string {
        if ($this->gpa >= 3.2) return "Giỏi";
        if ($this->gpa >= 2.5) return "Khá";
        return "Trung bình";
    }
}

$records = explode(";", $input);
$Slist = [];

foreach ($records as $r) {
    $parts = explode("-", $r);
    if (count($parts) !== 3) continue;

    $id   = trim($parts[0]);
    $name = trim($parts[1]);
    $gpa  = (float) trim($parts[2]);

    $Slist[] = new Student($id, $name, $gpa);
}
?>

<h3>1. Danh sách sinh viên</h3>
<table border="1" cellpadding="6" style="border-collapse: collapse;">
<tr>
    <th>Tên</th>
    <th>GPA</th>
    <th>Xếp loại</th>
</tr>

<?php foreach ($Slist as $sv): ?>
<tr>
    <td><?= htmlspecialchars($sv->getName()) ?></td>
    <td><?= $sv->getGpa() ?></td>
    <td><?= $sv->getRank() ?></td>
</tr>
<?php endforeach; ?>
</table>

<h4>2. Sinh viên GPA ≥ 3.2</h4>
<ul>
<?php foreach ($Slist as $sv): ?>
    <?php if ($sv->getGpa() >= 3.2): ?>
        <?= htmlspecialchars($sv->getName()) ?> (<?= $sv->getGpa() ?>)<br>
    <?php endif; ?>
<?php endforeach; ?>
</ul>
