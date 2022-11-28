<?php 

namespace App\Controller\Api;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiPasswordController extends AbstractController
{

	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
	}

	public function __invoke($data)
	{
		$loggedUser = $this->getUser();
		$modifiedUser = $data;

		if ($this->encoder->isPasswordValid($loggedUser, $modifiedUser->getOldPassword()) && $modifiedUser->getId() == $loggedUser->getId()){
			$modifiedUser->setPassword($this->encoder->encodePassword($modifiedUser, $modifiedUser->getPlainPassword()));
			$modifiedUser->eraseCredentials();
			return $modifiedUser;
		}


        return new Response("User modification not allowed", 400);
	}


}




