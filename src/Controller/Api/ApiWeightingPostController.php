<?php 

namespace App\Controller\Api;

use App\Repository\PriorityRepository;
use App\Repository\WeightingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The goal of this custom controller is to authorize creation of weighting only in owned profile
 * This also fill required fields
 * And check there is no existing weighting for this Criterion and Profile
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 */
class ApiWeightingPostController extends AbstractController
{
	private $priorityRepositoty;
	private $weightingRepository;

	public function __construct(
		PriorityRepository $priorityRepositoty,
		WeightingRepository $weightingRepository
	)
	{
		$this->priorityRepositoty = $priorityRepositoty;
		$this->weightingRepository = $weightingRepository;
	}
	

	public function __invoke($data)
	{
		$weighting = $data;
		$profile = $weighting->getProfile();
		$criterion = $weighting->getCriterion();

        $this->denyAccessUnlessGranted('PROFILE_EDIT', $profile);

        $existingWeighting = $this->weightingRepository->findOneFromCriterionAndProfile($criterion, $profile);

        if ($existingWeighting != null){
        	return new Response("There is already one weighting on this profile for this criterion. Update instead of create.", 409);
        	// throw new \RuntimeException("There is already one weighting on this profile for this criterion. Update instead of create.", 409, null);        	
        }

        $weighting
        	->setCreationDate(new \DateTime)
        	->setModificationDate(new \DateTime);

        $weighting
            ->setPositiveFlag(1)
            ->setNegativeFlag(0);

        if(0 > $weighting->getValue()){
            $weighting
                ->setPositiveFlag(0)
                ->setNegativeFlag(1);                
        }

        $priority = $this->priorityRepositoty->findOneFromCriterionAndProfile($criterion, $profile);

        $weighting->setPriority($priority);

        return $weighting;

	}


}




