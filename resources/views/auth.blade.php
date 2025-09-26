<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Voice - Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a2e 50%, #16213e 100%);
            color: #ffffff;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 80%, rgba(79, 172, 254, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(142, 68, 173, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }

        .auth-container {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 2px solid;
            border-image: linear-gradient(45deg, #4facfe, #8e44ad) 1;
            padding: 50px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5), 0 0 40px rgba(79, 172, 254, 0.1);
            position: relative;
            z-index: 1;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(45deg, #4facfe, #8e44ad, #00ff88, #4facfe);
            border-radius: 24px;
            z-index: -1;
            animation: borderGlow 3s ease-in-out infinite alternate;
        }

        @keyframes borderGlow {
            0% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #4facfe, #8e44ad);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 0 30px rgba(79, 172, 254, 0.5);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .logo h1 {
            font-size: 2.8rem;
            font-weight: 700;
            background: linear-gradient(45deg, #4facfe, #00f2fe, #8e44ad);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .logo p {
            color: #a0a0a0;
            font-size: 1.1rem;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 30px;
        }

        .welcome-text h2 {
            font-size: 2rem;
            font-weight: 600;
            background: linear-gradient(45deg, #ffffff, #a0a0a0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 8px;
        }

        .welcome-text p {
            color: #a0a0a0;
            font-size: 1rem;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 18px 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 2px solid rgba(79, 172, 254, 0.3);
            border-radius: 16px;
            color: #ffffff;
            font-size: 16px;
            transition: all 0.3s ease;
            position: relative;
        }

        .form-input::placeholder {
            color: #666;
        }

        .form-input:focus {
            outline: none;
            border-color: #4facfe;
            box-shadow: 0 0 25px rgba(79, 172, 254, 0.4), inset 0 0 20px rgba(79, 172, 254, 0.1);
            transform: translateY(-2px);
        }

        .password-container {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #666;
            cursor: pointer;
            font-size: 18px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #4facfe;
        }

        .auth-btn {
            width: 100%;
            background: linear-gradient(45deg, #4facfe, #8e44ad);
            border: none;
            border-radius: 16px;
            padding: 18px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 30px 0 20px;
            position: relative;
            overflow: hidden;
        }

        .auth-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .auth-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(79, 172, 254, 0.4);
        }

        .auth-btn:hover::before {
            left: 100%;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #4facfe, transparent);
        }

        .divider span {
            background: rgba(0, 0, 0, 0.4);
            padding: 0 20px;
            color: #a0a0a0;
            font-size: 14px;
        }

        .social-login {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-bottom: 25px;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 20px;
        }

        .social-btn:hover {
            border-color: #4facfe;
            box-shadow: 0 0 20px rgba(79, 172, 254, 0.3);
            transform: translateY(-2px);
        }

        .switch-form {
            text-align: center;
            color: #a0a0a0;
            margin-top: 20px;
        }

        .switch-form a {
            color: #4facfe;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .switch-form a:hover {
            text-shadow: 0 0 10px rgba(79, 172, 254, 0.5);
        }

        .forgot-password {
            text-align: center;
            margin-bottom: 20px;
        }

        .forgot-password a {
            color: #a0a0a0;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .forgot-password a:hover {
            color: #4facfe;
            text-shadow: 0 0 10px rgba(79, 172, 254, 0.5);
        }

        .signup-form {
            display: none;
        }

        .error {
            color: #ff4757;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        .success {
            color: #00ff88;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .auth-container {
                margin: 20px;
                padding: 30px;
            }
            
            .logo h1 {
                font-size: 2.2rem;
            }
            
            .welcome-text h2 {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="logo">
            <div class="logo-icon">🎤</div>
            <h1>AI Voice</h1>
            <p>Text-to-Speech Translator</p>
        </div>

        <!-- Login Form -->
        <form id="loginForm" class="login-form">
            <div class="welcome-text">
                <h2>Welcome Back</h2>
                <p>Log in to continue</p>
            </div>
            
            <div class="form-group">
                <input type="email" class="form-input" id="loginUser" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <div class="password-container">
                    <input type="password" class="form-input" id="loginPassword" placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('loginPassword')">
                        👁️
                    </button>
                </div>
            </div>
            
            <div class="forgot-password">
                <a href="#">Forgot Password?</a>
            </div>
            
            <button type="submit" class="auth-btn">Login</button>
            
            <div class="divider">
                <span>or continue with</span>
            </div>
            
            <div class="social-login">
                <div class="social-btn">🔍</div>
                <div class="social-btn">📱</div>
                <div class="social-btn">💼</div>
            </div>
            
            <div class="switch-form">
                Don't have an account? <a onclick="showSignup()">Sign up</a>
            </div>
            <div id="loginError" class="error"></div>
        </form>

        <!-- Signup Form -->
        <form id="signupForm" class="signup-form">
            <div class="welcome-text">
                <h2>Create Account</h2>
                <p>Join us today</p>
            </div>
            
            <div class="form-group">
                <input type="text" class="form-input" id="signupUsername" placeholder="Choose a username" required>
            </div>
            
            <div class="form-group">
                <input type="email" class="form-input" id="signupEmail" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <div class="password-container">
                    <input type="password" class="form-input" id="signupPassword" placeholder="Create a password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('signupPassword')">
                        👁️
                    </button>
                </div>
            </div>
            
            <button type="submit" class="auth-btn">Sign Up</button>
            
            <div class="switch-form">
                Already have an account? <a onclick="showLogin()">Login</a>
            </div>
            <div id="signupError" class="error"></div>
            <div id="signupSuccess" class="success"></div>
        </form>
    </div>

    <script>
        function showSignup() {
            document.querySelector('.login-form').style.display = 'none';
            document.querySelector('.signup-form').style.display = 'block';
        }

        function showLogin() {
            document.querySelector('.signup-form').style.display = 'none';
            document.querySelector('.login-form').style.display = 'block';
        }
        
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;
            
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁️';
            }
        }

        document.getElementById('loginForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const user = document.getElementById('loginUser').value;
            const password = document.getElementById('loginPassword').value;

            try {
                const response = await fetch('/api/auth.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        action: 'login',
                        user: user,
                        password: password
                    })
                });

                const result = await response.json();
                
                if (result.status === 'success') {
                    localStorage.setItem('userId', result.user_id);
                    localStorage.setItem('username', result.username);
                    window.location.href = '/tts-app.html';
                } else {
                    document.getElementById('loginError').textContent = result.message;
                }
            } catch (error) {
                document.getElementById('loginError').textContent = 'Login failed. Please try again.';
            }
        });

        document.getElementById('signupForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('signupUsername').value;
            const email = document.getElementById('signupEmail').value;
            const password = document.getElementById('signupPassword').value;

            try {
                const response = await fetch('/api/auth.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        action: 'signup',
                        username: username,
                        email: email,
                        password: password
                    })
                });

                const result = await response.json();
                
                if (result.status === 'success') {
                    document.getElementById('signupSuccess').textContent = 'Account created! Please login.';
                    document.getElementById('signupError').textContent = '';
                    setTimeout(() => showLogin(), 2000);
                } else {
                    document.getElementById('signupError').textContent = result.message;
                }
            } catch (error) {
                document.getElementById('signupError').textContent = 'Signup failed. Please try again.';
            }
        });
    </script>
</body>
</html>