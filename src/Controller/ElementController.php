<?php

namespace App\Controller;

use App\Entity\Element;
use App\Form\ElementType;
use App\Repository\ElementRepository;
use App\Services\CardService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/element")
 */
class ElementController extends BaseController
{
    /**
     * @Route("/", name="element_index", methods={"GET"})
     */
    public function index(ElementRepository $elementRepository, CardService $cardService): Response
    {
        return $this->render('admin/element/index.html.twig', [
            'elements' => $elementRepository->findAll(),
            'cardService' => $cardService,
        ]);
    }

    /**
     * @Route("/new", name="element_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function new(Request $request): JsonResponse
    {
        $element = new Element();
        $form = $this->createForm(ElementType::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($element);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_ELEMENT_COUNT);
            $this->addFlash('success', "Стихия добавлена");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/element/_element_modal.html.twig', [
                'form' => $form->createView(),
                'title' => 'Новая стихия'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="element_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Element $element): JsonResponse
    {
        $form = $this->createForm(ElementType::class, $element);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash('success', "Стихия {$element->getName()} обновлена");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/element/_element_modal.html.twig', [
                'element' => $element,
                'form' => $form->createView(),
                'title' => 'Редактировать стихию'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}", name="element_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Element $element): Response
    {
        if ($this->isCsrfTokenValid('delete' . $element->getId(), $request->request->get('_token'))) {
            $this->getEm()->remove($element);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_ELEMENT_COUNT);
            $this->addFlash('success','Стихия удалена');
        }

        return $this->redirectToRoute('element_index');
    }
}
