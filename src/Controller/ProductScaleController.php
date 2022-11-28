<?php

namespace App\Controller;

use App\Entity\ProductScale;
use App\Form\ProductScaleType;
use App\Repository\ProductScaleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin/scale")
 * 
 */
class ProductScaleController extends AbstractController
{
    /**
     * @Route("/", name="product_scale_index", methods={"GET"})
     */
    public function index(ProductScaleRepository $productScaleRepository): Response
    {
        $user = $this->getUser();

        $productScales = $productScaleRepository->getActiveScaleForOneUser($user);

        $entityManager = $this->getDoctrine()->getManager();
        
        // The persistence of the result doesn't work.
        // 
        //   foreach ($productScales as $productScale) {
        //     $entityManager->persist($productScale);
        // }
      
        // $entityManager->flush();

        return $this->render('product_scale/index.html.twig', [
            'product_scales' => $productScales,
        ]);
    }

    /**
     * @Route("/new", name="product_scale_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $productScale = new ProductScale();
        $form = $this->createForm(ProductScaleType::class, $productScale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productScale->setCreationDate(new \DateTime());
            $productScale->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productScale);
            $entityManager->flush();

            return $this->redirectToRoute('product_scale_index');
        }

        return $this->render('product_scale/new.html.twig', [
            'product_scale' => $productScale,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_scale_show", methods={"GET"})
     */
    public function show(ProductScale $productScale): Response
    {
        return $this->render('product_scale/show.html.twig', [
            'product_scale' => $productScale,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="product_scale_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductScale $productScale): Response
    {
        $form = $this->createForm(ProductScaleType::class, $productScale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('product_scale_index');
        }

        return $this->render('product_scale/edit.html.twig', [
            'product_scale' => $productScale,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="product_scale_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductScale $productScale): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productScale->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($productScale);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_scale_index');
    }
}
