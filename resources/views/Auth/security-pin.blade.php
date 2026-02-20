<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Security PIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            background: #ffffff;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pin-card {
            width: 360px;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.08);
            text-align: center;
        }

        .pin-inputs {
            display: flex;
            justify-content: center;
            gap: 14px; /* spacing between boxes */
            margin: 20px 0;
        }

        .pin-box {
            width: 55px;
            height: 55px;
            font-size: 22px;
            text-align: center;
            border-radius: 8px;
            border: 1px solid #ced4da;
        }

        .pin-box:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.15rem rgba(13,110,253,.25);
            outline: none;
        }
    </style>
</head>
<body>

    <div class="pin-card">
        <h5 class="mb-3">Enter Security PIN</h5>

        <form method="POST" action="{{ route('security.pin.verify') }}">
            @csrf

            <div class="pin-inputs">
                <input type="password" maxlength="1" name="pin[]" class="pin-box" autofocus>
                <input type="password" maxlength="1" name="pin[]" class="pin-box">
                <input type="password" maxlength="1" name="pin[]" class="pin-box">
                <input type="password" maxlength="1" name="pin[]" class="pin-box">
            </div>

            @error('pin')
                <div class="text-danger mb-2">{{ $message }}</div>
            @enderror

            <button class="btn btn-primary w-100">Verify PIN</button>
        </form>
    </div>

    <script>
        const inputs = document.querySelectorAll('.pin-box');

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value && inputs[index + 1]) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && inputs[index - 1]) {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>

</body>
</html>