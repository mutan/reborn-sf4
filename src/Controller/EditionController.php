<?php

namespace App\Controller;

use App\Entity\Edition;
use App\Form\EditionType;
use App\Repository\EditionRepository;
use App\Services\CardService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/edition")
 */
class EditionController extends BaseController
{
    /**
     * @Route("/", name="edition_index", methods={"GET"})
     */
    public function index(EditionRepository $editionRepository, CardService $cardService): Response
    {
        return $this->render('admin/edition/index.html.twig', [
            'editions' => $editionRepository->findAllOrderedByNumber(),
            'cardService' => $cardService,
        ]);
    }

    /**
     * @Route("/new", name="edition_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function new(Request $request): JsonResponse
    {
        $edition = new Edition();
        $form = $this->createForm(EditionType::class, $edition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($edition);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_EDITION_COUNT);
            $this->addFlash('success', "Выпуск добавлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/edition/_edition_modal.html.twig', [
                'form' => $form->createView(),
                'title' => 'Новый выпуск'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="edition_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Edition $edition): JsonResponse
    {
        $form = $this->createForm(EditionType::class, $edition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash('success', "Выпуск {$edition->getName()} обновлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/edition/_edition_modal.html.twig', [
                'edition' => $edition,
                'form' => $form->createView(),
                'title' => 'Редактировать выпуск'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}", name="edition_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Edition $edition): Response
    {
        if ($this->isCsrfTokenValid('delete' . $edition->getId(), $request->request->get('_token'))) {
            $this->getEm()->remove($edition);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_EDITION_COUNT);
            $this->addFlash('success','Выпуск удален');
        }

        return $this->redirectToRoute('edition_index');
    }
}
