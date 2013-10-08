<?
namespace CoolSpots\ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CoolSpotsCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        parent::configure();

        $this
                ->setName('coolspots:update')
				->setDescription('Update instagram\'s photos')
//                ->addArgument('call', InputArgument::REQUIRED, 'Call')              
//                ->addOption('user', 'u', InputOption::VALUE_OPTIONAL, 'Username', '')
//                ->addOption('passwd', 'p', InputOption::VALUE_OPTIONAL, 'Password', '')

        ;
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return integer 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract class is not implemented
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
		
		$output->writeln('Demo');
		return(0);
    }
}
?>