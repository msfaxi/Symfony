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
use Ugap\Bundle\SkeletonBundle\Generator\ProjectGenerator;

class ProjectCommand extends GeneratorCommand
{
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputOption('project-name', '', InputOption::VALUE_REQUIRED, 'The namespace of the project to create'),
                new InputOption('dir', '', InputOption::VALUE_REQUIRED, 'The directory where to create the project'),
                new InputOption('format', '', InputOption::VALUE_REQUIRED, 'Use the format for configuration files (php, xml, yml, or annotation)'),
                new InputOption('structure', '', InputOption::VALUE_REQUIRED, 'Whether to generate the whole directory structure'),
            ))
            ->setDescription('Generates a project')
            ->setHelp(<<<EOT
The <info>skeleton:project</info> command helps you generates new project.
EOT
            )
            ->setName('skeleton:project')
        ;
    }
    
 protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();

        if ($input->isInteractive()) {
            if (!$dialog->askConfirmation($output, $dialog->getQuestion('Do you confirm generation', 'yes', '?'), true)) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }

        foreach (array('project-name', 'dir') as $option) {
            if (null === $input->getOption($option)) {
                throw new \RuntimeException(sprintf('The "%s" option must be provided.', $option));
            }
        }

        $projectname = Validators::validateProjectName($input->getOption('project-name'));
        $dir = Validators::validateTargetDir($input->getOption('dir'));
        if (null === $input->getOption('format')) {
            $input->setOption('format', 'annotation');
        }
        $format = Validators::validateFormat($input->getOption('format'));
        $structure = $input->getOption('structure');

        $dialog->writeSection($output, 'Project generation');

        if (!$this->getContainer()->get('filesystem')->isAbsolutePath($dir)) {
            $dir = getcwd().'/'.$dir;
        }

        $generator = $this->getGenerator();
        $generator->generate($projectname, $dir, $format, $structure);

        $output->writeln('Generating the project code: <info>OK</info>');

        $errors = array();
        $runner = $dialog->getRunner($output, $errors);

        $dialog->writeGeneratorSummary($output, $errors);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $dialog = $this->getDialogHelper();
        $dialog->writeSection($output, 'Welcome to the UGAP project generator');

        // namespace
        $projectname = null;
        try {
            $projectname = $input->getOption('project-name') ? Validators::validateProjectName($input->getOption('project-name')) : null;
        } catch (\Exception $error) {
            $output->writeln($dialog->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

        if (null === $projectname) {
            $output->writeln(array(
                '',
                '',
            ));

            $projectname = $dialog->askAndValidate($output, $dialog->getQuestion('project name', $input->getOption('project-name')), array('Ugap\Bundle\SkeletonBundle\Command\Validators', 'validateProjectName'), false, $input->getOption('namespace'));
            $input->setOption('project-name', $projectname);
        }


     
        // target dir
        $dir = null;
        try {
            $dir = $input->getOption('dir') ? Validators::validateTargetDir($input->getOption('dir')) : null;
        } catch (\Exception $error) {
            $output->writeln($dialog->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

           // format
        $format = null;
        try {
            $format = $input->getOption('format') ? Validators::validateFormat($input->getOption('format')) : null;
        } catch (\Exception $error) {
            $output->writeln($dialog->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

        if (null === $format) {
            $output->writeln(array(
                '',
                'Determine the format to use for the generated configuration.',
                '',
            ));
            $format = $dialog->askAndValidate($output, $dialog->getQuestion('Configuration format (yml, xml, php, or annotation)', $input->getOption('format')), array('Ugap\Bundle\SkeletonBundle\Command\Validators', 'validateProjectName'), false, $input->getOption('format'));
            $input->setOption('format', $format);
        }

        // optional files to generate
        $output->writeln(array(
            '',
            'To help you get started faster, the command can generate some',
            'code snippets for you.',
            '',
        ));

        $structure = $input->getOption('structure');
        if (!$structure && $dialog->askConfirmation($output, $dialog->getQuestion('Do you want to generate the whole directory structure', 'no', '?'), false)) {
            $structure = true;
        }
        $input->setOption('structure', $structure);

        // summary
        $output->writeln(array(
            '',
            $this->getHelper('formatter')->formatBlock('Summary before generation', 'bg=blue;fg=white', true),
            '',
            sprintf("You are going to generate a \"<info>%s\\%s</info>\" /\" using the \"<info>%s</info>\" format.", $projectname,$dir, $format),
            '',
        ));
    }


    protected function createGenerator()
    {
        return new ProjectGenerator($this->getContainer()->get('filesystem'));
    }
}
    