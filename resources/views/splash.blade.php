<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Splash Screen</title>
    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #43A047;
            --accent-color: #66BB6A;
        }

        body {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow: hidden;
            animation: fadeIn 1s ease-in;
        }

        #logo {
            width: 250px;
            height: auto;
            animation: logoPulse 2s infinite;
        }

        @keyframes logoPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @media (max-width: 600px) {
            #logo {
                width: 200px;
            }
        }
    </style>
</head>
<body>
    <img id="logo" src="{{ asset('logo/logo.png') }}" alt="Logo">
    <script>
        setTimeout(() => {
            window.location.href = "{{ url('/login') }}";
        }, 2500);
    </script>
</body>
</html>