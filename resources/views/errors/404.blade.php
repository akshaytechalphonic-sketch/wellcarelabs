<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>404 - Page Not Found | Wellcare Labs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="The page you are looking for could not be found. Return to Wellcare Labs homepage.">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Lottie Player -->
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, 'Roboto', sans-serif;
            background: linear-gradient(145deg, #f8fafc 0%, #eef2f6 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* 🔥 PREVENT BODY SCROLL */
        }

        /* Simple header – fixed height, no extra margins */
        .brand-header {
            background: #ffffff;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
            padding: 12px 0;
            border-bottom: 1px solid #eef2f6;
            flex-shrink: 0;
        }

        .brand-logo {
            max-height: 42px;
            width: auto;
        }

        /* Main container – takes all remaining space, centers content vertically */
        .error-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow: hidden;
        }

        .error-card {
            max-width: 650px;
            width: 100%;
            background: #ffffff;
            border-radius: 32px;
            padding: 1.8rem 2rem 2rem;
            box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08);
            text-align: center;
            border: 1px solid rgba(0, 102, 255, 0.08);
            transition: transform 0.2s ease;
        }

        .error-card:hover {
            transform: translateY(-4px);
        }

        /* Lottie – responsive, never oversized */
        .lottie-wrapper {
            max-width: 280px;
            margin: 0 auto 0.5rem auto;
        }

        dotlottie-player {
            width: 100%;
            height: auto;
            min-height: 160px;
        }

        /* Typography – compact but readable */
        .error-code {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(135deg, #0a2540 0%, #0d6efd 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 0.2rem;
            line-height: 1.1;
        }

        .error-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0a2540;
            margin-bottom: 0.5rem;
        }

        .error-message {
            font-size: 0.95rem;
            color: #5a6e7c;
            max-width: 400px;
            margin: 0 auto 1.2rem auto;
            line-height: 1.4;
        }

        /* Helpful links – compact */
        .helpful-links {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.7rem;
            margin: 1rem 0 1rem;
        }

        .helpful-link {
            background: #f0f4f9;
            border-radius: 40px;
            padding: 0.4rem 1rem;
            font-size: 0.85rem;
            font-weight: 500;
            color: #0d6efd;
            text-decoration: none;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .helpful-link i {
            font-size: 0.85rem;
        }

        .helpful-link:hover {
            background: #e6edf6;
            color: #0b5ed7;
            transform: translateY(-2px);
        }

        .btn-home-custom {
            background: linear-gradient(135deg, #0d6efd 0%, #0099ff 100%);
            border: none;
            padding: 8px 24px;
            border-radius: 40px;
            font-weight: 600;
            font-size: 0.95rem;
            transition: all 0.25s;
            box-shadow: 0 4px 10px rgba(13, 110, 253, 0.2);
        }

        .btn-home-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(13, 110, 253, 0.25);
            background: linear-gradient(135deg, #0b5ed7 0%, #0088e6 100%);
        }

        /* Simple footer – fixed height, no extra space */
        .simple-footer {
            text-align: center;
            padding: 0.8rem;
            font-size: 0.75rem;
            color: #8c9aa8;
            border-top: 1px solid #e2e8f0;
            background: #ffffff;
            flex-shrink: 0;
        }

        /* Responsive adjustments – keep everything inside viewport */
        @media (max-width: 576px) {
            .brand-header {
                padding: 8px 0;
            }
            .brand-logo {
                max-height: 36px;
            }
            .error-card {
                padding: 1rem 1.2rem 1.5rem;
            }
            .lottie-wrapper {
                max-width: 200px;
            }
            dotlottie-player {
                min-height: 120px;
            }
            .error-code {
                font-size: 3rem;
            }
            .error-title {
                font-size: 1.2rem;
            }
            .error-message {
                font-size: 0.85rem;
                margin-bottom: 0.8rem;
            }
            .helpful-link {
                padding: 0.3rem 0.8rem;
                font-size: 0.75rem;
            }
            .btn-home-custom {
                padding: 6px 20px;
                font-size: 0.85rem;
            }
            .simple-footer {
                padding: 0.5rem;
                font-size: 0.7rem;
            }
        }

        /* For very short screens (e.g., old laptops), reduce spacing further */
        @media (max-height: 650px) {
            .error-card {
                padding: 1rem 1.5rem 1.2rem;
            }
            .lottie-wrapper {
                max-width: 220px;
            }
            dotlottie-player {
                min-height: 130px;
            }
            .error-code {
                font-size: 3rem;
            }
            .error-title {
                font-size: 1.3rem;
                margin-bottom: 0.2rem;
            }
            .error-message {
                margin-bottom: 0.6rem;
            }
            .helpful-links {
                margin: 0.6rem 0;
            }
        }
    </style>
</head>

<body>

    <!-- Mini header with logo -->
    <div class="brand-header">
        <div class="container">
            <div class="d-flex justify-content-center">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('Front_end/assets/img/new_logo_banner.png') }}" alt="Wellcare Labs" class="brand-logo">
                </a>
            </div>
        </div>
    </div>

    <!-- Main 404 content – vertically centered -->
    <div class="error-container">
        <div class="error-card">
            <div class="lottie-wrapper">
                <dotlottie-player src="https://lottie.host/9fd671ce-25ef-4b74-8627-08e358f7e5dc/3WBkRkEZG8.lottie"
                    background="transparent" speed="1" loop autoplay>
                </dotlottie-player>
            </div>

            <div class="error-code">404</div>
            <div class="error-title">Page not found</div>
            <div class="error-message">
                Oops! The page you are looking for might have been moved or never existed.
            </div>

            <div class="helpful-links">
                <a href="{{ url('/packages') }}" class="helpful-link">
                    <i class="fas fa-flask"></i> Packages
                </a>
                <a href="{{ url('/services') }}" class="helpful-link">
                    <i class="fas fa-microscope"></i> Tests
                </a>
                <a href="{{ url('/booking') }}" class="helpful-link">
                    <i class="fas fa-calendar-check"></i> Book
                </a>
                <a href="{{ url('/contact_us') }}" class="helpful-link">
                    <i class="fas fa-headset"></i> Support
                </a>
            </div>

            <a href="{{ url('/') }}" class="btn btn-primary btn-home-custom">
                <i class="fas fa-home me-2"></i> Back to Home
            </a>
        </div>
    </div>

    <!-- Simple footer -->
    <div class="simple-footer">
        &copy; {{ date('Y') }} Wellcare Labs – Accurate Diagnostics, Trusted Care
    </div>

    <!-- Auto-redirect after 15 seconds (optional) -->
    <script>
        setTimeout(function() {
            window.location.href = "{{ url('/') }}";
        }, 15000);
    </script>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>