<?php

declare(strict_types=1);

namespace Dwatch\Project;

interface ProjectsProviderInterface
{
    /**
     * Get the list of projects
     *
     * @return Project[]
     */
    public function getProjects(): array;
}