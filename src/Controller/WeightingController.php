<?php

namespace App\Controller;

use App\Entity\Criterion;
use App\Entity\Priority;
use App\Entity\Profile;
use App\Entity\Weighting;
use App\Form\WeightingType;
use App\Repository\CriterionRepository;
use App\Repository\PriorityRepository;
use App\Repository\ProfileRepository;
use App\Repository\WeightingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/weighting")
 */
class WeightingController extends AbstractController
{
    /**
     * @Route("/", name="weighting_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(WeightingRepository $weightingRepository): Response
    {
        return $this->render('weighting/index.html.twig', [
            'weightings' => $weightingRepository->findAll(),
        ]);
    }

    // /**
    //  * @Route("/new", name="weighting_new", methods={"GET","POST"})
    //  */
    // public function new(Request $request): Response
    // {
    //     $weighting = new Weighting();
    //     $form = $this->createForm(WeightingType::class, $weighting);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $weighting->setCreationDate(new \DateTime());
    //         $weighting->setModificationDate(new \DateTime());
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($weighting);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('weighting_index');
    //     }

    //     return $this->render('weighting/new.html.twig', [
    //         'weighting' => $weighting,
    //         'form' => $form->createView(),
    //     ]);
    // }    

    /**
     * @Route("/new/{priorityId}/{criterionId}", name="weighting_new_in_priority", methods={"GET","POST"})
     */
    public function newInPriority(Request $request, PriorityRepository $priorityRepository, $priorityId, CriterionRepository $criterionRepository, $criterionId, WeightingRepository $weightingRepository): Response
    {
        $priority = $priorityRepository->find($priorityId);
        $criterion = $criterionRepository->find($criterionId);


        $this->denyAccessUnlessGranted('PRIORITY_EDIT', $priority);

        $weighting = new Weighting();
        $weighting->setCriterion($criterion);
        $weighting->setPriority($priority);
        $weighting->setProfile($priority->getProfile());

        $form = $this->createForm(WeightingType::class, $weighting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $weighting->setCreationDate(new \DateTime());
            $weighting->setModificationDate(new \DateTime());
            $weighting = $this->setCalculatedFlags($weighting);


            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($weighting);
            $entityManager->flush();
            
            $priority->setWeightingsSum($weightingRepository->findSumByOnePriority($priority)['sumValue']);
            $entityManager->flush();

            return $this->redirectToRoute('priority_edit',['id' => $priorityId]);
        }

        return $this->render('weighting/new_in_priority.html.twig', [
            'weighting' => $weighting,
            'form' => $form->createView(),
            'priority' => $priority,
            'criterion' => $criterion,
        ]);
    }

    /**
     * @Route("/{id}", name="weighting_show", methods={"GET"})
     */
    public function show(Weighting $weighting): Response
    {
        $this->denyAccessUnlessGranted('WEIGHTING_VIEW', $weighting);

        return $this->render('weighting/show.html.twig', [
            'weighting' => $weighting,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="weighting_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Weighting $weighting, WeightingRepository $weightingRepository, PriorityRepository $priorityRepository): Response
    {
        $this->denyAccessUnlessGranted('WEIGHTING_EDIT', $weighting);

        $form = $this->createForm(WeightingType::class, $weighting);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $weighting->setModificationDate(new \DateTime());
            
            $weighting = $this->setCalculatedFlags($weighting);

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $priority = $priorityRepository->find($weighting->getPriority()->getId());
            $priority->setWeightingsSum($weightingRepository->findSumByOnePriority($priority)['sumValue']);
            $em->flush();

            return $this->redirectToRoute('priority_edit', ['id' => $priority->getId()]);
        }

        return $this->render('weighting/edit.html.twig', [
            'weighting' => $weighting,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="weighting_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Weighting $weighting, WeightingRepository $weightingRepository, PriorityRepository $priorityRepository): Response
    {
        $priority = $priorityRepository->find($weighting->getPriority()->getId());

        if ($this->isCsrfTokenValid('delete'.$weighting->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($weighting);
            $entityManager->flush();

            $priority->setWeightingsSum($weightingRepository->findSumByOnePriority($priority)['sumValue']);
            $entityManager->flush();
        }

        return $this->redirectToRoute('priority_edit', ['id' => $priority->getId()]);
    }

    private function setCalculatedFlags(Weighting $weighting)
    {
            $weighting
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
