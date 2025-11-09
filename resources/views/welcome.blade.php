<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - OJT Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow-x: hidden;
            position: relative;
        }

        /* Animated background particles */
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0) rotate(0deg);
                opacity: 0.3;
            }
            50% {
                transform: translateY(-100px) translateX(50px) rotate(180deg);
                opacity: 0.7;
            }
        }

        /* Main container */
        .welcome-container {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            width: 90%;
            padding: 40px;
            text-align: center;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Logo/Icon */
        .logo-container {
            margin-bottom: 30px;
            animation: pulse 2s infinite ease-in-out;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .logo {
            width: 120px;
            height: 120px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .logo img {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }

        .logo .fallback-icon {
            font-size: 3rem;
            display: none;
        }

        /* Title */
        h1 {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
            animation: slideInLeft 1s ease-out 0.3s both;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Subtitle */
        .subtitle {
            font-size: 1.5rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 50px;
            font-weight: 300;
            animation: slideInRight 1s ease-out 0.5s both;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Features section - Pyramid Layout */
        .features {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 30px;
            margin-bottom: 50px;
            animation: fadeIn 1s ease-out 0.7s both;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        /* Pyramid rows */
        .feature-row {
            display: flex;
            justify-content: center;
            gap: 30px;
            width: 100%;
            flex-wrap: wrap;
        }

        .feature-row-1 {
            max-width: 320px;
        }

        .feature-row-2 {
            max-width: 680px;
        }

        .feature-row-3 {
            max-width: 1040px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 30px;
            transition: all 0.3s ease;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            flex: 0 0 280px;
            width: 280px;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.2);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        .feature-title {
            font-size: 1.3rem;
            color: white;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .feature-description {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.6;
        }

        /* System info section */
        .system-info {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 40px;
            animation: fadeIn 1s ease-out 0.9s both;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .system-info h2 {
            font-size: 2rem;
            color: white;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .system-info p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.8;
            margin-bottom: 15px;
        }

        .system-info ul {
            list-style: none;
            text-align: left;
            max-width: 700px;
            margin: 0 auto;
        }

        .system-info li {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            margin-bottom: 10px;
            padding-left: 25px;
            position: relative;
        }

        .system-info li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #4ade80;
            font-weight: bold;
            font-size: 1.2rem;
        }

        /* Get Started Button */
        .get-started-btn {
            display: inline-block;
            padding: 18px 50px;
            font-size: 1.2rem;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-out 1.1s both;
            position: relative;
            overflow: hidden;
        }

        .get-started-btn:before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .get-started-btn:hover:before {
            left: 100%;
        }

        .get-started-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .get-started-btn:active {
            transform: translateY(-1px) scale(1.02);
        }

        /* Responsive design */
        @media (max-width: 768px) {
            h1 {
                font-size: 2.5rem;
            }

            .subtitle {
                font-size: 1.2rem;
            }

            .feature-row {
                flex-direction: column;
                align-items: center;
            }

            .feature-card {
                flex: 0 0 auto;
                width: 100%;
                max-width: 350px;
            }

            .welcome-container {
                padding: 20px;
            }

            .system-info {
                padding: 25px;
            }
        }

        /* Scroll animation */
        @keyframes scroll {
            0% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(10px);
            }
            100% {
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Animated background particles -->
    <div class="particles">
        <div class="particle" style="width: 45px; height: 45px; left: 15%; top: 20%; animation-delay: 3s; animation-duration: 15s;"></div>
        <div class="particle" style="width: 30px; height: 30px; left: 75%; top: 40%; animation-delay: 7s; animation-duration: 18s;"></div>
        <div class="particle" style="width: 55px; height: 55px; left: 45%; top: 60%; animation-delay: 2s; animation-duration: 12s;"></div>
        <div class="particle" style="width: 35px; height: 35px; left: 85%; top: 15%; animation-delay: 9s; animation-duration: 16s;"></div>
        <div class="particle" style="width: 50px; height: 50px; left: 25%; top: 75%; animation-delay: 5s; animation-duration: 14s;"></div>
        <div class="particle" style="width: 40px; height: 40px; left: 60%; top: 30%; animation-delay: 11s; animation-duration: 19s;"></div>
        <div class="particle" style="width: 28px; height: 28px; left: 10%; top: 50%; animation-delay: 1s; animation-duration: 13s;"></div>
        <div class="particle" style="width: 48px; height: 48px; left: 90%; top: 70%; animation-delay: 8s; animation-duration: 17s;"></div>
        <div class="particle" style="width: 32px; height: 32px; left: 35%; top: 10%; animation-delay: 4s; animation-duration: 11s;"></div>
        <div class="particle" style="width: 42px; height: 42px; left: 70%; top: 85%; animation-delay: 6s; animation-duration: 20s;"></div>
    </div>

    <div class="welcome-container">
        <!-- Logo -->
        <div class="logo-container">
            <div class="logo">
                <img src="{{ asset('logo.png') }}" alt="OJT System Logo" id="logoImage" onerror="this.style.display='none'; document.getElementById('fallbackIcon').style.display='block';">
                <span class="fallback-icon" id="fallbackIcon">🎓</span>
            </div>
        </div>

        <!-- Title -->
        <h1>Welcome to OJT Management System</h1>
        <p class="subtitle">Streamline Your Internship Program with Ease</p>

        <!-- Features - Pyramid Layout -->
        <div class="features">
            <!-- Row 1: Top of pyramid (1 card) -->
            <div class="feature-row feature-row-1">
                <div class="feature-card">
                    <span class="feature-icon">👥</span>
                    <h3 class="feature-title">Intern Management</h3>
                    <p class="feature-description">
                        Efficiently manage intern registrations, track progress, and handle all phases of the internship program from pre-enrollment to deployment.
                    </p>
                </div>
            </div>

            <!-- Row 2: Middle of pyramid (2 cards) -->
            <div class="feature-row feature-row-2">
                <div class="feature-card">
                    <span class="feature-icon">📊</span>
                    <h3 class="feature-title">Real-Time Tracking</h3>
                    <p class="feature-description">
                        Monitor attendance, time logs, and journal submissions in real-time. Get instant updates on intern activities and progress.
                    </p>
                </div>

                <div class="feature-card">
                    <span class="feature-icon">💬</span>
                    <h3 class="feature-title">Communication Hub</h3>
                    <p class="feature-description">
                        Seamless messaging system between administrators, supervisors, and interns. Stay connected and informed throughout the program.
                    </p>
                </div>
            </div>

            <!-- Row 3: Bottom of pyramid (2 cards) -->
            <div class="feature-row feature-row-3">
                <div class="feature-card">
                    <span class="feature-icon">📄</span>
                    <h3 class="feature-title">Document Management</h3>
                    <p class="feature-description">
                        Organize and manage all internship documents including DTRs, journals, grades, and auto-generated letters and contracts.
                    </p>
                </div>

                <div class="feature-card">
                    <span class="feature-icon">🔐</span>
                    <h3 class="feature-title">Secure & Protected</h3>
                    <p class="feature-description">
                        Enterprise-grade security with encrypted passwords, OTP verification, and comprehensive security headers for data protection.
                    </p>
                </div>
            </div>
        </div>

        <!-- System Information -->
        <div class="system-info">
            <h2>How the System Works</h2>
            <p>
                Our OJT Management System is designed to simplify and automate the entire internship management process. Here's how it works:
            </p>
            <ul>
                <li><strong>Registration & Onboarding:</strong> Interns can register through invitation links. The system supports multiple phases including pre-enrollment, pre-deployment, mid-deployment, and deployment.</li>
                <li><strong>Authentication & Security:</strong> Multi-level authentication with OTP verification, password encryption using Argon2id, and account lockout protection against brute force attacks.</li>
                <li><strong>Role-Based Access:</strong> Three distinct user roles - Administrators, Supervisors, and Interns - each with appropriate permissions and dashboards.</li>
                <li><strong>Attendance Tracking:</strong> Real-time attendance marking with QR code verification, DTR generation, and supervisor approval workflows.</li>
                <li><strong>Document Management:</strong> Automated generation of acceptance letters, memorandums, contracts, and endorsement letters. Upload and manage grades, journals, and other required documents.</li>
                <li><strong>Communication:</strong> Built-in messaging system for direct communication between all parties, with broadcast capabilities for announcements.</li>
                <li><strong>Progress Monitoring:</strong> Track intern progress through different phases, view submitted documents, and manage the entire internship lifecycle.</li>
            </ul>
        </div>

        <!-- Get Started Button -->
        <a href="{{ route('login') }}" class="get-started-btn">
            Get Started →
        </a>
    </div>

    <script>
        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add parallax effect to particles on mouse move
        document.addEventListener('mousemove', (e) => {
            const particles = document.querySelectorAll('.particle');
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;

            particles.forEach((particle, index) => {
                const speed = (index % 5) * 0.5;
                const x = (mouseX - 0.5) * speed * 20;
                const y = (mouseY - 0.5) * speed * 20;
                particle.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    </script>
</body>
</html>