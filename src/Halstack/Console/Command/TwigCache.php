<?php
namespace Halstack\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TwigCache extends Command
{
    private $basePath = null;

    protected function configure()
    {
        $this
            ->setName('cache:clear')
            ->setDescription('Delete twig cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("<info>Clearing '%s/*'</info>", $this->basePath));
        foreach(glob($this->basePath."/??") as $dir) {
          $this->rrmdir($dir);
        }
    }

    public function setBasePath($basePath)
    {
      $this->basePath = $basePath;
    }

    private function rrmdir($dir)
    {
      if (is_dir($dir)) {
           $objects = scandir($dir);
           foreach ($objects as $object) {
             if ($object != "." && $object != "..") {
               if (is_dir($dir."/".$object))
                 $this->rrmdir($dir."/".$object);
               else
                 unlink($dir."/".$object);
             }
           }
         rmdir($dir);
      }
    }
}
