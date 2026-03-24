<?php
session_start();

$woordenLijst = [
    "PROGRAMMING",
    "DEVELOPER",
    "KEYBOARD",
    "INTERNET",
    "SOFTWARE",
    "DATABASE",
    "ALGORITHM",
    "VARIABLE",
    "FUNCTION",
    "INTERFACE"
];

if (isset($_POST['action'])) {
    if ($_POST['action'] == 'random') {
        $_SESSION['woord'] = strtoupper($woordenLijst[array_rand($woordenLijst)]);
    } elseif ($_POST['action'] == 'custom' && !empty($_POST['custom_word'])) {
        $_SESSION['woord'] = strtoupper(trim($_POST['custom_word']));
    }
    $_SESSION['geraden'] = [];
    $_SESSION['fouten'] = 0;
    $_SESSION['foute_letters'] = [];
}

if (isset($_GET['reset'])) {
    session_destroy();
    header("Location: galgje.php");
    exit;
}

if (isset($_GET['letter']) && isset($_SESSION['woord'])) {
    $letter = strtoupper($_GET['letter']);
    if (!in_array($letter, $_SESSION['geraden'])) {
        $_SESSION['geraden'][] = $letter;
        if (strpos($_SESSION['woord'], $letter) === false) {
            $_SESSION['fouten']++;
            $_SESSION['foute_letters'][] = $letter;
        }
    }
}

$weergaveWoord = "";
$gewonnen = true;
if (isset($_SESSION['woord'])) {
    foreach (str_split($_SESSION['woord']) as $l) {
        if (in_array($l, $_SESSION['geraden'])) {
            $weergaveWoord .= $l . " ";
        } else {
            $weergaveWoord .= "_ ";
            $gewonnen = false;
        }
    }
    $verloren = ($_SESSION['fouten'] >= 10);
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Galgje</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        @keyframes drawLine {
            from {
                stroke-dashoffset: 200;
            }

            to {
                stroke-dashoffset: 0;
            }
        }

        .draw-anim {
            stroke-dasharray: 200;
            stroke-dashoffset: 200;
            animation: drawLine 0.8s ease-out forwards;
        }

        .dead-eyes {
            opacity: 0;
            animation: fadeIn 0.5s 0.8s forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        .letter-btn {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>

<body class="bg-[#0f172a] min-h-screen flex items-center justify-center p-4">

    <div class="max-w-2xl w-full">
        <?php if (!isset($_SESSION['woord'])) : ?>
            <div class="bg-slate-800/50 backdrop-blur-xl border border-white/10 p-10 rounded-[3rem] shadow-2xl text-center">
                <h1 class="text-6xl font-black text-white mb-4 tracking-tighter italic">GALGJE<span class="text-indigo-500">.</span></h1>
                <form method="post" class="space-y-6">
                    <input type="text" name="custom_word" placeholder="Kies een geheim woord..."
                        class="w-full bg-slate-900/50 border border-slate-700 rounded-2xl py-5 px-8 text-white
                        placeholder-slate-600 focus:outline-none
                        focus:ring-4 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-center text-xl font-bold">
                    <div class="flex flex-col md:flex-row gap-4">
                        <button type="submit" name="action" value="custom"
                            class="flex-1 bg-indigo-600 hover:bg-indigo-500 text-white 
                            font-black py-5 rounded-2xl shadow-xl shadow-indigo-500/30 transition-all active:scale-95 uppercase tracking-widest">
                            Start Game
                        </button>
                        <button type="submit" name="action" value="random"
                            class="flex-1 bg-slate-700 hover:bg-slate-600 text-white 
                            font-black py-5 rounded-2xl 
                            shadow-lg transition-all active:scale-95 
                            border border-slate-600 uppercase tracking-widest">
                            Random Word
                        </button>
                    </div>
                </form>
            </div>
        <?php else : ?>
            <div class="bg-white rounded-[3rem] shadow-[0_20px_60px_-15px_rgba(0,0,0,0.5)] overflow-hidden">
                <div class="p-10 text-center">
                    <div class="bg-indigo-50/50 rounded-[2rem] p-8 mb-10 flex justify-center border border-indigo-100/50 relative">
                        <svg width="180" height="180" viewBox="0 0 150 200">
                            <?php if ($_SESSION['fouten'] >= 1) : ?>
                                <line class="draw-anim" x1="20" y1="180" x2="130" y2="180" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                            <?php endif; ?>
                            <?php if ($_SESSION['fouten'] >= 2) : ?>
                                <line class="draw-anim" x1="40" y1="180" x2="40" y2="20" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                            <?php endif; ?>
                            <?php if ($_SESSION['fouten'] >= 3) : ?>
                                <line class="draw-anim" x1="40" y1="20" x2="120" y2="20" stroke="#1e293b" stroke-width="8" stroke-linecap="round" />
                            <?php endif; ?>
                            <?php if ($_SESSION['fouten'] >= 4) : ?>
                                <line class="draw-anim" x1="40" y1="60" x2="80" y2="20" stroke="#1e293b" stroke-width="6" stroke-linecap="round" />
                            <?php endif; ?>
                            <?php if ($_SESSION['fouten'] >= 5) : ?>
                                <line class="draw-anim" x1="120" y1="20" x2="120" y2="50" stroke="#6366f1" stroke-width="4" />
                            <?php endif; ?>
                            <?php if ($_SESSION['fouten'] >= 6) : ?>
                                <circle class="draw-anim" cx="120" cy="75" r="20" stroke="#1e293b" stroke-width="6" fill="white" />
                            <?php endif; ?>
                            <?php if ($_SESSION['fouten'] >= 7) : ?>
                                <line class="draw-anim" x1="120" y1="95" x2="120" y2="140" stroke="#1e293b" stroke-width="6" stroke-linecap="round" />
                            <?php endif; ?>
                            <?php if ($_SESSION['fouten'] >= 8) : ?>
                                <line class="draw-anim" x1="120" y1="105" x2="95" y2="125" stroke="#1e293b" stroke-width="6" stroke-linecap="round" />
                                <line class="draw-anim" x1="120" y1="105" x2="145" y2="125" stroke="#1e293b" stroke-width="6" stroke-linecap="round" />
                            <?php endif; ?>
                            <?php if ($_SESSION['fouten'] >= 9) : ?>
                                <line class="draw-anim" x1="120" y1="140" x2="100" y2="175" stroke="#1e293b" stroke-width="6" stroke-linecap="round" />
                            <?php endif; ?>
                            <?php if ($_SESSION['fouten'] >= 10) : ?>
                                <line class="draw-anim" x1="120" y1="140" x2="140" y2="175" stroke="#1e293b" stroke-width="6" stroke-linecap="round" />
                                <g class="dead-eyes">
                                    <line x1="113" y1="70" x2="118" y2="75" stroke="#ef4444" stroke-width="2" />
                                    <line x1="118" y1="70" x2="113" y2="75" stroke="#ef4444" stroke-width="2" />
                                    <line x1="122" y1="70" x2="127" y2="75" stroke="#ef4444" stroke-width="2" />
                                    <line x1="127" y1="70" x2="122" y2="75" stroke="#ef4444" stroke-width="2" />
                                </g>
                            <?php endif; ?>
                        </svg>
                    </div>

                    <div class="mb-12">
                        <div class="text-5xl font-black tracking-[0.3em] text-slate-800 mb-6 font-mono break-all">
                            <?php echo $weergaveWoord; ?>
                        </div>
                        <div class="flex justify-center flex-wrap gap-3">
                            <?php foreach ($_SESSION['foute_letters'] as $fout) : ?>
                                <span class="bg-red-50 text-red-500 px-4 py-2 rounded-xl text-sm font-black border border-red-100">
                                    <?php echo $fout; ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if ($gewonnen) : ?>
                        <div class="bg-emerald-50 border-2 border-emerald-100 p-8 rounded-[2rem] mb-6">
                            <h3 class="text-emerald-600 font-black text-4xl mb-2">WAUW! 🎉</h3>
                            <a href="galgje.php?reset=1" class="inline-block mt-4 bg-emerald-600 text-white 
                            px-10 py-4 rounded-2xl font-black hover:bg-emerald-500 uppercase">Next Round</a>
                        </div>
                    <?php elseif ($verloren) : ?>
                        <div class="bg-rose-50 border-2 border-rose-100 p-8 rounded-[2rem] mb-6">
                            <h3 class="text-rose-600 font-black text-4xl mb-2">GAME OVER 💀</h3>
                            <p class="text-rose-400 font-bold mb-4 uppercase tracking-widest text-sm">Het woord was: <?php echo $_SESSION['woord']; ?></p>
                            <a href="galgje.php?reset=1" class="inline-block bg-rose-600 text-white 
                            px-10 py-4 rounded-2xl font-black hover:bg-rose-500 uppercase">Try Again</a>
                        </div>
                    <?php else : ?>
                        <div class="grid grid-cols-7 md:grid-cols-9 gap-3">
                            <?php
                            foreach (range('A', 'Z') as $char) {
                                $isUsed = in_array($char, $_SESSION['geraden']);
                                if ($isUsed) {
                                    $isInWord = strpos($_SESSION['woord'], $char) !== false;
                                    $style = $isInWord ? 'bg-emerald-100 text-emerald-600 border-emerald-200' : 'bg-slate-100 text-slate-300 border-slate-200 opacity-40 cursor-not-allowed';
                                    echo "<span class='h-12 flex items-center justify-center rounded-xl font-black border-2 $style'>$char</span>";
                                } else {
                                    echo "<a href='galgje.php?letter=$char' class='letter-btn h-12 flex items-center justify-center rounded-xl bg-slate-800 text-white font-black hover:bg-indigo-600 border-b-4 border-slate-900'>$char</a>";
                                }
                            }
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>