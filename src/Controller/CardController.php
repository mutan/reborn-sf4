<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\CardRepository;
use App\Services\CardService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/card")
 */
class CardController extends BaseController
{
    /**
     * @Route("/", name="card_index", methods={"GET"})
     */
    public function index(CardRepository $cardRepository, CardService $cardService): Response
    {
        return $this->render('admin/card/index.html.twig', [
            'cards' => $cardRepository->findAll(),
            'cardService' => $cardService,
        ]);
    }

    /**
     * @Route("/new", name="card_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function new(Request $request): JsonResponse
    {
        $card = new Card();
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($card);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_CARD_COUNT);
            $this->addFlash('success', "Карта добавлена");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/card/_card_modal.html.twig', [
                'form' => $form->createView(),
                'title' => 'Новая карта'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="card_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Card $card): JsonResponse
    {
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash('success', "Карта {$card->getName()} обновлена");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/card/_card_modal.html.twig', [
                'card' => $card,
                'form' => $form->createView(),
                'title' => 'Редактировать карту'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}", name="card_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Card $card): Response
    {
        if ($this->isCsrfTokenValid('delete' . $card->getId(), $request->request->get('_token'))) {
            $this->getEm()->remove($card);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_CARD_COUNT);
            $this->addFlash('success','Карта удалена');
        }

        return $this->redirectToRoute('card_index');
    }
}
