<?php

namespace App\Controller;

use App\Entity\Criterion;
use App\Form\CriterionType;
use App\Repository\CriterionRepository;
use App\Repository\RatingRepository;
use App\Repository\ThemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/")
 */
class CriterionController extends AbstractController
{
    /**
     * @Route("/", name="criterion_index", methods={"GET"})
     */
    public function index(CriterionRepository $criterionRepository, ThemeRepository $themeRepo): Response
    {
        $criteria = $criterionRepository->findAllWithTheme();
        $themes = $themeRepo->findAll();
        return $this->render('criterion/index.html.twig', [
            'criteria' => $criteria,
            'themes' => $themes,
        ]);
    }

    /**
     * @Route("/criterion/new", name="criterion_new", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request): Response
    {
        $criterion = new Criterion();
        $form = $this->createForm(CriterionType::class, $criterion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            if (NULL == $criterion->getCreationDate()){
                $criterion->setCreationDate(new \DateTime());
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($criterion);
            $entityManager->flush();

            return $this->redirectToRoute('criterion_index');
        }

        return $this->render('criterion/new.html.twig', [
            'criterion' => $criterion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/criterion/{id}", name="criterion_show", methods={"GET"})
     */
    public function show(Criterion $criterion, RatingRepository $ratingRepo): Response
    {
        // $ratings = $ratingRepo->findBy(['criterion' => $criterion]);
        $ratings = $ratingRepo->findByCriterionWithCountry($criterion);

        return $this->render('criterion/show.html.twig', [
            'criterion' => $criterion,
            // 'ratings' => $criterion->getRatings(),
            'ratings' => $ratings,
        ]);
    }

    /**
     * @Route("/criterion/{id}/edit", name="criterion_edit", methods={"GET","POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function edit(Request $request, Criterion $criterion): Response
    {
        $form = $this->createForm(CriterionType::class, $criterion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $criterion->setModificationDate(new \DateTime());
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('criterion_index');
        }

        return $this->render('criterion/edit.html.twig', [
            'criterion' => $criterion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/criterion/{id}", name="criterion_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Criterion $criterion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$criterion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($criterion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('criterion_index');
    }
}
