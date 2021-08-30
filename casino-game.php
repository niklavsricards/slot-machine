<?php

$board = [
    [],
    [],
    [],
];
$rows = 3;
$columns = 4;

$payout = [
    'A' => 30,
    'B' => 20,
    'X' => 10
];

$bets = [
    1 => 10,
    2 => 20,
    3 => 40,
    4 => 80
];

$symbols = array_keys($payout);

$conditions = [
    [
        [0, 0], [0, 1], [0, 2], [0, 3]
    ],
    [
        [1, 0], [1, 1], [1, 2], [1, 3]
    ],
    [
        [2, 0], [2, 1], [2, 2], [2, 3]
    ],
    [
        [0, 0], [0, 1], [1, 2], [2, 3]
    ],
    [
        [0, 3], [0, 2], [1, 1], [2, 0]
    ],
    [
        [2, 0], [2, 1], [1, 2], [0, 3]
    ],
    [
        [2, 3], [2, 2], [1, 1], [0, 0]
    ],
];

function displayGame(int $rows, int $columns, array $symbols, array &$board): array {
    for ($r = 0; $r < $rows; $r++) {
        for ($c = 0; $c < $columns; $c++) {
            $board[$r][$c] = $symbols[array_rand($symbols)];
        }
    }

    foreach ($board as $row) {
        foreach ($row as $symbol) {
            echo $symbol . ' ';
        }
        echo PHP_EOL;
    }
    return $board;
}

function checkWinningConditions(array $conditions, array &$board, array $payout): int {
    $totalWin = 0;
    foreach ($conditions as $condition) {
        $x = [];

        foreach ($condition as $positions) {
            $row = $positions[0];
            $column = $positions[1];

            $x[] = $board[$row][$column];

        }
        if (count(array_unique($x)) == 1) {
            $totalWin += $payout[$x[0]];
        }
    }
    return $totalWin;
}

function showBets(array $bets): void {
    echo "Available bets:" . PHP_EOL;
    echo "Coefficient -> Price" . PHP_EOL;

    foreach ($bets as $key => $bet) {
        echo "{$key} -> {$bet}" . PHP_EOL;
    }
}

$playerMoney = (int) readline("Enter your starting money: ");
$game = true;
showBets($bets);

while ($game) {
    $selectedBet = (int) readline("Please choose your bet size: ");
    if (!isset($bets[$selectedBet])) {
        echo "Invalid bet size picked" . PHP_EOL;
        continue;
    }

    displayGame($rows, $columns, $symbols, $board);

    $win = checkWinningConditions($conditions, $board, $payout) * $selectedBet;
    if ($win > 0) {
        echo "You won {$win}" . PHP_EOL;
    } else {
        echo "No win!" . PHP_EOL;
    }
    $playerMoney = $playerMoney - $bets[$selectedBet] + $win;

    echo "Your current balance = {$playerMoney}" . PHP_EOL;

    if ($playerMoney === 0) {
        $game = false;
    }

    $input = readline("Do you want to continue? y/n ");
    if ($input === "n") {
        $game = false;
        exit("Thank you for playing!");
    }
}
