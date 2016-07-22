<?php

/**
 * @link https://www.reddit.com/r/dailyprogrammer/comments/4tetif/20160718_challenge_276_easy_recktangles/
 */

function take($count, Generator $iterable) {
    if ($count < 1 || ! $iterable->valid()) return [];
    $current = $iterable->current();
    $iterable->next();
    return array_merge([$current], take(--$count, $iterable));
}

function wordGen($word) {
    $word = str_split($word);
    while (true) {
        foreach($word as $char) yield $char;
        foreach(array_slice(array_reverse($word), 1, -1) as $char) yield $char;
    }
}

function getSize($word, $length) {
    return strlen($word) + (($length - 1) * (strlen($word) - 1));
}

function getPos($word, $index) {
    return max(0, $index * (strlen($word) - 1));
}

function insertRow($matrix, $row, $word) {
    $matrix[$row] = $word;
    return $matrix;
}

function insertCol($matrix, $col, $word) {
    return array_map(function ($row, $letter) use ($col) {
        $row[$col] = $letter;
        return $row;
    }, $matrix, $word);
}

function insertSection(callable $insert, $word, $amount, $size, $matrix) {
    foreach(range(0, $amount) as $index) {
        $matrix = $insert($matrix, getPos($word, $index), take(getSize($word, $size), wordGen($word)));
        $word = strrev($word);
    }
    return $matrix;
}

function buildRektangle($word, $x, $y) {
    $matrix = array_fill(0, getSize($word, $y), array_fill(0, getSize($word, $x), " "));
    $matrix = insertSection('insertCol', $word, $x, $y, $matrix);
    $matrix = insertSection('insertRow', $word, $y, $x, $matrix);
    return $matrix;
}

function printRektangle($rektangle) {
    foreach ($rektangle as $row) {
        foreach($row as $cell) {
            echo $cell . " ";
        }
        echo PHP_EOL;
    }
}

printRektangle(buildRektangle(...explode(' ', file_get_contents('php://stdin'), 4)));
