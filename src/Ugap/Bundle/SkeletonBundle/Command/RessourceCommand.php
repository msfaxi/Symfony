<?php

/**
 * 
 * @author MSFAXI
 *
 */
namespace Ugap\Bundle\SkeletonBundle\Command;

use Sensio\Bundle\GeneratorBundle\Command\GeneratorCommand;
use Ugap\Bundle\SkeletonBundle\Command\Validators;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper;
use Ugap\Bundle\SkeletonBundle\Generator\Generator;
use Ugap\Bundle\SkeletonBundle\Generator\RessourceGenerator;

class RessourceCommand extends GeneratorCommand
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('ressource-name', '', InputOption::VALUE_REQUIRED, 'The namespace of the project to create'),
				new InputOption('dir', '', InputOption::VALUE_REQUIRED, 'The directory of your project'),
            	new InputOption('project-name', '', InputOption::VALUE_REQUIRED, 'name of your project'),            		
            ))
            ->setDescription('Generates a ressource')
            ->setHelp(<<<EOT
The <info>skeleton:ressource</info> command helps you generates new ressource.
EOT
            )
            ->setName('skeleton:ressource')
        ;
    }
    
 protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();

        foreach (array('ressource-name') as $option) {
            if (null === $input->getOption($option)) {
                throw new \RuntimeException(sprintf('The "%s" option must be provided.', $option));
            }
        }

        $ressourcename = Validators::validateProjectName($input->getOption('ressource-name'));
        $dir = $input->getOption('dir');
        $projectname = $input->getOption('project-name');
        $dialog->writeSection($output, 'Ressource generation');

        $generator = $this->getGenerator();
        $generator->generate($ressourcename, $dir, $projectname);

        $output->writeln('Generating ressource code: <info>OK</info>');

        $errors = array();
        $runner = $dialog->getRunner($output, $errors);

        $dialog->writeGeneratorSummary($output, $errors);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($output, 'Welcome to the UGAP ressource generator');

        // namespace
        $ressourcename = null;
        try {
            $ressourcename = $input->getOption('ressource-name') ? Validators::validateProjectName($input->getOption('ressource-name')) : null;
        } catch (\Exception $error) {
            $output->writeln($dialog->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

        // summary
        $output->writeln(array(
            '',
            $this->getHelper('formatter')->formatBlock('Summary before generation', 'bg=blue;fg=white', true),
            '',
            sprintf("You are going to generate a \"<info>%s</info>\" ressource", $ressourcename),
            '',
        ));
    }


    protected function createGenerator()
    {
        return new RessourceGenerator($this->getContainer()->get('filesystem'));
    }
}
    