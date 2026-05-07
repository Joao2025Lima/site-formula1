<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscrição - StudyRank F1</title>
    <style>
        body {
            background-color: #15151e;
            color: white;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .register-card {
            background: #1f1f27;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            border-top: 5px solid #e10600;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        h1 { color: #e10600; text-align: center; text-transform: uppercase; margin-bottom: 30px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; color: #bbb; }
        input {
            width: 100%;
            padding: 12px;
            background: #2b2b2b;
            border: 1px solid #444;
            color: white;
            border-radius: 5px;
            box-sizing: border-box;
        }
        .btn-register {
            width: 100%;
            padding: 15px;
            background: #e10600;
            color: white;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .btn-register:hover { background: #b80500; }
        a { color: #bbb; text-decoration: none; display: block; text-align: center; margin-top: 15px; font-size: 14px; }
    </style>
</head>
<body>

    <div class="register-card">
        <h1>Novo Piloto</h1>
        <form action="/register" method="POST">
            @csrf
            <div class="form-group">
                <label>Nome do Piloto</label>
                <input type="text" name="name" required placeholder="Ex: Ayrton Senna">
            </div>
            <div class="form-group">
                <label>E-mail da Equipe</label>
                <input type="email" name="email" required placeholder="piloto@f1.com">
            </div>
            <div class="form-group">
                <label>Senha (Chave de Ignição)</label>
                <input type="password" name="password" required placeholder="Mínimo 6 caracteres">
            </div>
            <button type="submit" class="btn-register">ENTRAR NO PADDOCK</button>
            <a href="/login">Já sou piloto cadastrado</a>
        </form>
    </div>

</body>
</html>