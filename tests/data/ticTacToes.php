<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Tic-Tac-Toe :: Test Scenarios
    |--------------------------------------------------------------------------
    |
    | Next move always performed by player 2 at position P, where P is the
    | number value in each key preceded by the underscore sign.
    |
    | Example: R1_2 will place a circle at position 2 (third column, first row)
    |
    */

    'R1_0' => [
        0,2,2,
        0,1,1,
        0,0,0,
    ],

    'R2_3' => [
        0,1,1,
        0,2,2,
        0,0,0,
    ],

    'R3_6' => [
        0,0,0,
        0,1,1,
        0,2,2,
    ],

    'C1_6' => [
        2,1,0,
        2,1,0,
        0,0,0,
    ],

    'C2_7' => [
        1,2,0,
        1,2,0,
        0,0,0,
    ],

    'C3_8' => [
        0,1,2,
        0,1,2,
        0,0,0,
    ],

    'D1_4' => [
        2,1,0,
        0,0,0,
        0,1,2,
    ],

    'D2_2' => [
        1,1,0,
        0,2,0,
        2,0,0,
    ],
];