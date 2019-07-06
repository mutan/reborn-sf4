<?php

namespace App\Controller;

use App\Entity\Supertype;
use App\Exceptions\EmptyManyToManyRelationException;
use App\Form\SupertypeType;
use App\Repository\SupertypeRepository;
use App\Services\CardService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/supertype")
 */
class SupertypeController extends BaseController
{
    /**
     * @Route("/", name="supertype_index", methods={"GET"})
     * @param SupertypeRepository $supertypeRepository
     * @return Response
     */
    public function index(SupertypeRepository $supertypeRepository): Response
    {
        return $this->render('admin/supertype/index.html.twig', [
            'supertypes' => $supertypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="supertype_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function new(Request $request): JsonResponse
    {
        $supertype = new Supertype();
        $form = $this->createForm(SupertypeType::class, $supertype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($supertype);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_ARTIST_COUNT);
            $this->addFlash('success', "Супертип добавлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/supertype/_supertype_modal.html.twig', [
                'form' => $form->createView(),
                'title' => 'Новый супертип'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="supertype_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Supertype $supertype
     * @return JsonResponse
     */
    public function edit(Request $request, Supertype $supertype): JsonResponse
    {
        $form = $this->createForm(SupertypeType::class, $supertype);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash('success', "Супертип {$supertype->getName()} обновлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/supertype/_supertype_modal.html.twig', [
                'supertype' => $supertype,
                'form' => $form->createView(),
                'title' => 'Редактировать супертип'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}", name="supertype_delete", methods={"DELETE"})
     * @param Request $request
     * @param Supertype $supertype
     * @return Response
     * @throws InvalidArgumentException
     */
    public function delete(Request $request, Supertype $supertype): Response
    {
        if ($this->isCsrfTokenValid('delete' . $supertype->getId(), $request->request->get('_token'))) {
            try {
                $this->getEm()->remove($supertype);
                $this->getEm()->flush();
                $this->getCache()->deleteItem(CardService::CACHE_KEY_SUPERTYPE_COUNT);
                $this->addFlash('success','Супертип удален');
            } catch (EmptyManyToManyRelationException $e) {
                $this->addFlash(
                    'danger',
                    "В базе есть карты, использующие супертип \"{$supertype->getName()}\". Удаление невозможно."
                );
            }
        }

        return $this->redirectToRoute('supertype_index');
    }
}
