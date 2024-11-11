<?php

use Dwatch\Project\ProjectsProviderInterface;
use Dwatch\Project\Project;

test('ProjectsProvider returns array of Project objects', function () {
    $provider = new class implements ProjectsProviderInterface {
        public function getProjects(): array {
            return [
                new Project('/path/to/project1', 'project1'),
                new Project('~/project2', 'project2'),
            ];
        }
    };

    $projects = $provider->getProjects();

    expect($projects)
        ->toBeArray()
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Project::class);
});
