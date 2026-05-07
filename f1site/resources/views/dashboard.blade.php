<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - StudyRank F1</title>
    <style>
        :root { --f1-red: #e10600; --bg: #15151e; }
        body { background: var(--bg); color: white; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 20px; }
        .paddock { display: flex; max-width: 1200px; margin: auto; background: #2b2b2b; border-top: 5px solid var(--f1-red); border-radius: 15px; height: 650px; overflow: hidden; }
        .col { padding: 25px; border-right: 1px solid #444; }
        .profile { flex: 1; text-align: center; background: #333; }
        .quizzes { flex: 1.5; overflow-y: auto; background: #444; }
        .ranking { flex: 1.2; background: #666; }
        .avatar { width: 100px; height: 100px; border-radius: 50%; border: 3px solid var(--f1-red); margin-bottom: 10px; }
        .xp-bar { width: 100%; height: 10px; background: #111; border-radius: 5px; margin: 10px 0; }
        .xp-fill { height: 100%; background: var(--f1-red); width: {{ Auth::user()->xp }}%; }
        .quiz-item { background: #555; padding: 15px; margin-bottom: 10px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; border-left: 5px solid #777; }
        .unlocked { border-left-color: #44ff44; }
        .completed { border-left-color: gold; }
        .btn { background: var(--f1-red); color: white; padding: 8px 15px; text-decoration: none; font-weight: bold; border-radius: 4px; font-size: 12px; }
        .alert { padding: 15px; text-align: center; font-weight: bold; border-radius: 8px; margin-bottom: 15px; max-width: 1200px; margin-left: auto; margin-right: auto; }
    </style>
</head>
<body>

    @if(session('success')) <div class="alert" style="background: #44ff44; color: black;">{{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert" style="background: var(--f1-red); color: white;">{{ session('error') }}</div> @endif

    <div class="paddock">
        <div class="col profile">
            <div class="avatar" style="background: url('https://www.formula1.com/content/dam/fom-website/drivers/M/MAXVER01_Max_Verstappen/maxver01.png.transform/2col-retina/image.png') center/cover;"></div>
            <h2 style="color: var(--f1-red);">{{ Auth::user()->name }}</h2>
            <div class="xp-bar"><div class="xp-fill"></div></div>
            <p>NÍVEL: {{ Auth::user()->level }} | XP: {{ Auth::user()->xp }}</p>
            <form action="/logout" method="POST">@csrf <button style="background:none; border:1px solid #777; color:#ccc; cursor:pointer; padding:5px 10px; margin-top:200px;">ABANDONAR</button></form>
        </div>

        <div class="col quizzes">
            <h3 style="text-align: center;">TEMPORADA 2026</h3>
            @php
                $done = DB::table('user_quiz_progress')->where('user_id', Auth::id())->where('completed', true)->pluck('quiz_id')->toArray();
                $gps = [1 => 'GP PYTHON', 2 => 'GP JAVASCRIPT', 3 => 'GP SQL', 4 => 'GP PHP', 5 => 'GP BACKEND'];
            @endphp

            @foreach($gps as $id => $nome)
                @php $liberado = ($id == 1 || in_array($id - 1, $done)); $venceu = in_array($id, $done); @endphp
                <div class="quiz-item {{ $venceu ? 'completed' : ($liberado ? 'unlocked' : '') }}">
                    <span>{{ $id }}. {{ $nome }}</span>
                    @if($liberado) <a href="/quiz/{{ $id }}" class="btn">LARGADA</a> @else <span>🔒</span> @endif
                </div>
            @endforeach
        </div>

        <div class="col ranking">
            <h3 style="text-align: center;">PÓDIO</h3>
            <div style="background:rgba(0,0,0,0.2); padding:10px; border-radius:5px; margin-bottom:5px;">1º SENNA_CODE - 500xp</div>
            <div style="background:rgba(225,6,0,0.2); padding:10px; border-radius:5px; border:1px solid var(--f1-red);">2º {{ Auth::user()->name }} - {{ Auth::user()->xp }}xp</div>
        </div>
    </div>
</body>
</html>