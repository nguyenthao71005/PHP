<?php
    if(isset($_GET['name']) && isset($_GET['age'])) {
    $name = htmlspecialchars($_GET['name']);
    $age = htmlspecialchars($_GET['age']);
    echo "Hello: $name, Age: $age";
    } else {
        echo "http://localhost/Lab01/get_demo.php?name=Thao&age=20";
    }
?>