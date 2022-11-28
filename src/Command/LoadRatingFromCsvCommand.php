<?php

namespace App\Command;

use App\Entity\Country;
use App\Entity\Rating;
use App\Repository\CountryRepository;
use App\Repository\CriterionRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;

/**
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 */
class LoadRatingFromCsvCommand extends Command
{
    protected static $defaultName = 'app:load-rating-from-csv';

    private $em;
    private $countryRepo;
    private $criterionRepo;

    public function __construct(EntityManagerInterface $em, CountryRepository $countryRepo, CriterionRepository $criterionRepo)
    {
        parent::__construct();
        $this->em = $em;
        $this->countryRepo = $countryRepo;
        $this->criterionRepo = $criterionRepo;

    }

    protected function configure()
    {
        $this
            ->setDescription('This command is used to load the data from indicators into the database.')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'File name')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $io->title('CSV data loader for ratings');
        $io->text([
            'the file to process must be in repertory /public/data/',
            'the delimiter must be ;'
        ]);

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $criterionCode = $io->ask('what criterion code are those ratings for ?');
        $io->text('Criterion found : '.$this->criterionRepo->findOneBy(['code' => $criterionCode])->getDesignation());

        $fileName = $io->ask('what is file name ?');

        $reader = Reader::createFromPath('%kernel.root_dir%/../public/data/'.$fileName);
        $reader->setDelimiter(';');
        $reader->setHeaderOffset(0);
        $results = $reader->getIterator();

        $io->table($reader->getHeader(),iterator_to_array($results));
        $io->note('there are '.$reader->count().' lines detected in the file. It will take some time to process');
        $confirmation = $io->confirm('Ready to process ?', FALSE);

        if (TRUE == $confirmation){
            
            $io->progressStart($reader->count());

            foreach ($results as $row) {
                $rating = (new Rating())
                    ->setRatingValue($row['rating_value'])
                    ->setSourceIndex($row['source_index'])
                    ->setRatingDate(new \DateTime())
                    //->setCriterionCode($row['criterion_code'])
                    ->setCountry($this->countryRepo->findOneBy(['alpha3' => $row['country_alpha3']]))
                    ->setCriterion($this->criterionRepo->findOneBy(['code' => $criterionCode]))
                ;

                $this->em->persist($rating);
                $this->em->flush();
                $io->progressAdvance();
            }

            $io->progressFinish();
            $io->success('The data have been loaded from file '.$fileName);

            return 0;
        }



        if ($input->getOption('option1')) {
            // ...
        }

        $io->warning('No data loaded, process aborded');

        return 0;
    }
}
