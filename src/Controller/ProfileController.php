<?php

namespace App\Controller;

use App\Entity\Priority;
use App\Entity\Profile;
use App\Form\ProfileType;
use App\Repository\CriterionRepository;
use App\Repository\PriorityRepository;
use App\Repository\ProfileRepository;
use App\Repository\RatingRepository;
use App\Repository\ThemeRepository;
use App\Repository\WeightingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/profile")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/", name="profile_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(ProfileRepository $profileRepository): Response
    {
        return $this->render('profile/index.html.twig', [
            'profiles' => $profileRepository->findAll(),
        ]);
    }

    /**
     * @Route("/personal", name="personal_profile_index", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function personalIndex(ProfileRepository $profileRepository): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'profiles' => $profileRepository->findMyProfiles($user),
        ]);
    }

    /**
     * @Route("/public", name="public_profile_index", methods={"GET"})
     */
    public function publicIndex(ProfileRepository $profileRepository): Response
    {

        return $this->render('profile/index.html.twig', [
            'profiles' => $profileRepository->findPublicProfiles(),
        ]);
    }

    /**
     * @Route("/new", name="profile_new", methods={"GET","POST"})
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, ThemeRepository $themeRepo): Response
    {
        $profile = new Profile();
        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $profile->setCreationDate(new \DateTime());
            $profile->setModificationDate(new \DateTime());
            $profile->setAuthor($this->getUser());
            $profile->setIsPublic(false);
            
            $themes = $themeRepo->findAll();
            $priorities = [];
            
            foreach ($themes as $theme) {

                $entityManager->persist((new Priority())
                    ->setTheme($theme)
                    ->setProfile($profile)
                    ->setValue(100)
                );
            }

            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('personal_profile_index');
        }

        return $this->render('profile/new.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="profile_show", methods={"GET"})
     */
    public function show(Profile $profile, PriorityRepository $priorityRepository, RatingRepository $ratingRepository): Response
    {
        
        $this->denyAccessUnlessGranted('PROFILE_VIEW', $profile);

        return $this->render('profile/show.html.twig', [
            'profile' => $profile,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="profile_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Profile $profile, CriterionRepository $criterionRepo, WeightingRepository $weightingRepo, ThemeRepository $themeRepo, PriorityRepository $priorityRepo): Response
    {
                
        $this->denyAccessUnlessGranted('PROFILE_EDIT', $profile);

        $criteria = $criterionRepo->findAll();
        $themes = $themeRepo->findAll();

        $priorities = $priorityRepo->findAllInProfile($profile);

        $weightingsSum = $weightingRepo->findSumByOneProfile($profile);
        $weightings = $weightingRepo->findAllForOneProfile($profile);

        $form = $this->createForm(ProfileType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            $profile->setWeightingsSum($weightingsSum['sumValue']);
            $this->getDoctrine()->getManager()->flush();
            
            return $this->redirectToRoute('persnal_profile_index');
        }

        return $this->render('profile/edit.html.twig', [
            'profile' => $profile,
            'form' => $form->createView(),
            'criteria' => $criteria,
            'weightingsSum' => $weightingsSum,
            'weightings' => $weightings,
            'themes' => $themes,
            'priorities' => $priorities,
        ]);
    }

    /**
     * @Route("/{id}", name="profile_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Profile $profile): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profile->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($profile);
            $entityManager->flush();
        }

        return $this->redirectToRoute('profile_index');
    }
}
