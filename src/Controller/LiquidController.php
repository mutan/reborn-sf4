<?php

namespace App\Controller;

use App\Entity\Liquid;
use App\Exceptions\EmptyManyToManyRelationException;
use App\Form\LiquidType;
use App\Repository\LiquidRepository;
use App\Services\CardService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/liquid")
 */
class LiquidController extends BaseController
{
    /**
     * @Route("/", name="liquid_index", methods={"GET"})
     * @param LiquidRepository $liquidRepository
     * @param CardService $cardService
     * @return Response
     */
    public function index(LiquidRepository $liquidRepository, CardService $cardService): Response
    {
        return $this->render('admin/liquid/index.html.twig', [
            'liquids' => $liquidRepository->findAll(),
            'cardService' => $cardService,
        ]);
    }

    /**
     * @Route("/new", name="liquid_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function new(Request $request): JsonResponse
    {
        $liquid = new Liquid();
        $form = $this->createForm(LiquidType::class, $liquid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($liquid);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_LIQUID_COUNT);
            $this->addFlash('success', "Стихия добавлена");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/liquid/_liquid_modal.html.twig', [
                'form' => $form->createView(),
                'title' => 'Новая стихия'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="liquid_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Liquid $liquid
     * @return JsonResponse
     */
    public function edit(Request $request, Liquid $liquid): JsonResponse
    {
        $form = $this->createForm(LiquidType::class, $liquid);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash('success', "Стихия {$liquid->getName()} обновлена");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/liquid/_liquid_modal.html.twig', [
                'liquid' => $liquid,
                'form' => $form->createView(),
                'title' => 'Редактировать стихию'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}", name="liquid_delete", methods={"DELETE"})
     * @param Request $request
     * @param Liquid $liquid
     * @return Response
     * @throws InvalidArgumentException
     */
    public function delete(Request $request, Liquid $liquid): Response
    {
        if ($this->isCsrfTokenValid('delete' . $liquid->getId(), $request->request->get('_token'))) {
            try {
                $this->getEm()->remove($liquid);
                $this->getEm()->flush();
                $this->getCache()->deleteItem(CardService::CACHE_KEY_LIQUID_COUNT);
                $this->addFlash('success','Стихия удалена');
            } catch (EmptyManyToManyRelationException $e) {
                $this->addFlash(
                    'warning',
                    "В базе есть карты, использующие жидкость \"{$liquid->getName()}\". Удаление невозможно."
                );
            }
        }

        return $this->redirectToRoute('liquid_index');
    }
}
