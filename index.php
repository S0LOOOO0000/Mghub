<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
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
            background: url('../images/background.jpg') no-repeat center center/cover;
            position: relative;
        }
        .image-section::after {
            content: "";
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.25);
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
            margin-bottom: 25px;
            font-weight: 700;
            color: #0072ff;
        }

        .input-group {
            margin-bottom: 20px;
            width: 100%;
            max-width: 320px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            font-weight: 600;
            color: #555;
        }

        .input-group input {
            width: 300px;
            padding: 12px 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            background-color: #fafafa;
            color: #333;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: #0072ff;
            box-shadow: 0 0 6px rgba(0,114,255,0.3);
            outline: none;
            background-color: #fff;
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
            <h1>Login</h1>
            <form method="POST" action="php/login.php">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </div>
</body>
</html>
