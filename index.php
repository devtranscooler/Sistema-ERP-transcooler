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
            <div class="mb-3 position-relative">
                <input type="password" id="PASSWORD" name="PASSWORD" class="form-control" placeholder="Password" required>
                <button type="button" id="changeInpt" class="btn btn-transparent position-absolute top-50 end-0 translate-middle-y p-1 me-2"></button>
            </div>
            <button type="submit" class="btn btn-login w-100">Entrar</button>
        </form>
    </div>

    <script>

        let inptPassword = document.getElementById("PASSWORD")
        const btnChange = document.getElementById("changeInpt")

        document.addEventListener("DOMContentLoaded", (event) => {
            
            btnChange.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"></path>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"></path>
                </svg>`;
        });

        btnChange.addEventListener("click", (e) => {
            e.preventDefault()
            btnChange.innerHTML = ""

            if(inptPassword.getAttribute("type") === "password") {
                btnChange.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                        <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                        <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                        <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                    </svg>`;
                return inptPassword.setAttribute("type", "text")
            } 

            btnChange.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
                    <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
                    <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
                </svg>`;
            return inptPassword.setAttribute("type", "password")
        })

    </script>

</body>
</html>