<?php

/**
 * @link https://www.reddit.com/r/dailyprogrammer/comments/4savyr/20160711_challenge_275_easy_splurthian_chemistry/
 */

function getSymbols($name) {
    if (empty($name)) return [];
    $chars = str_split($name);
    $first = array_shift($chars);
    $sym = getSymbols(implode('', $chars));
    $gen = function($a) use($first) {return ucfirst(strtolower($first . $a));};
    return array_merge([$first], array_map($gen, $sym), $sym);
}

function getSymbolsOfSize($name, $size = null) {
    $filter = function($a) use ($size) { return strlen($a) === $size; };
    return $size ? array_filter(getSymbols($name), $filter) : getSymbols($name);
}

function symbolValid($name, $symbol, $size = null) {
    return in_array($symbol, getSymbolsOfSize($name, $size));
}

function firstSymbol($name, $size = null) {
    $sym = getSymbolsOfSize($name, $size);
    sort($sym);
    return array_shift($sym);
}

function distinct($name, $size = null) {
    return count(array_unique(getSymbolsOfSize($name, $size)));
}

var_dump(
    symbolValid("Spenglerium", "Ee", 2),
    symbolValid("Zeddemorium", "Zr", 2),
    symbolValid("Venkmine", "Kn", 2),
    symbolValid("Stantzon", "Zt", 2),
    symbolValid("Melintzum", "Nn", 2),
    symbolValid("Tullium", "Ty", 2),
    firstSymbol("Gozerium", 2),
    firstSymbol("Slimyrine", 2),
    distinct("Zuulon", 2),
    distinct("Zuulon")
);
