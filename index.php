<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Transcooler</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #063a61;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        /* Este es el cuadro blanco central */
        .login-card {
            background-color: white;
            padding: 2.5rem;
            border-radius: 20px; /* Esquinas redondeadas */
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .logo {
            margin-bottom: 2rem;
        }

        .logo img {
            width: 80%; /* Ajustado para que no sature el cuadro */
            height: auto;
        }

        .form-control {
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            color: #063a61;
            padding: 0.75rem;
        }

        .btn-login {
            background-color: #fabf19;
            color: #063a61;
            font-weight: bold;
            padding: 0.75rem;
            border: none;
            transition: background 0.3s ease;
        }

        .btn-login:hover {
            background-color: #e5ae17;
            color: #063a61;
        }

        #loginError {
            display: block;
            margin-bottom: 1rem;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="logo">
            <img src="./img/logo1.png" alt="Logo" />
        </div>

        <?php
        $error = isset($_GET['error']) ? $_GET['error'] : null;
        if ($error): ?>
            <label id="loginError" style="color:#d9534f"><?php echo htmlspecialchars($error); ?></label>
        <?php endif; ?>

        <form action="./access.php" method="POST">
            <div class="mb-3">
                <input type="text" id="USERNAME" name="USERNAME" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" id="PASSWORD" name="PASSWORD" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-login w-100">Entrar</button>
        </form>
    </div>


    

</body>
</html>