<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>F1 - {{ $quiz['title'] }}</title>
    <style>
        body { background: #0b0b0b; color: white; font-family: sans-serif; display: flex; justify-content: center; padding: 20px; }
        .cockpit { width: 100%; max-width: 700px; background: #1a1a1a; border-radius: 15px; padding: 30px; border-top: 5px solid #e10600; }
        .q-card { background: #252525; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
        .option { display: block; background: #333; padding: 15px; margin-top: 10px; border-radius: 5px; cursor: pointer; }
        .option:hover { background: #444; border: 1px solid #e10600; }
        .btn { width: 100%; background: #e10600; color: white; padding: 20px; border: none; border-radius: 10px; font-weight: bold; cursor: pointer; font-size: 16px; }
    </style>
</head>
<body>
    <div class="cockpit">
        <h1 style="color: #e10600; text-align: center;">{{ $quiz['title'] }}</h1>
        <form action="/quiz/{{ $id }}/finish" method="POST">
            @csrf
            @foreach($quiz['questions'] as $i => $q)
                <div class="q-card">
                    <p><strong>{{ $i+1 }}.</strong> {{ $q['q'] }}</p>
                    @foreach($q['options'] as $oi => $opt)
                        <label class="option">
                            <input type="radio" name="q{{ $i }}" value="{{ $oi }}" required> {{ $opt }}
                        </label>
                    @endforeach
                </div>
            @endforeach
            <button class="btn">CRUZAR A LINHA DE CHEGADA</button>
        </form>
    </div>
</body>
</html>