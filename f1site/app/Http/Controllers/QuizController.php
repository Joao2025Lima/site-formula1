<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizController extends Controller
{
    private $quizzes = [
        1 => [
            'title' => 'GP DE PYTHON',
            'questions' => [
                ['q' => 'Como imprimir no console?', 'options' => ['echo', 'print()', 'log', 'printf'], 'ans' => 1],
                ['q' => 'Símbolo de comentário?', 'options' => ['//', '/*', '#', '--'], 'ans' => 2],
                ['q' => 'Tipo de dado de "10"?', 'options' => ['int', 'str', 'float', 'bool'], 'ans' => 1],
                ['q' => 'Como criar uma lista?', 'options' => ['[]', '{}', '()', '<>'], 'ans' => 0],
                ['q' => 'Operador de potência?', 'options' => ['^', '**', 'pow', '&&'], 'ans' => 1],
            ]
        ],
        2 => [
            'title' => 'GP DE JAVASCRIPT',
            'questions' => [
                ['q' => 'Como selecionar um ID?', 'options' => ['getElement', 'querySelector', 'getElementById', 'find'], 'ans' => 2],
                ['q' => 'Declaração constante?', 'options' => ['var', 'let', 'const', 'static'], 'ans' => 2],
                ['q' => 'Exibir alerta?', 'options' => ['msg()', 'alert()', 'popup()', 'print()'], 'ans' => 1],
                ['q' => 'Evento de clique?', 'options' => ['onpress', 'onclick', 'onhover', 'change'], 'ans' => 1],
                ['q' => 'Tipo de [ ]?', 'options' => ['Object', 'Array', 'String', 'Number'], 'ans' => 1],
            ]
        ],
        3 => [
            'title' => 'GP DE BANCO DE DADOS',
            'questions' => [
                ['q' => 'Comando para ler dados?', 'options' => ['GET', 'READ', 'SELECT', 'EXTRACT'], 'ans' => 2],
                ['q' => 'Chave única de uma tabela?', 'options' => ['Foreign Key', 'Primary Key', 'Unique Key', 'Master Key'], 'ans' => 1],
                ['q' => 'Comando para inserir?', 'options' => ['ADD', 'SET', 'INSERT INTO', 'PUT'], 'ans' => 2],
                ['q' => 'Significado de SQL?', 'options' => ['Simple Query Lang', 'Structured Query Lang', 'System Query Lang', 'Strong Query Lang'], 'ans' => 1],
                ['q' => 'Para deletar tudo de uma tabela?', 'options' => ['REMOVE', 'DELETE', 'TRUNCATE', 'DROP'], 'ans' => 2],
            ]
        ],
        4 => [
            'title' => 'GP DE PHP',
            'questions' => [
                ['q' => 'Como começar um script PHP?', 'options' => ['<script>', '<?php', '<?', '<php'], 'ans' => 1],
                ['q' => 'Como declarar variável?', 'options' => ['var', 'let', '$', 'v'], 'ans' => 2],
                ['q' => 'Finalizador de comandos?', 'options' => ['.', ':', ';', ','], 'ans' => 2],
                ['q' => 'Concatenar strings?', 'options' => ['+', '.', '&', ','], 'ans' => 1],
                ['q' => 'Superglobal de formulário?', 'options' => ['$_GET', '$_POST', '$_REQUEST', 'Todas'], 'ans' => 3],
            ]
        ],
        5 => [
            'title' => 'GP DE BACKEND FINAL',
            'questions' => [
                ['q' => 'O que é uma API?', 'options' => ['Interface de App', 'Linguagem', 'Banco de Dados', 'Protocolo'], 'ans' => 0],
                ['q' => 'Verbo para criar dados?', 'options' => ['GET', 'POST', 'PUT', 'DELETE'], 'ans' => 1],
                ['q' => 'Status de sucesso (OK)?', 'options' => ['404', '500', '200', '301'], 'ans' => 2],
                ['q' => 'O que é JSON?', 'options' => ['Linguagem', 'Formato de Dados', 'Servidor', 'Script'], 'ans' => 1],
                ['q' => 'Onde o Backend roda?', 'options' => ['Navegador', 'Servidor', 'Celular', 'Monitor'], 'ans' => 1],
            ]
        ]
    ];

    public function show($id) {
        if (!isset($this->quizzes[$id])) return redirect('/dashboard');
        
        if ($id > 1) {
            $prev = DB::table('user_quiz_progress')
                ->where('user_id', Auth::id())
                ->where('quiz_id', $id-1)
                ->where('completed', true)
                ->first();
            if (!$prev) return redirect('/dashboard')->with('error', '🔒 Vença o GP anterior primeiro!');
        }
        return view('quiz.show', ['quiz' => $this->quizzes[$id], 'id' => $id]);
    }

    public function finish(Request $request, $id) {
        if (!isset($this->quizzes[$id])) return redirect('/dashboard');

        $acertos = 0;
        $quiz = $this->quizzes[$id];
        foreach ($quiz['questions'] as $i => $q) {
            if ($request->input('q'.$i) == $q['ans']) $acertos++;
        }

        if ($acertos >= 4) {
            DB::table('user_quiz_progress')->updateOrInsert(
                ['user_id' => Auth::id(), 'quiz_id' => $id],
                ['completed' => true, 'score' => $acertos, 'updated_at' => now()]
            );

            $user = Auth::user();
            $user->xp += 20;
            if ($user->xp >= 100) { 
                $user->level += 1; 
                $user->xp = 0; 
            }
            $user->save();

            return redirect('/dashboard')->with('success', "🏁 PÓDIO! Você acertou $acertos de 5. Próximo GP liberado!");
        }
        return redirect('/dashboard')->with('error', "🏎️ PIT STOP! Você acertou apenas $acertos de 5. Precisa de 4.");
    }
}