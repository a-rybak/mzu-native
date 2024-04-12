<?php
require_once "app.config.php";
?>
<!DOCTYPE html>
<html lang="EN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MZU Test task</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
    <style>
        .actions > .btn {
            display: inline-block;
            margin: 0 5px;
            float: right;
        }

        .table thead th {
            text-align: center;
        }

        .boxable {
            border: 1px solid darkgray;
            border-radius: 6px;
            padding: 20px 15px;
            box-shadow: 4px 4px 8px 1px lightgray;
            margin-left: 15px;
        }

        #addPurchase {
            margin-top: 5px;
        }
        .operation span {
            display: inline-block;
            width: 100%;
            margin: 5px 0;
        }
        #paginator > nav {
            display: inline-block;
        }
    </style>
    <script>
        $(document).ready(function (){
            // flash message hide handler
            if($('.operation span.alert').length){
                setTimeout(() => {
                    $('.operation span.alert').fadeOut(400).remove()
                }, 3000)
            }
        })
    </script>
</head>
<body>
<div class="wrapper">
    <div class="container">

        <div class="row">
            <div class="col-md-12 operation">
                <?php
                    // Flash message for operation result
                    if (isset($_SESSION['operation'])) {
                        echo "<span class='alert alert-".$_SESSION['operation']."'>";
                        echo $_SESSION['message'];
                        echo "</span>";
                    }
                    unset($_SESSION['operation'], $_SESSION['message']);
                ?>
            </div>
        </div>

        <div class="row my-2">
            <div class="col-md-12 boxable">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Закупівлі медичного обладнання</h2>
                    </div>
                    <div class="col-md-6">
                        <a href="create.php" id="addPurchase" type="button" class="btn btn-success float-end">+ Додати</a>
                    </div>
                </div>

                <?php
                try {
                    // work with pagination parameters
                    $limit = $_SESSION['pageSize'];
                    $currentPage = $_GET['page'] ?? $_SESSION['page'];
                    $offset = ($currentPage - 1) * $limit;

                    $queryForCount = mysqli_query($link, "SELECT count(*) as c FROM purchase");
                    $totalRecordsCount = (int)mysqli_fetch_assoc($queryForCount)['c'];
                    $totalPagesCount = ceil($totalRecordsCount / $limit);

                    // get rows with data from DB
                    $queryResult = mysqli_query($link, "SELECT * FROM purchase LIMIT $limit OFFSET $offset");
                    if (mysqli_num_rows($queryResult) === 0) {
                        echo '<hr><span class="alert alert-danger mx-auto text-center" style="display: inline-block; width: 100%;">Даних не знайдено...</span>';
                    } else {
                        echo '<table class="table table-striped table-hover table-bordered mt-5">
                                <thead>
                                    <tr>
                                        <th scope="col" width="100">ID</th>
                                        <th scope="col" width="350">Назва продукту</th>
                                        <th scope="col" width="250">Кількість, шт.</th>
                                        <th scope="col" width="250">Ціна, грн.</th>
                                        <th scope="col" width="250">Дата закупівлі</th>
                                        <th scope="col" width="250">Дозування</th>
                                    </tr>
                                </thead>
                                <tbody>';
                        //                                    <th scope="col">Actions</th>
                        while ($row = mysqli_fetch_array($queryResult)) {
                            echo "<tr>";
                            echo "<th class='text-center'>" . $row['id'] . "</th>";
                            echo "<td>" . $row['product_name'] . "</td>";
                            echo "<td class='text-center'>" . $row['quantity'] . "</td>";
                            echo "<td class='text-center'>" . number_format($row['amount'], 2, '.', '') . "</td>";
                            echo "<td class='text-center'>" . date('d.m.Y H:i', $row['purchased_at']) . "</td>";
                            echo "<td class='text-center'>" . Measure::getValueByName($row['measure']) . "</td>";
                            //                            echo "<td class='actions'>";
                            //                            echo "<a class='btn btn-primary' href='edit.php?id=".$row['id']."'>Edit</a>";
                            //                            echo "<a class='btn btn-danger' href='delete.php?id=".$row['id']."'>Delete</a>";
                            //                            echo "</td>";
                            echo "</tr>";
                        }
                        echo '</tbody></table>';
                        ?>
                        <div id="paginator" class="text-center">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <?php
                                        for ($i=1; $i <= $totalPagesCount; $i++){
                                            $active = $currentPage == $i ? 'active' : '';
                                            echo '<li class="page-item '.$active.'"><a class="page-link" href="index.php?page='.$i.'">'.$i.'</a></li>';
                                        }
                                    ?>
                                </ul>
                            </nav>
                        </div>
                        <?php
                    }
                } catch (Exception $e) {
                    throw new ErrorException('Query to DB failed!');
                }
                mysqli_close($link);
                ?>
            </div>
        </div>
    </div>
</div>
</body>
</html>