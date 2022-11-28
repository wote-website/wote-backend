<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use App\Repository\PriorityRepository;
use App\Repository\ProfileRepository;
use App\Repository\RatingRepository;
use App\Repository\ScoreRepository;
use App\Repository\WeightingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/country")
 */
class CountryController extends AbstractController
{
    /**
     * @Route("/", name="country_index", methods={"GET"})
     */
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('country/index.html.twig', [
            'countries' => $countryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/rated", name="rated_country_index", methods={"GET"})
     */
    public function ratedIndex(CountryRepository $countryRepository, ProfileRepository $profileRepo, ScoreRepository $scoreRepository): Response
    {
        //dd($this->getUser());
        $profile = $this->getUser()->getActiveProfile();
        //$profile = $profileRepo->find($profileId); 

        $countries = $countryRepository->getScoredCountriesForOneProfile($profile);
        $scores = $scoreRepository->getScoresForAllCountriesForOneProfile($profile);
        
        return $this->render('country/rated_index.html.twig', [
            'countries' => $countries,
            'profileId' => $profile->getId(),
            'scores' => $scores,
        ]);
    }

    /**
     * @Route("/new", name="country_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($country);
            $entityManager->flush();

            return $this->redirectToRoute('country_index');
        }

        return $this->render('country/new.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="country_show", methods={"GET"})
     */
    public function show(Country $country, RatingRepository $ratingRepo, ProfileRepository $profileRepository, PriorityRepository $priorityRepository, WeightingRepository $weightingRepository): Response
    {
        //$profile = $profileRepository->find($profileId);
        $profile = $this->getUser()->getActiveProfile();
        $priorities = $priorityRepository->findAllInProfileWithAllCriterial($profile);
        $ratings = $ratingRepo->findAllByCountry($country);
        $weightings = $weightingRepository->findAllForOneProfile($profile);

        $scores = $ratingRepo->getScoresForOneCountryByProfile($country, $profile);

        $scoresSum = 0;
        $prioritiesSum = 0;
        $transparencySum = 0;

        foreach ($priorities as $priority) {
            foreach ($scores as $score) {
                if ($priority->getId() == $score['priorityId']){
                    $priority->setScore($score['score']);
                    $priority->setTransparency($score['ratedWeightingsSum']/$priority->getWeightingsSum());

                    $scoresSum += $score['score']*$priority->getValue()*$priority->getTransparency();
                }
            }
            $prioritiesSum += $priority->getValue();
            $transparencySum += $priority->getTransparency()*$priority->getValue();
        }

        $country->setScore($scoresSum/$transparencySum);
        $country->setTransparency($transparencySum/$prioritiesSum);



        $ratings2 = $ratingRepo->findByCountryForWeighting($country, $profile);


        $priorityRepository->findAllInProfileBySQL($profile);


        return $this->render('country/show.html.twig', [
            'profileId' => $profile->getId(),
            'country' => $country,
            'priorities' => $priorities,
            'ratings' => $ratings,
            'weightings' => $weightings,
            'ratings2' => $ratings2,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="country_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Country $country): Response
    {
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('country_index');
        }

        return $this->render('country/edit.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="country_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Country $country): Response
    {
        if ($this->isCsrfTokenValid('delete'.$country->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($country);
            $entityManager->flush();
        }

        return $this->redirectToRoute('country_index');
    }
}
