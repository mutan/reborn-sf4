<?php

namespace App\Controller;

use App\Entity\Rarity;
use App\Form\RarityType;
use App\Repository\RarityRepository;
use App\Services\CardService;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/rarity")
 */
class RarityController extends BaseController
{
    /**
     * @Route("/", name="rarity_index", methods={"GET"})
     * @param RarityRepository $rarityRepository
     * @param CardService $cardService
     * @return Response
     */
    public function index(RarityRepository $rarityRepository, CardService $cardService): Response
    {
        return $this->render('admin/rarity/index.html.twig', [
            'rarities' => $rarityRepository->findAll(),
            'cardService' => $cardService,
        ]);
    }

    /**
     * @Route("/new", name="rarity_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function new(Request $request): JsonResponse
    {
        $rarity = new Rarity();
        $form = $this->createForm(RarityType::class, $rarity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($rarity);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_ELEMENT_COUNT);
            $this->addFlash('success', "Стихия добавлена");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/rarity/_rarity_modal.html.twig', [
                'form' => $form->createView(),
                'title' => 'Новая стихия'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="rarity_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Rarity $rarity
     * @return JsonResponse
     */
    public function edit(Request $request, Rarity $rarity): JsonResponse
    {
        $form = $this->createForm(RarityType::class, $rarity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash('success', "Стихия {$rarity->getName()} обновлена");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/rarity/_rarity_modal.html.twig', [
                'rarity' => $rarity,
                'form' => $form->createView(),
                'title' => 'Редактировать стихию'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}", name="rarity_delete", methods={"DELETE"})
     * @param Request $request
     * @param Rarity $rarity
     * @return Response
     * @throws InvalidArgumentException
     */
    public function delete(Request $request, Rarity $rarity): Response
    {
        if ($this->isCsrfTokenValid('delete' . $rarity->getId(), $request->request->get('_token'))) {
            try {
                $this->getEm()->remove($rarity);
                $this->getEm()->flush();
                $this->getCache()->deleteItem(CardService::CACHE_KEY_RARITY_COUNT);
                $this->addFlash('success','Стихия удалена');
            } catch (ForeignKeyConstraintViolationException $e) {
                $this->addFlash('danger',"В базе есть карты, использующие редкость \"{$rarity->getName()}\". Удаление невозможно.");
            }
        }

        return $this->redirectToRoute('rarity_index');
    }
}
