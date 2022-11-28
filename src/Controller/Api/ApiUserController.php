<?php 

namespace App\Controller\Api;

use App\Entity\Priority;
use App\Entity\Profile;
use App\Entity\User;
use App\Entity\Weighting;
use App\Repository\CountryRepository;
use App\Repository\PriorityRepository;
use App\Repository\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiUserController extends AbstractController
{

	private $profileRepository;
	private $countryRepository;
	private $encoder;
	private $em;
	private $priorityRepository;

	public function __construct(
		ProfileRepository $profileRepository, 
		CountryRepository $countryRepository, 
		UserPasswordEncoderInterface $encoder, 
		EntityManagerInterface $em,
		PriorityRepository $priorityRepository
	)
	{
		$this->profileRepository = $profileRepository;
		$this->countryRepository = $countryRepository;
		$this->encoder = $encoder;
		$this->em = $em;
		$this->priorityRepository = $priorityRepository;
	}

	public function __invoke($data)
	{
		$user = $data;
		$user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
		$user->eraseCredentials();
        $user->setRoles(["ROLE_USER"]);
        $user->setCountry($this->countryRepository->findOneBy(["name" => "France"]));
        $this->em->persist($user);
        $this->em->flush();

        $newProfile = new Profile();
        $newProfile
        	->setTitle("copie automatique du profil par défaut à la création de l'utilisateur")
        	->setAuthor($user)
        	->setCreationDate(new \DateTime())
        	->setModificationDate(new \DateTime())
        	->setIsPublic(false)
        ;
        $this->em->persist($newProfile);
        $this->em->flush();


        $defaultProfile = $this->profileRepository->find($this->getParameter('app.default_profile_id'));

		$defaultPriorities = $defaultProfile->getPriorities();
        foreach ($defaultPriorities as $defaultPriority) {
        	$newPriority = new Priority();
        	$newPriority
        		->setProfile($newProfile)
        		->setTheme($defaultPriority->getTheme())
        		->setValue($defaultPriority->getValue())
    		;
    		$this->em->persist($newPriority);
        }
        $this->em->flush();

		$defaultWeightings = $defaultProfile->getWeightings();
        foreach ($defaultWeightings as $defaultWeighting) {
        	$newWeighting = new Weighting();
        	$newWeighting
        		->setProfile($newProfile)
        		->setCriterion($defaultWeighting->getCriterion())
        		->setValue($defaultWeighting->getValue())
        		->setPositiveFlag($defaultWeighting->getPositiveFlag())
        		->setNegativeFlag($defaultWeighting->getNegativeFlag())
        		->setCreationDate(new \DateTime())
        		->setModificationDate(new \DateTime())
        		->setPriority($this->priorityRepository->findOneFromCriterionAndProfile($defaultWeighting->getCriterion(), $newProfile))
    		;
    		$this->em->persist($newWeighting);
        }
        $this->em->flush();


        $user->setActiveProfile($newProfile);

		return $user;
	}


}




