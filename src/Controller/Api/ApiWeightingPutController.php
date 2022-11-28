<?php 

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * The goal of this custom controller is to authorize creation of weighting only in owned profile
 * This also fill required fields
 * And check there is no existing weighting for this Criterion and Profile
 * @author Ronan GLEMAIN <ronan.glemain@gmail.com>
 */
class ApiWeightingPutController extends AbstractController
{	

	public function __invoke($data)
	{
		$weighting = $data;


        $weighting
        	->setModificationDate(new \DateTime)
            ->setPositiveFlag(1)
            ->setNegativeFlag(0);

        if(0 > $weighting->getValue()){
            $weighting
                ->setPositiveFlag(0)
                ->setNegativeFlag(1);                
        }

        return $weighting;

	}


}




