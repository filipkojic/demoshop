<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="/src/Application/Presentation/Public/css/login.css">
</head>
<body>
<div class="login-container">
    <h1>Welcome</h1>
    <p>To demo shop administration!</p>
    <form action="/src/admin" method="POST">
        <div class="input-group">
            <label for="username">User name</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div class="input-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="input-group checkbox-group">
            <input type="checkbox" id="keep-logged-in" name="keep_logged_in">
            <label for="keep-logged-in">Keep me logged in</label>
        </div>
        <div class="input-group">
            <button type="submit">Log in</button>
        </div>
    </form>
</div>
</body>
</html>
