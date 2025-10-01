<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MGCAFE - Login</title>
    <?php include 'includes/favicon.php'; ?>
    <?php include 'includes/password-toggle.php'; ?>
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Additional favicon for better browser support -->
    <link rel="icon" type="image/png" href="images/logo.png">
    <style>
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #eae2da; 
            color: #333;
        }

        .container {
            display: flex;
            flex-direction: row;
            background: #f5f1ed;
            border-radius: 16px;
            overflow: hidden;
            width: 90%;
            max-width: 1200px;
            height: 70vh;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            border: 1px solid #eaeaea;
        }

        .image-section {
            flex: 1;
            background: url('../images/background.png') no-repeat center center/cover;
            position: relative;
        }

        .login-box {
            flex: 1;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: #ffffff;
            border-left: 1px solid #eaeaea;
        }

        .login-box h1 {
            font-size: 30px;
            margin-top: -10px;
            margin-bottom: 25px;
            font-weight: 700;
            color: #0072ff;
        }

        .input-group {
            margin-bottom: 20px;
            width: 100%;
            max-width: 320px;
            text-align: left;
            position: relative;
        }

        .input-group label {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            font-weight: 400;
            color: #9ca3af;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            pointer-events: none;
            background-color: #ffffff;
            padding: 0 4px;
        }

        .input-group input {
            width: 300px;
            padding: 16px 18px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            background-color: #ffffff;
            color: #2d3748;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 400;
            letter-spacing: 0.02em;
            box-sizing: border-box;
        }

        .input-group input:focus {
            border-color: #0072ff;
            box-shadow: 0 0 0 3px rgba(0, 114, 255, 0.1), 0 4px 12px rgba(0, 114, 255, 0.15);
            outline: none;
            background-color: #ffffff;
            transform: translateY(-1px);
        }

        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
            top: 0;
            transform: translateY(-50%);
            font-size: 12px;
            color: #0072ff;
            font-weight: 500;
        }

        .input-group input:hover:not(:focus) {
            border-color: #cbd5e0;
            background-color: #f7fafc;
        }

        /* Password toggle specific styles for index.php */
        .input-group[data-password-toggle] {
            position: relative;
        }

        .input-group .password-toggle-btn {
            position: absolute;
            right: 28px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            color: #9ca3af;
            transition: color 0.2s ease;
            z-index: 10;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
        }

        .input-group .password-toggle-btn:hover {
            color: #0072ff;
        }

        .input-group .password-toggle-btn:focus {
            outline: none;
            color: #0072ff;
        }

        /* Material Design eye icons */
        .input-group .password-toggle-btn {
            font-family: 'Material Icons';
            font-size: 20px;
            line-height: 1;
        }

        .input-group .password-toggle-btn.show::before {
            content: "visibility_off";
        }

        .input-group .password-toggle-btn.hide::before {
            content: "visibility";
        }

        /* Adjust input padding to accommodate toggle button while maintaining same visual width */
        .input-group[data-password-toggle] input {
            padding-right: 45px; /* Space for toggle button */
        }

        button {
            width: 330px;
            padding: 14px;
            background: linear-gradient(135deg, #00c6ff, #0072ff);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
        }

        button:hover {
            background: linear-gradient(135deg, #0072ff, #00c6ff);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 114, 255, 0.25);
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                width: 90%;
                height: auto;
            }
            .image-section {
                height: 200px;
            }
            .login-box {
                border-left: none;
                border-top: 1px solid #eaeaea;
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section"></div>
        <div class="login-box">
            <img src="images/logo.png" alt="MGCAFE Logo" style="width: 200px; height: auto; margin-bottom: 0;">
            <h1>Sign in to your workspace</h1>
            <form method="POST" action="php/login.php">
                <div class="input-group">
                    <input type="email" id="email" name="email" placeholder=" " required>
                    <label for="email">Email Address</label>
                </div>
                <div class="input-group" data-password-toggle>
                    <input type="password" id="password" name="password" placeholder=" " required>
                    <label for="password">Password</label>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
