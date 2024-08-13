<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/src/Application/Presentation/Public/css/login.css">
</head>
<body>
<div class="loginContainer">
    <h1>Welcome</h1>
    <p>To demo shop administration!</p>

    <?php if (!empty($error)): ?>
        <div class="errorMessageBack"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>


    <form id="loginForm" action="/src/admin" method="POST">
        <div class="inputGroup">
            <label for="username">User name</label>
            <input type="text" id="username" name="username">
        </div>
        <div class="inputGroup">
            <label for="password">Password</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="inputGroup checkboxGroup">
            <input type="checkbox" id="keepLoggedIn" name="keepLoggedIn">
            <label for="keepLoggedIn">Keep me logged in</label>
        </div>
        <div class="inputGroup">
            <button type="submit">Log in</button>
        </div>
    </form>
    <script src="/src/Application/Presentation/Public/js/login.js"></script>
</div>
</body>
</html>
