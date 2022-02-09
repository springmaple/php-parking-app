<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Parking Price Calculator</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style>
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Parking Price Calculator</h1>

    <?php
        $message = "";
        $errorMessage = "";

        $startDateTime = new DateTime();
        $endDateTime = new DateTime();

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $startDateTime = $_POST['startDateTime'];
            $endDateTime = $_POST['endDateTime'];

            if (empty($startDateTime)) {
                $errorMessage = "Please enter a start date and time";
            } else if (empty($endDateTime)) {
                $errorMessage = "Please enter an end date and time";
            } else {
                $startDateTime = new DateTime($startDateTime);
                $endDateTime = new DateTime($endDateTime);
                if ($startDateTime > $endDateTime) {
                    $errorMessage = "Start date time must be before end date time";
                } else {
                    $oneHour = new DateInterval('PT1H');
                    $price = 0;
                    $tmpStartDateTime = clone $startDateTime;
                    if ($tmpStartDateTime != $endDateTime) {
                        do {
                            $tmpStartDateTime->add($oneHour);
                            $hour = $tmpStartDateTime->format('H');
                            if ($hour > 8 && $hour <= 20) {
                                $price += 2;
                            } else {
                                $price += 1;
                            }
                        } while($tmpStartDateTime < $endDateTime);
                    }
                    $message = "Price: MYR " . number_format((float)$price, 2, '.', '');
                }
            }
        }
    ?>

    <form action="" method="post">
        <div class="card" style="padding:10px;">
            <h5 class="card-title">Rates</h5>
            <p class="card-text">8am - 8pm (MYR 2.00/hour)<br>Other hours (MYR 1.00/hour)</p>
        </div>
        <div class="mt-2">
            <label for="startDateTime">Parking from</label>
            <?= '<input type="datetime-local" id="startDateTime" name="startDateTime" value="' . $startDateTime->format('Y-m-d\TH:i') . '">' ?>
            <label for="endDateTime">to</label>
            <?= '<input type="datetime-local" id="endDateTime" name="endDateTime" value="' . $endDateTime->format('Y-m-d\TH:i') . '">' ?>
        </div>
        <button type="submit">Calculate</button>
    </form>

    <div class="success"><?= $message ?></div>
    <div class="error"><?= $errorMessage ?></div>
</div>
</body>
</html>