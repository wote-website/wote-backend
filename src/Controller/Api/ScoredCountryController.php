<?php 

namespace App\Controller\Api;

use App\Repository\CountryRepository;
use App\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class ScoredCountryController extends AbstractController
{

	private $countryRepository;
	private $profileRepository;

	public function __construct(CountryRepository $countryRepository, ProfileRepository $profileRepository)
	{
		$this->countryRepository = $countryRepository;
		$this->profileRepository = $profileRepository;
	}

	public function __invoke()
	{
		if ($user = $this->getUser()){
			$profile = $user->getActiveProfile();
		}
		else {
			$profile = $this->profileRepository->find($this->getParameter('app.default_profile_id'));
		}

		return $this->countryRepository->getScoredCountriesForOneProfile($profile);
	}


}