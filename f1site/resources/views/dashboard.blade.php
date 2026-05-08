<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - StudyRank F1</title>
    <style>
        :root { --f1-red: #e10600; --bg: #15151e; }
        body { background: var(--bg); color: white; font-family: 'Segoe UI', sans-serif; margin: 0; padding: 20px; }
        
        /* Layout Principal */
        .paddock { display: flex; max-width: 1200px; margin: auto; background: #2b2b2b; border-top: 5px solid var(--f1-red); border-radius: 15px; height: 750px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
        .col { padding: 25px; border-right: 1px solid #444; }
        
        /* Perfil */
        .profile { flex: 1; display: flex; flex-direction: column; align-items: center; text-align: center; background: #1f1f27; }
        .avatar { width: 130px; height: 130px; border-radius: 50%; border: 4px solid var(--f1-red); object-fit: cover; background: #333; }
        
        /* XP */
        .xp-bar { width: 85%; height: 12px; background: #111; border-radius: 6px; margin: 15px 0; overflow: hidden; border: 1px solid #444; }
        .xp-fill { height: 100%; background: linear-gradient(90deg, #b80500, var(--f1-red)); width: {{ Auth::user()->xp }}%; transition: 0.8s ease-in-out; }
        
        /* Troféus */
        .trophy-case { display: flex; justify-content: center; gap: 15px; margin-top: 15px; background: rgba(0,0,0,0.3); padding: 10px; border-radius: 10px; width: 90%; border: 1px solid #444; }
        .trophy-item { display: flex; flex-direction: column; align-items: center; }
        .trophy-icon { font-size: 22px; filter: drop-shadow(0 0 5px rgba(0,0,0,0.5)); }
        .trophy-count { font-size: 11px; font-weight: bold; margin-top: 2px; color: #fff; background: var(--f1-red); padding: 2px 6px; border-radius: 10px; }

        /* Quizzes */
        .quizzes { flex: 1.5; overflow-y: auto; background: #25252d; }
        .quiz-item { background: #333; padding: 18px; margin-bottom: 12px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; border-left: 6px solid #555; }
        .unlocked { border-left-color: #44ff44; background: #3d3d3d; }
        .completed { border-left-color: gold; background: rgba(255, 215, 0, 0.1); }
        
        /* Botões */
        .btn { background: var(--f1-red); color: white; padding: 10px 18px; text-decoration: none; font-weight: bold; border-radius: 4px; font-size: 12px; border: none; cursor: pointer; text-transform: uppercase; }
        .btn:hover { background: #ff1e18; transform: scale(1.05); }

        /* Ranking */
        .leaderboard { display: flex; flex-direction: column; gap: 8px; }
        .rank-item { display: flex; justify-content: space-between; padding: 10px; background: rgba(255,255,255,0.05); border-radius: 5px; font-size: 14px; border-left: 3px solid transparent; }
        .rank-item.me { background: rgba(225, 6, 0, 0.2); border-left-color: var(--f1-red); font-weight: bold; }
        .rank-item .pos { color: var(--f1-red); font-weight: bold; width: 30px; }
        .rank-item .name { flex-grow: 1; margin-left: 10px; text-transform: uppercase; text-align: left; }
        .rank-item .pts { color: #aaa; font-size: 12px; }
        .spacer { text-align: center; color: #444; letter-spacing: 5px; margin: 5px 0; }

        /* Alertas */
        .alert { max-width: 1200px; margin: 0 auto 15px auto; padding: 15px; border-radius: 8px; text-align: center; font-weight: bold; }
        .alert-success { background: #28a745; color: white; border: 2px solid #1e7e34; }
        .alert-error { background: #dc3545; color: white; border: 2px solid #bd2130; }
    </style>
</head>
<body>

    @if(session('success')) <div class="alert alert-success">🏁 {{ session('success') }}</div> @endif
    @if(session('error')) <div class="alert alert-error">⚠️ {{ session('error') }}</div> @endif

    <div class="paddock">
        <div class="col profile">
            <div class="avatar-container">
                <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://www.formula1.com/content/dam/fom-website/drivers/M/MAXVER01_Max_Verstappen/maxver01.png' }}" class="avatar">
            </div>

            <h2 style="color: var(--f1-red); margin: 5px 0;">{{ Auth::user()->name }}</h2>
            <p style="font-size: 11px; color: #aaa; letter-spacing: 2px;">PILOTO OFICIAL DE ACADEMIA</p>

            <div class="xp-bar"><div class="xp-fill"></div></div>
            <p style="font-weight: bold;">LEVEL {{ Auth::user()->level }} | {{ Auth::user()->xp }} XP</p>

            <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" style="background:rgba(255,255,255,0.05); padding:10px; border-radius:5px; width:90%;">
                @csrf
                <label style="display:block; font-size:10px; margin-bottom:5px;">Trocar Capacete (Foto):</label>
                <input type="file" name="photo" required style="font-size: 10px; width:100%;">
                <button type="submit" class="btn" style="width:100%; margin-top:10px; padding:5px; font-size:10px;">ATUALIZAR</button>
            </form>

            @php
                $done = DB::table('user_quiz_progress')->where('user_id', Auth::id())->where('completed', true)->pluck('quiz_id')->toArray();
                $totalConcluidos = count($done);
            @endphp
            <div class="trophy-case">
                <div class="trophy-item" title="Ouro: Campeão (5 GPs)">
                    <span class="trophy-icon" style="color: gold;">🏆</span>
                    <span class="trophy-count">{{ $totalConcluidos >= 5 ? 1 : 0 }}</span>
                </div>
                <div class="trophy-item" title="Prata: Pro (3 GPs)">
                    <span class="trophy-icon" style="color: #C0C0C0;">🏆</span>
                    <span class="trophy-count">{{ $totalConcluidos >= 3 ? 1 : 0 }}</span>
                </div>
                <div class="trophy-item" title="Bronze: Estreante (1 GP)">
                    <span class="trophy-icon" style="color: #CD7F32;">🏆</span>
                    <span class="trophy-count">{{ $totalConcluidos >= 1 ? 1 : 0 }}</span>
                </div>
            </div>

            <form action="/logout" method="POST" style="margin-top: auto; padding-bottom: 20px; width: 100%;">
                @csrf 
                <button class="btn" style="background: transparent; border: 1px solid #555; width: 100%; color: #888;">ABANDONAR GP</button>
            </form>
        </div>

        <div class="col quizzes">
            <h3 style="text-align: center; border-bottom: 2px solid var(--f1-red); padding-bottom: 10px; margin-top: 0;">CALENDÁRIO DA TEMPORADA</h3>
            @php
                $gps = [
                    1 => 'GP DE PYTHON', 
                    2 => 'GP DE JAVASCRIPT', 
                    3 => 'GP DE SQL (BANCO DE DADOS)', 
                    4 => 'GP DE PHP', 
                    5 => 'GP DE BACKEND FINAL'
                ];
            @endphp

            @foreach($gps as $id => $nome)
                @php 
                    $liberado = ($id == 1 || in_array($id - 1, $done)); 
                    $venceu = in_array($id, $done); 
                @endphp
                <div class="quiz-item {{ $venceu ? 'completed' : ($liberado ? 'unlocked' : '') }}">
                    <div>
                        <span style="display: block; font-size: 10px; color: #888;">ROUND {{ $id }}</span>
                        <span style="font-weight: bold;">{{ $nome }}</span>
                    </div>
                    @if($venceu) <span style="color: gold; font-weight: bold;">🏆 PODIUM</span>
                    @elseif($liberado) <a href="/quiz/{{ $id }}" class="btn">LARGADA</a>
                    @else <span style="color: #555;">🔒 BLOQUEADO</span> @endif
                </div>
            @endforeach
        </div>

        <div class="col ranking">
            <h3 style="text-align: center; margin-top: 0; color: var(--f1-red);">MUNDIAL DE PILOTOS</h3>
            @php
                $top10 = DB::table('users')->orderByDesc('level')->orderByDesc('xp')->limit(10)->get();
                $minhaPosicao = DB::table('users')->where('level', '>', Auth::user()->level)
                    ->orWhere(function($query) {
                        $query->where('level', Auth::user()->level)->where('xp', '>', Auth::user()->xp);
                    })->count() + 1;
                $estouNoTop10 = $top10->contains('id', Auth::id());
            @endphp

            <div class="leaderboard">
                @foreach($top10 as $index => $piloto)
                    <div class="rank-item {{ $piloto->id == Auth::id() ? 'me' : '' }}">
                        <span class="pos">{{ $index + 1 }}º</span>
                        <span class="name">{{ $piloto->name }}</span>
                        <span class="pts">{{ ($piloto->level - 1) * 100 + $piloto->xp }} pts</span>
                    </div>
                @endforeach

                @if(!$estouNoTop10)
                    <div class="spacer">...</div>
                    <div class="rank-item me">
                        <span class="pos">{{ $minhaPosicao }}º</span>
                        <span class="name">{{ Auth::user()->name }}</span>
                        <span class="pts">{{ (Auth::user()->level - 1) * 100 + Auth::user()->xp }} pts</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>