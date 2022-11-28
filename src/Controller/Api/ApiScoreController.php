<?php 

namespace App\Controller\Api;

use App\Repository\CountryRepository;
use App\Repository\ProfileRepository;
use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ApiScoreController extends AbstractController
{

	private $scoreRepository;
	private $countryRepository;
	private $profileRepository;

	public function __construct(ScoreRepository $scoreRepository, CountryRepository $countryRepository, ProfileRepository $profileRepository)
	{
		
		$this->scoreRepository = $scoreRepository;
		$this->countryRepository = $countryRepository;
		$this->profileRepository = $profileRepository;
	}

	public function __invoke($data)
	{
		$request = Request::createFromGlobals();

		if ($user = $this->getUser()){
			$profile = $user->getActiveProfile();
		}
		else {
			$profile = $this->profileRepository->find($this->getParameter('app.default_profile_id'));
		}


		if ($countryId = $request->query->get('country')){
			$countryScore = $this->scoreRepository->getScoresForOneCountryForOneProfile($this->countryRepository->find($countryId), $profile);
			$themeScores = $this->scoreRepository->getScoresOfThemesForOneCountryForOneProfile($this->countryRepository->find($countryId), $profile);
			$criterionScores = $this->scoreRepository->getScoresOfCriteriaForOneCountryForOneProfile($this->countryRepository->find($countryId), $profile);
			return array_merge($countryScore, $themeScores, $criterionScores);

		}

		return $this->scoreRepository->getScoresForAllCountriesForOneProfile($profile);
	}


}




