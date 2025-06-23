<?php
$apiUrl = 'http://app-service/';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    // Send POST to Flask app
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query(['name' => $name])
        ]
    ];
    $context  = stream_context_create($options);
    file_get_contents($apiUrl, false, $context);

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Fetch data from Flask
$response = file_get_contents($apiUrl);
$users = json_decode($response, true);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Flask-MySQL App</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px;
            background: linear-gradient(135deg, #f9f9f9, #e0f7fa);
            color: #333;
        }
        h1, h2 {
            color: #222;
            border-left: 5px solid #28a745;
            padding-left: 10px;
        }
        form {
            margin-top: 20px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }
        input[type="text"] {
            padding: 10px;
            flex: 1 1 250px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.05);
            font-size: 16px;
        }
        input[type="submit"] {
            padding: 10px 16px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        ul {
            margin-top: 20px;
            padding: 0;
            list-style: none;
            max-width: 400px;
        }
        li {
            background-color: white;
            margin-bottom: 10px;
            padding: 10px;
            border-left: 4px solid #28a745;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-radius: 3px;
        }
        @media (max-width: 600px) {
            body {
                margin: 20px;
            }
            form {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <h1>Enter a Name</h1>
    <form method="post">
        <input type="text" name="name" required placeholder="Enter a name" />
        <input type="submit" value="Add" />
    </form>

    <h2>User List</h2>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?php echo htmlspecialchars($user['name']); ?></li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
