<?php

/**
 * @link https://www.reddit.com/r/programmingchallenges/comments/4pkc3m/can_you_solve_this_wanting_to_compare_to_my/ 
 */

class Node {
    public $value = 0;
    public $children = [];
    function __construct($value, $children) {$this->value = $value; $this->children = $children; }
}

function getMax(Node $current) {
    # Find all max candidates by getting the max of all child nodes
    $maxCandidates = [$currentMax = $current];
    foreach($current->children as $child) {
        $maxCandidates[] = getMax($child);
    }

    # Using each candidate, find the one with the largest average
    $maxAverage = 0;
    foreach ($maxCandidates as $maxCandidate) {
        $candidateGroup = new SplObjectStorage;
        $candidateGroup->attach($maxCandidate);
        $checking = true;

        # Flatten all nodes in candidate into flat list
        while ($checking) {
            $checking = false;
            foreach ($candidateGroup as $candidateNode) {
                foreach ($candidateNode->children as $child) {
                    if (!$candidateGroup->contains($child)) {
                        $candidateGroup->attach($child);
                        $checking = true;
                    }
                }
            }
        }

        # Generate average for candidate
        $candidateAverage = 0;
        foreach ($candidateGroup as $candidateNode) {
            $candidateAverage += $candidateNode->value;
        }
        $candidateAverage = $candidateAverage / count($candidateGroup);

        # Check the candidate max against the current max
        if ($candidateAverage > $maxAverage) {
            $currentMax = $maxCandidate;
            $maxAverage = $candidateAverage;
        }
    }

    return $currentMax;
}

$node = new Node(8, [
    new Node(15, []),
    new Node(2, [
        new Node(7, []), new Node(9, [])
    ]), new Node(9, [
        new Node(8, [])
    ])
]);

var_dump(getMax($node));
