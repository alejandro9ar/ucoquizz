<?php

namespace App\Controller;

use App\Entity\Cuestionario;
use App\Form\CuestionarioType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class CuestionarioController extends AbstractController
{
    /**
     * Lists all cuestionario entities.
     *
     * @Route("/", name="cuestionario.list", methods="GET")
     *
     * @return Response
     */
    public function list() : Response
    {
        $cuestionarios = $this->getDoctrine()->getRepository(Cuestionario::class)->findAll();

        return $this->render('cuestionario/list.html.twig', [
            'cuestionarios' => $cuestionarios,
        ]);
    }

    /**
     * Finds and displays a cuestionario entity.
     *
     * @Route("/{id}", name="cuestionario.show", requirements={"id":"\d+"})
     *
     * @param Cuestionario $cuestionario
     *
     * @return Response
     */
    public function show(Cuestionario $cuestionario) : Response
    {

            return $this->render('cuestionario/show.html.twig', [
                'cuestionario' => $cuestionario,
            ]);

    }

    /**
     * Creates a new cuestionario entity.
     *
     * @Route("/cuestionario/create", name="cuestionario.create", methods="GET")
     *
     * @return Response
     */
    public function create() : Response
    {
        $cuestionario = new Cuestionario();
        $form = $this->createForm(CuestionarioType::class, $cuestionario);

        return $this->render('cuestionario/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
