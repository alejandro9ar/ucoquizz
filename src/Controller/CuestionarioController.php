<?php

namespace App\Controller;

use App\Entity\Cuestionario;
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

}
