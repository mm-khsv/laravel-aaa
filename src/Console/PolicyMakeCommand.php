<?php

namespace dnj\AAA\Console;

use Illuminate\Foundation\Console\PolicyMakeCommand as ParentPolicyMakeCommand;

class PolicyMakeCommand extends ParentPolicyMakeCommand
{
    /**
     * The name of the console command.
     *
     * This name is used to identify the command during lazy loading.
     *
     * @var string|null
     *
     * @deprecated
     */
    protected static $defaultName = 'make:policy:aaa';
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:policy:aaa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new policy class for dnj\AAA';

    /**
     * Resolve the fully-qualified path to the stub.
     *
     * @param string $stub
     *
     * @return string
     */
    protected function resolveStubPath($stub)
    {
        $customPath = $this->laravel->basePath(trim($stub, '/'));

        return file_exists($customPath) ? $customPath : __DIR__.$stub;
    }
}
