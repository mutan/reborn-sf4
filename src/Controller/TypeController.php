<?php

namespace App\Controller;

use App\Entity\Type;
use App\Exceptions\EmptyManyToManyRelationException;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use App\Services\CardService;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/type")
 */
class TypeController extends BaseController
{
    /**
     * @Route("/", name="type_index", methods={"GET"})
     * @param TypeRepository $typeRepository
     * @return Response
     */
    public function index(TypeRepository $typeRepository): Response
    {
        return $this->render('admin/type/index.html.twig', [
            'types' => $typeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_new", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws InvalidArgumentException
     */
    public function new(Request $request): JsonResponse
    {
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->persist($type);
            $this->getEm()->flush();
            $this->getCache()->deleteItem(CardService::CACHE_KEY_ARTIST_COUNT);
            $this->addFlash('success', "Тип добавлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/type/_type_modal.html.twig', [
                'form' => $form->createView(),
                'title' => 'Добавить тип'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}/edit", name="type_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Type $type
     * @return JsonResponse
     */
    public function edit(Request $request, Type $type): JsonResponse
    {
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEm()->flush();
            $this->addFlash('success', "Тип {$type->getName()} обновлен");
            $reload = true;
        }

        return new JsonResponse([
            'message' => 'Success',
            'reload' => $reload ?? false,
            'output' => $this->renderView('admin/type/_type_modal.html.twig', [
                'type' => $type,
                'form' => $form->createView(),
                'title' => 'Редактировать тип'
            ])
        ], 200);
    }

    /**
     * @Route("/{id}", name="type_delete", methods={"DELETE"})
     * @param Request $request
     * @param Type $type
     * @return Response
     * @throws InvalidArgumentException
     */
    public function delete(Request $request, Type $type): Response
    {
        if ($this->isCsrfTokenValid('delete' . $type->getId(), $request->request->get('_token'))) {
            try {
                $this->getEm()->remove($type);
                $this->getEm()->flush();
                $this->getCache()->deleteItem(CardService::CACHE_KEY_TYPE_COUNT);
                $this->addFlash('success','Тип удален');
            } catch (EmptyManyToManyRelationException $e) {
                $this->addFlash(
                    'danger',
                    "В базе есть карты, использующие тип \"{$type->getName()}\". Удаление невозможно."
                );
            }
        }

        return $this->redirectToRoute('type_index');
    }
}
