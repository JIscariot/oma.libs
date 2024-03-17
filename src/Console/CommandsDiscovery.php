<?php

declare(strict_types=1);

namespace Libs\Console;

use Composer\ClassMapGenerator\ClassMapGenerator;
use Symfony\Component\Console\Application;
use Throwable;

final class CommandsDiscovery
{
    private static ?self $instance = null;

    /**
     * @param string $path Path to commands directory
     * @throws Throwable
     */
    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new CommandsDiscoveryException("Folder does not exist: {$path}");
        }

        $application = new Application();
        $application->addCommands($this->findCommands($path));
        $application->run();
    }

    /**
     * @param string $path Path to commands directory
     * @return array
     */
    private function findCommands(string $path): array
    {
        $commands = [];

        $classMap = ClassMapGenerator::createMap($path);
        foreach ($classMap as $className => $classPath) {
            require_once $classPath;
            $commands[] = new $className;
        }
        return $commands;
    }

    /**
     * @param string $path Path to commands directory
     * @return self
     * @throws Throwable
     */
    public static function load(string $path): self
    {
        if (is_null(self::$instance)) {
            self::$instance = new self($path);
        }
        return self::$instance;
    }
}