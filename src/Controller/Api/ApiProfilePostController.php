<?php 

namespace App\Controller\Api;

use App\Repository\CountryRepository;
use App\Repository\ProfileRepository;
use App\Repository\ScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * The goal of this custom controller is to fill requested fields author and dates before insertion
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 */
class ApiProfilePostController extends AbstractController
{

	public function __invoke($data)
	{
		$profile = $data;

		$profile
			->setAuthor($this->getUser())
			->setCreationDate(new \DateTime)
			->setModificationDate(new \DateTime)
		;

        return $profile;

	}


}




