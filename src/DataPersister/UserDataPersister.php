<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * This object is an alternative to encode password before persistance.
 * Not used because I prefer encode in a custom controller to avoid modifying the regular persister.
 * @todo delete this file
 * @author Ronan GLEMAIN ronan.glemain@gmail.com
 */
class UserDataPersister implements DataPersisterInterface
{

    private $entityManager;

    private $userPasswordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function supports($data): bool
    {
        return $data instanceof User;
    }

    /**
     * @param User $data
     */
    public function persist($data)
    {
        // if ($data->getPlainPassword()) {
        //     $data->setPassword(
        //         $this->userPasswordEncoder->encodePassword($data, $data->getPlainPassword())
        //     );
        //     $data->eraseCredentials();
        // }
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }
    
    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}