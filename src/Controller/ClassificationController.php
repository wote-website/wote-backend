<?php

namespace App\Controller;

use App\Entity\Classification;
use App\Form\ClassificationType;
use App\Repository\ClassificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/classification")
 * 
 */
class ClassificationController extends AbstractController
{
    /**
     * @Route("/", name="classification_index", methods={"GET"})
     */
    public function index(ClassificationRepository $classificationRepository): Response
    {
        return $this->render('classification/index.html.twig', [
            'classifications' => $classificationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="classification_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $classification = new Classification();
        $form = $this->createForm(ClassificationType::class, $classification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classification->setCreationDate(new \DateTime());
            $classification->setModificationDate(new \DateTime());
            $classification->setAuthor($this->getUser());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($classification);
            $entityManager->flush();

            return $this->redirectToRoute('classification_index');
        }

        return $this->render('classification/new.html.twig', [
            'classification' => $classification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="classification_show", methods={"GET"})
     */
    public function show(Classification $classification): Response
    {
        return $this->render('classification/show.html.twig', [
            'classification' => $classification,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="classification_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Classification $classification): Response
    {
        $form = $this->createForm(ClassificationType::class, $classification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $classification->setModificationDate(new \DateTime());            
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('classification_index');
        }

        return $this->render('classification/edit.html.twig', [
            'classification' => $classification,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="classification_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Classification $classification): Response
    {
        if ($this->isCsrfTokenValid('delete'.$classification->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($classification);
            $entityManager->flush();
        }

        return $this->redirectToRoute('classification_index');
    }
}
