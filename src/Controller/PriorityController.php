<?php

namespace App\Controller;

use App\Entity\Priority;
use App\Form\PriorityType;
use App\Repository\PriorityRepository;
use App\Repository\ProfileRepository;
use App\Repository\ThemeRepository;
use App\Repository\WeightingRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/priority")
 */
class PriorityController extends AbstractController
{
    /**
     * @Route("/", name="priority_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(PriorityRepository $priorityRepository): Response
    {
        return $this->render('priority/index.html.twig', [
            'priorities' => $priorityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="priority_show", methods={"GET"})
     */
    public function show(Priority $priority): Response
    {
        $this->denyAccessUnlessGranted('PRIORITY_VIEW', $priority);

        return $this->render('priority/show.html.twig', [
            'priority' => $priority,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="priority_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Priority $priority, WeightingRepository $weightingRepo, ProfileRepository $profileRepo, ThemeRepository $themeRepo, PriorityRepository $priorityRepo, $id): Response
    {
        
        $this->denyAccessUnlessGranted('PRIORITY_EDIT', $priority);

        $priorityWithCriteria = $priorityRepo->findOneWithCriteria($id);


        $form = $this->createForm(PriorityType::class, $priority);
        $form->handleRequest($request);

        $theme = $themeRepo->findOnebyPriorityWithCriteria($priority);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('profile_edit', ['id' => $priority->getProfile()->getId()]);
        }

        return $this->render('priority/edit.html.twig', [
            'priority' => $priority,
            'form' => $form->createView(),
            'theme' => $theme,
            'weightings' => $weightingRepo->findAllForOneProfile($priority->getProfile()),
        ]);
    }

    /**
     * @Route("/{id}", name="priority_delete", methods={"DELETE"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Priority $priority): Response
    {
        if ($this->isCsrfTokenValid('delete'.$priority->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($priority);
            $entityManager->flush();
        }

        return $this->redirectToRoute('priority_index');
    }
}
