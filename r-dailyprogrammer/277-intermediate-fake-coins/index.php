<?php

function compose($f1, $f2) {
    return function() use ($f1, $f2) { return $f2($f1(...func_get_args())); };
}

function explodeLine($line) {
    return explode(' ', $line, 3);
}

function splitLeftRight($statement) {
    return [str_split($statement[0]), str_split($statement[1]), $statement[2]];
}

function formatResults(array $results) {
    switch (count($results)) {
        case 1: return array_pop($results) . ' is lighter' . PHP_EOL;
        default: return 'no fake coins detected' . PHP_EOL;
    }
}

function sortEqualsLast($statements, $statement) {
    $addMethod = $statement[2] === 'equal' ? 'array_push' : 'array_unshift';
    $addMethod($statements, $statement);
    return $statements;
}

function analyzeStatements($carry, $statement) {
    list($all, $real, $maybeFake) = $carry;
    list($left, $right, $balance) = $statement;

    if ($balance === 'left') {
        $real = array_merge($real, $left);
        $maybeFake = array_merge($maybeFake, $right);
    } elseif ($balance === 'right') {
        $real = array_merge($real, $right);
        $maybeFake = array_merge($maybeFake, $left);
    } else {
        $real = array_merge($real, $right, $left);
    }

    $all = array_merge($all, $right, $left);
    return array_map('array_unique', [$all, $real, $maybeFake]);
}

function processAnalises($analysis) {
    list($all, $real, $maybeFake) = $analysis;

    // If all "maybe fakes" have been proven real then there are inconsistencies
    return ( ! empty($maybeFake) && count(array_diff($maybeFake, $real)) === 0)
        ? 'data is inconsistent' . PHP_EOL
        : formatResults(array_diff($all, $real));
}

echo processAnalises(
    array_reduce(
        array_reduce(
            array_map(
                compose('explodeLine', 'splitLeftRight'),
                explode(PHP_EOL, file_get_contents('php://stdin'))
            ),
            'sortEqualsLast', []
        ),
        'analyzeStatements', [[], [], []]
    )
);
