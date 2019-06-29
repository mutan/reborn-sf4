<?php

namespace App\Controller;

use App\Entity\Subtype;
use App\Form\SubtypeType;
use App\Repository\SubtypeRepository;
use App\Services\CardService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/subtype")
 */
class SubtypeController extends BaseController
{
    /**
     * @Route("/", name="subtype_index", methods={"GET"})
     */
    public function index(SubtypeRepository $subtypeRepository): Response
    {
        return $this->render('admin/subtype/index.html.twig', [
            'subtypes' => $subtypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="subtype_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function new(Request $request): JsonResponse
    {
        $subtype = new Subtype();
        $form = $this->createForm(SubtypeType::class, $subtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($subtype);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_SUBTYPE_COUNT);
            $this->addFlash('success', "Подтип добавлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/subtype/_subtype_modal.html.twig', [
                'form' => $form->createView(),
                'title' => 'Новый подтип'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="subtype_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Subtype $subtype): JsonResponse
    {
        $form = $this->createForm(SubtypeType::class, $subtype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash('success', "Подтип {$subtype->getName()} обновлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/subtype/_subtype_modal.html.twig', [
                'subtype' => $subtype,
                'form' => $form->createView(),
                'title' => 'Редактировать подтип'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}", name="subtype_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Subtype $subtype): Response
    {
        if ($this->isCsrfTokenValid('delete' . $subtype->getId(), $request->request->get('_token'))) {
            $this->getEm()->remove($subtype);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_SUBTYPE_COUNT);
            $this->addFlash('success','Подтип удален');
        }

        return $this->redirectToRoute('subtype_index');
    }
}
