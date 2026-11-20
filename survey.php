
<?php
require_once 'includes/functions.php';

$success = false;
$error = '';
$submittedAt = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = cleanInput($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $flight = cleanInput($_POST['flight'] ?? '');
    $service = cleanInput($_POST['service'] ?? '');
    $cleanliness = cleanInput($_POST['cleanliness'] ?? '');
    $food = cleanInput($_POST['food'] ?? '');
    $suggestions = cleanInput($_POST['suggestions'] ?? '');

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Перевірте ім’я та правильність електронної пошти.';
    } elseif (
        !in_array($flight, ['Так', 'Ні'], true) ||
        !in_array($service, ['Відмінно', 'Добре', 'Задовільно', 'Погано'], true) ||
        !in_array($cleanliness, ['Чисто', 'Потрібне покращення'], true) ||
        !in_array($food, ['Так', 'Ні', 'Не користувався'], true)
    ) {
        $error = 'Будь ласка, оберіть допустимі варіанти відповідей.';
    } else {

        $submittedAt = date('d.m.Y H:i:s');

        $data = [
            'name' => $name,
            'email' => $email,
            'flight' => $flight,
            'service' => $service,
            'cleanliness' => $cleanliness,
            'food' => $food,
            'suggestions' => $suggestions,
            'date' => $submittedAt
        ];

        if (saveSurvey($data)) {
            $success = true;
        } else {
            $error = 'Не вдалося зберегти відповіді.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анкета пасажира</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef3f8;
            margin: 0;
            color: #24364b;
        }

        .survey-container {
            max-width: 650px;
            margin: 35px auto;
            padding: 30px;
            background: white;
            border-radius: 14px;
        }

        h1 { color: #123c64; }

        label {
            display: block;
            margin-top: 18px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccd5df;
            border-radius: 7px;
        }

        button {
            margin-top: 25px;
            padding: 13px 25px;
            background: #123c64;
            color: white;
            border: none;
            border-radius: 7px;
            cursor: pointer;
        }

        .success {
            background: #e1f5e7;
            padding: 15px;
            border-radius: 8px;
        }

        .error {
            background: #ffe6e6;
            padding: 15px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

<?php include 'includes/header.php'; ?>

<div class="survey-container">

    <h1>Опитування пасажирів</h1>

    <p>Допоможіть нам покращити якість обслуговування в міжнародному аеропорту!</p>

    <?php if ($success): ?>

        <div class="success">
            <h2>Дякуємо за вашу відповідь!</h2>
            <p>Вашу анкету успішно збережено.</p>
            <p>Дата та час: <strong><?= $submittedAt ?></strong></p>
        </div>

    <?php else: ?>

        <?php if ($error !== ''): ?>
            <div class="error">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="survey.php">

            <label>Ваше ім'я:</label>
            <input type="text" name="name" maxlength="100" required>

            <label>Ваш Email:</label>
            <input type="email" name="email" maxlength="254" required>

            <label>1. Чи часто ви користуєтеся авіаперельотами?</label>
            <select name="flight" required>
                <option value="">Оберіть відповідь</option>
                <option>Так</option>
                <option>Ні</option>
            </select>

            <label>2. Як ви оцінюєте обслуговування?</label>
            <select name="service" required>
                <option value="">Оберіть оцінку</option>
                <option>Відмінно</option>
                <option>Добре</option>
                <option>Задовільно</option>
                <option>Погано</option>
            </select>

            <label>3. Чи задоволені ви чистотою аеропорту?</label>
            <select name="cleanliness" required>
                <option value="">Оберіть відповідь</option>
                <option>Чисто</option>
                <option>Потрібне покращення</option>
            </select>

            <label>4. Чи користувалися ви послугами харчування?</label>
            <select name="food" required>
                <option value="">Оберіть відповідь</option>
                <option>Так</option>
                <option>Ні</option>
                <option>Не користувався</option>
            </select>

            <label>5. Що б ви хотіли покращити?</label>
            <textarea name="suggestions" rows="4" maxlength="2000"></textarea>

            <button type="submit">Надіслати анкету</button>

        </form>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
