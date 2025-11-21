<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

session_start();
require_once "functionSet.php";
if (trim($_SESSION['role']) != 'admin') {
    header("Location: login.html");
    exit();
}

$sql = "
    SELECT e.event_type, COUNT(t.seat_num) AS soldT, SUM(t.ticket_price) AS total
    FROM events e
    JOIN ticket t ON e.event_id = t.event_id
    GROUP BY e.event_type
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
    crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>

<title>Overview</title>
</head>

<body>
    <div class="d-flex">
        <?= sidebarShow("overview"); ?>
        <!--Main div==============================================================================-->
        <div class="container-fluid content flex-grow-1 p-5">
            <!--<div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center"></div>-->
            <h2>Admin Overview</h2><br>
            <h4>Event Type with Revenue</h4>
            

            <?php
            if(empty($results)) {
                echo "<div class='alert alert-warning'>No data</div>";
            }else{
                echo "<div class='list-group mb-2'>";
                foreach($results as $row) {
                    echo "<div class='list-group-item'><strong>".$row['event_type']."</strong></div>";
                    echo "<div class='list-group-item'><strong>Ticket solded: </strong>".$row['soldT']."</div>";
                    echo "<div class='list-group-item'><strong>Total revenue: </strong>".$row['total']."</div>";
                    echo "<br>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>
</body>
</html>
