<?php

namespace App\Command;

use App\Entity\Country;
use App\Entity\Rating;
use App\Repository\CountryRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;

/**
 * The difference with the LoadRatingFromCsvCommand command is that Countries are already linked to Rating.
 * They cannot be removed and replaced. The id must be kept so we need an update.
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 */
class UpdateCountriesFromCsvCommand extends Command
{
    protected static $defaultName = 'app:update-countries-from-csv';

    private $em;
    private $countryRepo;

    public function __construct(EntityManagerInterface $em, CountryRepository $countryRepo)
    {
        parent::__construct();
        $this->em = $em;
        $this->countryRepo = $countryRepo;

    }

    protected function configure()
    {
        $this
            ->setDescription('This command is used to load the data for countries update into the database.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('CSV data loader for countries update');
        $io->text([
            'the file to process must be in repertory /public/data/',
            'the delimiter must be ;'
        ]);

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
                $country = $this->countryRepo->findOneBy(['alpha3' => $row['alpha3']])
                    ->setFrName($row['fr_name'])
                    ->setName($row['name'])
                    ->setAlpha2($row['alpha2'])
                    ->setModificationDate(new \DateTime())
                ;

                $this->em->persist($country);
                $this->em->flush();
                $io->progressAdvance();
            }

            $io->progressFinish();
            $io->success('The data have been loaded from file '.$fileName);

            return 0;
        }


        $io->warning('No data loaded, process aborded');

        return 0;
    }
}
