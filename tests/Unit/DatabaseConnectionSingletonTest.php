<?php

use App\Patterns\Singleton\DatabaseConnectionSingleton;

test('database connection singleton returns the same shared instance', function () {
    $first = DatabaseConnectionSingleton::getInstance();
    $second = DatabaseConnectionSingleton::getInstance();

    expect($first)->toBe($second);
});

test('database connection singleton exposes one shared pdo connection', function () {
    $firstConnection = DatabaseConnectionSingleton::getInstance()->getConnection();
    $secondConnection = DatabaseConnectionSingleton::getInstance()->getConnection();

    expect($firstConnection)->toBeInstanceOf(\PDO::class);
    expect($firstConnection)->toBe($secondConnection);
});
