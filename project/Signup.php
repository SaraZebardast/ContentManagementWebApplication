<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - CMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f8;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            display: flex;
            width: 1400px;
            max-width: 90%;
            padding: 40px;
            gap: 80px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(35,25,66,0.1);
        }

        .signup-section {
            flex: 1;
            padding-top: 40px;
        }

        .image-section {
            flex: 1;
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }

        h1 {
            font-size: 42px;
            color: #231942;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .subtitle {
            color: #666;
            margin-bottom: 40px;
            font-size: 16px;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            color: #231942;
            margin-bottom: 8px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px 0;
            border: none;
            border-bottom: 1px solid #ddd;
            font-size: 16px;
            transition: border-color 0.3s;
            background: transparent;
        }

        select {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
        }

        input:focus,
        select:focus {
            outline: none;
            border-bottom-color: #231942;
        }

        .user-type-select {
            position: relative;
        }

        .user-type-select::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #231942;
            pointer-events: none;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
        }

        .checkbox-group input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #231942;
        }

        .checkbox-group label {
            color: #666;
            margin-bottom: 0;
        }

        .checkbox-group a {
            color: #231942;
            text-decoration: none;
        }

        button {
            background-color: #231942;
            color: white;
            padding: 15px 40px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #362a55;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #666;
        }

        .login-link a {
            color: #231942;
            text-decoration: none;
        }

        .illustration {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 20px;
            }
            
            .image-section {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="signup-section">
            <h1>Create Account</h1>
            <p class="subtitle">Join our content management platform and start creating!</p>

            <form>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" placeholder="Choose a username">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" placeholder="Create a password">
                </div>

                <div class="form-group">
                    <label for="confirm-password">Confirm Password</label>
                    <input type="password" id="confirm-password" placeholder="Confirm your password">
                </div>

                <div class="form-group user-type-select">
                    <label for="user-type">Account Type</label>
                    <select id="user-type">
                        <option value="">Select account type</option>
                        <option value="content_creator">Content Creator</option>
                        <option value="editor">Editor</option>
                    </select>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" id="terms">
                    <label for="terms">I agree to the <a href="./conditions.php">Terms & Conditions</a></label>
                </div>

                <button type="submit">Sign Up</button>
            </form>

            <p class="login-link">
                Already have an account? <a href="login.php">Log in</a>
            </p>
        </div>
        <div class="image-section">
            <img src="./pics/signUp.jpg" alt="Sign up illustration" class="illustration">
        </div>
    </div>
</body>
</html>