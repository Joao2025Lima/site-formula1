<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StudyRank F1 - Login</title>
    <style>
        body {
            background-color: #0b0b0b;
            color: white;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .login-card {
            background: #1a1a1a;
            padding: 40px;
            border-radius: 15px;
            border-top: 4px solid #e10600;
            width: 350px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.8);
            text-align: center;
        }

        h1 { 
            color: #e10600; 
            margin-bottom: 5px; 
            text-transform: uppercase; 
            letter-spacing: 3px;
            font-size: 28px;
        }

        p.subtitle {
            color: #888;
            margin-bottom: 30px;
            font-size: 14px;
            text-transform: uppercase;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label { 
            display: block; 
            margin-bottom: 8px; 
            color: #eee; 
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        input {
            width: 100%;
            padding: 12px;
            background: #2b2b2b;
            border: 1px solid #444;
            border-radius: 5px;
            color: white;
            box-sizing: border-box;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #e10600;
            background: #333;
        }

        button {
            width: 100%;
            padding: 15px;
            background: #e10600;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            text-transform: uppercase;
            font-size: 16px;
            transition: 0.3s;
            margin-top: 10px;
        }

        button:hover {
            background: #b80500;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(225, 6, 0, 0.4);
        }

        /* Alertas de Erro e Sucesso */
        .error-msg {
            background: rgba(255, 0, 0, 0.1);
            color: #ff4d4d;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid #ff4d4d;
        }

        .success-msg {
            background: rgba(68, 255, 68, 0.1);
            color: #44ff44;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
            border: 1px solid #44ff44;
        }

        .register-link {
            display: block;
            margin-top: 20px;
            color: #888;
            text-decoration: none;
            font-size: 13px;
            transition: 0.3s;
        }

        .register-link:hover {
            color: white;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <h1>StudyRank</h1>
        <p class="subtitle">F1 Racing Academy</p>

        @if(session('success'))
            <div class="success-msg">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any() || session('error'))
            <div class="error-msg">
                @if(session('error'))
                    {{ session('error') }}
                @else
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                @endif
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf 
            <div class="form-group">
                <label for="email">E-mail do Piloto</label>
                <input type="email" name="email" id="email" required placeholder="seu@email.com">
            </div>

            <div class="form-group">
                <label for="password">Senha da Equipa</label>
                <input type="password" name="password" id="password" required placeholder="••••••••">
            </div>

            <button type="submit">Largar / Iniciar Corrida</button>
        </form>

        <a href="/register" class="register-link">Não possui convite da equipe? Cadastre-se</a>
    </div>

</body>
</html>