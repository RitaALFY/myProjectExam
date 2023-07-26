<?php

namespace App\Controller\Back;

use App\Entity\Model;
use App\Form\ModelType;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/model/admin')]
class ModelController extends AbstractController
{
    #[Route('/', name: 'app_model_admin_index', methods: ['GET'])]
    public function index(ModelRepository $modelRepository): Response
    {
        return $this->render('back/pages/model/index.html.twig', [
            'models' => $modelRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_model_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $model = new Model();
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($model);
            $entityManager->flush();

            return $this->redirectToRoute('app_model_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/pages/model/new.html.twig', [
            'model' => $model,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_model_admin_show', methods: ['GET'])]
    public function show(Model $model): Response
    {
        return $this->render('back/pages/model/show.html.twig', [
            'model' => $model,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_model_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Model $model, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModelType::class, $model);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_model_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('model/edit.html.twig', [
            'model' => $model,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_model_admin_delete', methods: ['POST'])]
    public function delete(Request $request, Model $model, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$model->getId(), $request->request->get('_token'))) {
            $entityManager->remove($model);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_model_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
