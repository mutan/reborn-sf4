<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Exceptions\EmptyManyToManyRelationException;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use App\Services\CardService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/artist")
 */
class ArtistController extends BaseController
{
    /**
     * @Route("/", name="artist_index", methods={"GET"})
     * @param ArtistRepository $artistRepository
     * @return Response
     */
    public function index(ArtistRepository $artistRepository): Response
    {
        return $this->render('admin/artist/index.html.twig', [
            'artists' => $artistRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="artist_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function new(Request $request): JsonResponse
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($artist);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_ARTIST_COUNT);
            $this->addFlash('success', "Художник добавлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/artist/_artist_modal.html.twig', [
                'form' => $form->createView(),
                'title' => 'Новый художник'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="artist_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Artist $artist
     * @return JsonResponse
     */
    public function edit(Request $request, Artist $artist): JsonResponse
    {
        $form = $this->createForm(ArtistType::class, $artist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash('success', "Художник {$artist->getName()} обновлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/artist/_artist_modal.html.twig', [
                'artist' => $artist,
                'form' => $form->createView(),
                'title' => 'Редактировать художника'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}", name="artist_delete", methods={"DELETE"})
     * @param Request $request
     * @param Artist $artist
     * @return Response
     * @throws InvalidArgumentException
     */
    public function delete(Request $request, Artist $artist): Response
    {
        if ($this->isCsrfTokenValid('delete' . $artist->getId(), $request->request->get('_token'))) {
            try {
                $this->getEm()->remove($artist);
                $this->getEm()->flush();
                $this->getCache()->deleteItem(CardService::CACHE_KEY_ARTIST_COUNT);
                $this->addFlash('success','Художник удален');
            } catch (EmptyManyToManyRelationException $e) {
                $this->addFlash(
                    'warning',
                    "В базе есть карты, использующие художника \"{$artist->getName()}\". Удаление невозможно."
                );
            }
        }

        return $this->redirectToRoute('artist_index');
    }
}
