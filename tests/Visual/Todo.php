<?php

use Symfony\Component\Process\Process;

$run = function (string $target, bool $parallel) {
    $process = new Process(['php', 'bin/pest', $target, $parallel ? '--parallel' : '', '--colors=always'], dirname(__DIR__, 2),
        ['COLLISION_PRINTER' => 'DefaultPrinter', 'COLLISION_IGNORE_DURATION' => 'true'],
    );

    $process->run();

    expect($process->getExitCode())->toBe(0);

    $output = $process->getOutput();

    return preg_replace('/Duration: \d+\.\d+s/', 'Duration: x.xxs', removeAnsiEscapeSequences($output));
};

test('todos', function () use ($run) {
    expect($run('--todos', false))->toMatchSnapshot();
})->skipOnWindows();

test('todos in parallel', function () use ($run) {
    expect($run('--todos', true))->toMatchSnapshot();
})->skipOnWindows();

test('todo', function () use ($run) {
    expect($run('--todo', false))->toMatchSnapshot();
})->skipOnWindows();

test('todo in parallel', function () use ($run) {
    expect($run('--todo', true))->toMatchSnapshot();
})->skipOnWindows();
