<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

use Halstack\Console\Command\Hello;
use Halstack\Console\Command\TwigCache;

$console = new Application('Metrics collector', $app['version']);
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('my-command')
    ->setDefinition(array(
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('My command description')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        // do something
    })
;

$twigCacheCommand = new TwigCache();
$twigCacheCommand->setBasePath($app['twig.options']['cache']);

$console->add($twigCacheCommand);
$console->add(new Hello());

return $console;
