<?php
namespace Acme\DemoBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper;

class ProjectCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('demo:project')
            ->setDescription('Saluez quelqu\'un')
             ->addArgument(
                'FirstName',
                InputArgument::OPTIONAL,
                'Prenom à saluer?'
            )
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Qui voulez vous saluez?'
            )
            ->addOption(
               'yell',
               null,
               InputOption::VALUE_NONE,
               'Si défini, la réponse est affichée en majuscules'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $FirstName = $input->getArgument('FirstName');
        if ($name) {
        	if ($FirstName){
        		$text = 'Salut, '.$name.' '.$FirstName;	
        	}
        } else {
            $text = 'Salut';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }
		
        $dialog = $this->getHelperSet()->get('dialog');
		$colors = array('rouge', 'bleu', 'jaune');
		
		$color = $dialog->select(
		    $output,
		    'Svp choisissez une couleur (par défaut rouge)',
		    $colors,
		    0
		);
		
		switch ($color) {
			 case 0:
       		  $output->writeln(sprintf('<error>%s</error>',$text));
       		 	break;
			 case 1 :
			 $output->writeln(sprintf('<question>%s</question>',$text));
       		 	break;
       		 case 2 :
			 $output->writeln(sprintf('<comment>%s</comment>',$text));
       		 	break;	
		}
       
    }
}