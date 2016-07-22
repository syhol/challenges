<?php

function buildTuple($element, $key) {
    return [$element, $key];
}

function groupByFirst($carry, $tuple) {
    $key = array_pop($tuple);
    $carry[$key] = array_merge(isset($carry[$key]) ? $carry[$key] : [], [array_pop($tuple)]);
    return $carry;
}

function keyFunction(array $elements, array $key, callable $applyFunction) {
    return array_map($applyFunction, array_reduce(array_map('buildTuple', $elements, $key), 'groupByFirst', []));
}

function histogram(array $elements) {
    return keyFunction($elements, $elements, 'count');
}

function groupedSumOfField(array $tuples) {
    return keyFunction(array_map('array_pop', $tuples), array_map('array_shift', $tuples), 'array_sum');
}

function nub(array $tuples) {
    return keyFunction(array_map('array_pop', $tuples), array_map('array_shift', $tuples), 'array_shift');
}

// Function signature example
var_dump(keyFunction([3, 4, 5, 6], [2, 0, 1, 2], 'array_sum'));

// Histogram
$input = [5, 3, 5, 2, 2, 9, 7, 0, 7, 5, 9, 2, 9 /* ... */];
var_dump(histogram($input));

// Grouped sum of field
$input = [['a', 14], ['b', 21], ['c', 82], ['d', 85] /* ... */];
var_dump(groupedSumOfField($input));

// Nub
$input = [['a', 14], ['b', 21], ['c', 82], ['d', 85] /* ... */];
var_dump(nub($input));
