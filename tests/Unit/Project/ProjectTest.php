<?php

use RWatch\Project\Project;

test('Project can be created with valid absolute path', function (): void {
    $project = new Project(path: '/var/www/projects/my-project', name: 'my-project');

    expect($project->getName())->toBe('my-project')
        ->and($project->getPath())->toBe('/var/www/projects/my-project');
});

test('Project can be created with valid home-relative path', function (): void {
    $project = new Project(path: '~/projects/my-project', name: 'my-project');

    expect($project->getName())->toBe('my-project')
        ->and($project->getPath())->toBe('~/projects/my-project');
});

test('Project throws exception for invalid path', function (): void {
    expect(fn() => new Project(path: 'invalid/path', name: 'my-project'))
        ->toThrow(InvalidArgumentException::class);
});

test('Project name is inferred from path if not provided', function (): void {
    $project = new Project(path: '~/projects/my-project');

    expect($project->getName())->toBe('my-project');
});
