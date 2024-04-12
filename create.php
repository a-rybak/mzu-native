<?php
require_once "app.config.php";
require_once "functions.php";

$productName = $quantity = $amount = $measure = $purchasedAt = "";
$errors = [];


// if form has submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // validate input attributes
    $productName = sanitizeAttribute($_POST['product_name']);
    if (!validateAttribute($productName)) {
        $errors['productName'] = "В назві дозволені тільки літери";
    }

    $quantity = sanitizeAttribute($_POST['quantity']);
    if (!validateAttribute($quantity, 'integer')) {
        $errors['quantity'] = "В кількості дозволені тільки цифри або перевищено дiапазон (0-100)";
    }

    $amount = sanitizeAttribute($_POST['amount']);
    if (!validateAttribute($amount, 'float')) {
        $errors['amount'] = "В ціні дозволені тільки цифри або перевищено дiапазон (10-1000)";
    }

    $purchasedAt = strtotime(sanitizeAttribute($_POST['purchased_at']));

    $measure = sanitizeAttribute($_POST['measure']);

    // if errors array is empty - insert data
    if (!sizeof($errors)) {

        $sqlQuery = "INSERT INTO purchase(product_name, quantity, amount, purchased_at, measure) VALUES (?,?,?,?,?);";
        try {

            if ($statement = mysqli_prepare($link, $sqlQuery)) {

                // set params with their types
                mysqli_stmt_bind_param($statement, "sidis", $prsName, $prsQuantity, $prsPrice, $prsDate, $prsMeasure);

                $prsName = $productName;
                $prsQuantity = $quantity;
                $prsPrice = $amount;
                $prsDate = $purchasedAt;
                $prsMeasure = $measure;

                // execute insert query
                if (mysqli_stmt_execute($statement)) {
                    $_SESSION['operation'] = 'success';
                    $_SESSION['message'] = "Запис успiшно додано в БД";
                    header("location: index.php");
                    exit();
                } else {
                    throw new Exception('Insert data failed!');
                }
            }
        } catch (Exception $e) { // catch query errors
            $_SESSION['operation'] = 'danger';
            $_SESSION['message'] = "Помилка! Запис не вдалося додати до БД";
            echo "<pre>";
            print_r($e->getMessage());
            echo "</pre>";
            exit;
        }

        mysqli_stmt_close($statement);
    }

    mysqli_close($link);
}
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
        #create-form-container {
            border: 1px solid darkgray;
            border-radius: 6px;
            padding: 20px 15px;
            box-shadow: 4px 4px 8px 1px lightgray;
        }

        .btns > * {
            display: inline-block;
            float: right;
            margin: 0 5px;
        }

        .control-label {
            font-weight: bold;
            margin: 8px 0;
        }

        .has-error-group > span.errored {
            font-size: .8rem;
            color: red;
            text-align: center;
            display: inline-block;
            width: 100%;
        }

        .has-error-group > label {
            color: red;
        }

        .has-error-group > .form-control {
            border: 1px solid red;
        }
    </style>
    <script>
        function cleanErroredView(id) {
            const input = document.getElementById(id);
            const formGroup = input.closest('.form-group')
            if (formGroup.classList.contains('has-error-group')) {
                formGroup.classList.remove('has-error-group');
                const spanErrored = input.nextElementSibling;
                spanErrored.style.display = 'none';
                spanErrored.textContent = '';
            }
        }
    </script>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3 text-center my-3">
                <h2>Додати новий запис</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 offset-md-3" id="create-form-container">
                <form action="<?= $_SERVER["PHP_SELF"] ?>" method="POST">
                    <div class="form-group mb-3 <?= array_key_exists('productName', $errors) ? 'has-error-group' : ''; ?>">
                        <label class="control-label" for="product_name">Назва продукту</label>
                        <input
                                type="text"
                                required
                                name="product_name"
                                id="product_name"
                                onfocus="cleanErroredView(this.id)"
                                class="form-control"
                                value="<?= $productName ?>"
                        />
                        <span class="errored">
                            <?= array_key_exists('productName', $errors) ? $errors['productName'] : '' ?>
                        </span>
                    </div>
                    <div class="form-group mb-3 <?= array_key_exists('quantity', $errors) ? 'has-error-group' : ''; ?>">
                        <label class="control-label" for="quantity">Кількість</label>
                        <input
                                type="number"
                                required
                                name="quantity"
                                id="quantity"
                                onfocus="cleanErroredView(this.id)"
                                class="form-control"
                                value="<?= $quantity ?>"
                        />
                        <span class="errored">
                            <?= array_key_exists('quantity', $errors) ? $errors['quantity'] : '' ?>
                        </span>
                    </div>
                    <div class="form-group mb-3 <?= array_key_exists('amount', $errors) ? 'has-error-group' : ''; ?>">
                        <label class="control-label" for="amount">Ціна</label>
                        <input
                                type="text"
                                required
                                min="10"
                                max="1000"
                                step="0.1"
                                name="amount"
                                onfocus="cleanErroredView(this.id)"
                                id="amount" class="form-control"
                                value="<?= $amount; ?>"
                        />
                        <span class="errored">
                            <?= array_key_exists('amount', $errors) ? $errors['amount'] : '' ?>
                        </span>
                    </div>
                    <div class="form-group mb-3 <?= array_key_exists('purchased_at', $errors) ? 'has-error-group' : ''; ?>">
                        <label class="control-label" for="purchaseDate">Дата закупівлі</label>
                        <input
                                type="date"
                                required
                                name="purchased_at"
                                id="purchaseDate"
                                class="form-control"
                                value="<?= $purchasedAt ?>"
                        />
                        <span class="errored">
                            <?= array_key_exists('purchased_at', $errors) ? $errors['purchased_at'] : '' ?>
                        </span>
                    </div>
                    <div class="form-group mb-3 <?= array_key_exists('measure', $errors) ? 'has-error-group' : ''; ?>">
                        <label class="control-label" for="">Дозування</label>
                        <select class="form-select" aria-label="Default select example" required name="measure" id="measure">
                            <option value="">Оберiть...</option>
                            <?php
                            foreach (Measure::cases() as $key => $m) {
                                echo '<option value="' . Measure::getNameById($key) . '">' . Measure::getValueById($key) . '</option>';
                            }
                            ?>
                        </select>
                        <span class="errored">
                            <?= array_key_exists('measure', $errors) ? $errors['measure'] : '' ?>
                        </span>
                    </div>
                    <hr>
                    <div class="my-3 text-right btns">
                        <a href="index.php" class="btn btn-secondary ml-2">Назад до списку</a>
                        <input type="submit" class="btn btn-success" value="Створити">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>