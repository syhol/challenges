<?php

/**
 * @link https://www.reddit.com/r/dailyprogrammer/comments/4so25w/20160713_challenge_275_intermediate_splurthian/
 */

function getSymbols($name) {
    if (empty($name)) return [];
    $chars = str_split($name);
    $first = array_shift($chars);
    $gen = function($a) use($first) {return ucfirst(strtolower($first . $a));};
    return array_merge(array_map($gen, $chars), getSymbols(implode('', $chars)));
}

function getValidSymbol($name, $registry) {
    return array_merge($registry, [
        $name => array_shift(array_diff(getSymbols($name), array_values($registry)))
    ]);
}

function generateSymbolRegistry($elements, $registry = []) {
    if (empty($elements)) return $registry;
    $element = array_shift($elements);
    return generateSymbolRegistry($elements, getValidSymbol($element, $registry));
}

function getFirstEmptyElement($registry) {
    return array_shift(array_keys(array_filter($registry, function ($v) { return !$v; })));
}

# Main Challenge
$elements = explode(PHP_EOL, file_get_contents('elements.txt'));
$registry = generateSymbolRegistry($elements);
echo getFirstEmptyElement($registry) . PHP_EOL;

# Bonus
while ($empty = getFirstEmptyElement($registry)) {
    unset($elements[array_search($empty, $elements)]);
    array_unshift($elements, $empty);
    $registry = generateSymbolRegistry($elements);
}
var_dump($registry);
