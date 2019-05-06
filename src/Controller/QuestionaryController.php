<?php

namespace App\Controller;

use App\Entity\Questionary;
use App\Entity\Question;
use App\Form\CuestionarioType;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class QuestionaryController extends AbstractController
{


    /**
     * Lists all questionary entities.
     *
     * @Route("/", name="questionary.list", methods="GET")
     *
     * @return Response
     */
    public function list() : Response
    {
        $cuestionarios = $this->getDoctrine()->getRepository(Questionary::class)->findAll();

        return $this->render('questionary/list.html.twig', [
            'cuestionarios' => $cuestionarios,
        ]);
    }

    /**
     * Finds and displays a questionary entity.
     *
     * @Route("/{id}", name="questionary.show", requirements={"id":"\d+"})
     *
     * @param Questionary $cuestionario
     *
     * @return Response
     */
    public function show(Questionary $cuestionario) : Response
    {

            return $this->render('questionary/show.html.twig', [
                'questionary' => $cuestionario,
            ]);

    }

    /**
     * Creates a new questionary entity.
     *
     * @Route("/questionary/create", name="questionary.create", methods={"GET", "POST"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em) : Response
    {
        $cuestionario = new Questionary();
        $form = $this->createForm(CuestionarioType::class, $cuestionario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cuestionario->setUser($this->getUser());

            $em->persist($cuestionario);
            $em->flush();

            return $this->redirectToRoute('questionary.list');
        }

        return $this->render('questionary/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit existing questionary entity
     *
     * @Route("/questionary/{token}/edit", name="questionary.edit", methods={"GET", "POST"}, requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Questionary $cuestionario
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(Request $request, Questionary $cuestionario, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(CuestionarioType::class, $cuestionario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('questionary.list');
        }

        return $this->render('questionary/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    /**
     * Finds and displays the prEditeview page for a questionary entity.
     *
     * @Route("questionary/{token}", name="questionary.preview", methods="GET", requirements={"token" = "\w+"})
     *
     * @param Questionary $cuestionario
     *
     * @return Response
     */
    public function preview(Questionary $cuestionario) : Response
    {
        $deleteForm = $this->createDeleteForm($cuestionario);

        return $this->render('questionary/show.html.twig', [
            'questionary' => $cuestionario,
            'hasControlAccess' => true,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a questionary entity.
     *
     * @param Questionary $cuestionario
     *
     * @return FormInterface
     */
    private function createDeleteForm(Questionary $cuestionario) : FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('questionary.delete', ['token' => $cuestionario->getToken()]))
            ->setMethod('DELETE')
            ->getForm();
    }


    /**
     * Delete a questionary entity.
     *
     * @Route("questionary/{token}/delete", name="questionary.delete", methods="DELETE", requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Questionary $cuestionario
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Questionary $cuestionario, EntityManagerInterface $em) : Response
    {
        $form = $this->createDeleteForm($cuestionario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($cuestionario);
            $em->flush();
        }

        return $this->redirectToRoute('questionary.list');
    }


    /**
     * Finds and displays a questionary entity.
     *
     * @Route("/export/{id}", name="questionary.export", requirements={"id":"\d+"})
     *
     * @param Questionary $questionary
     *
     * @return Response
     */
    public function export(Questionary $questionary) : Response
    {
        $this->denyAccessUnlessGranted('QUESTIONARY_OWNER', $questionary);

        $documento = new Spreadsheet();

        $iddocumento = $questionary->getId();
        $namedocumento = $questionary->getName();

        $documento
            ->getProperties()
            ->setCreator("UCOQUIZZ")
            ->setTitle('Preguntas exportadas por UCOQUIZZ')
            ->setDescription('Este documento fue generado por la aplicacion UCOQUIZZ')
            ->setKeywords('preguntas questionary exportar UCOQUIZZ');

        $question = $questionary->getQuestion();

        $hoja = $documento->getActiveSheet();
        $hoja->setTitle("CUESTIONARIO UCOQUIZZ");

        $nquestion= count($question)-1;

        for($i=0; $i<=$nquestion ;$i++ ) {
            $var=$i+2;
            $hoja->setCellValue("A$var", $question[$i]->getTitle());
            $hoja->setCellValue("B$var", $question[$i]->getDuration());
            $hoja->setCellValue("C$var", $question[$i]->getAnswer1());
            $hoja->setCellValue("D$var", $question[$i]->getCheck1());
            $hoja->setCellValue("E$var", $question[$i]->getAnswer2());
            $hoja->setCellValue("F$var", $question[$i]->getCheck2());
            $hoja->setCellValue("G$var", $question[$i]->getAnswer3());
            $hoja->setCellValue("H$var", $question[$i]->getCheck3());
            $hoja->setCellValue("I$var", $question[$i]->getAnswer4());
            $hoja->setCellValue("J$var", $question[$i]->getCheck4());
            $hoja->setCellValue("K$var", $question[$i]->getDuration());

        }
        $nombreDelDocumento = "cuestionario_$iddocumento"."_$namedocumento";
        /**
         * Los siguientes encabezados son necesarios para que
         * el navegador entienda que no le estamos mandando
         * simple HTML
         * Por cierto: no hagas ningún echo ni cosas de esas; es decir, no imprimas nada
         */

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($documento, 'Xlsx');
        $writer->save('php://output');
        exit;



    }
}
