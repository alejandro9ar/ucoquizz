<?php

namespace App\Controller;

use App\Entity\Questionary;
use App\Entity\Question;
use App\Form\QuestionaryType;
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
        $questionaries = $this->getDoctrine()->getRepository(Questionary::class)->findAll();

        return $this->render('questionary/list.html.twig', [
            'questionarys' => $questionaries,
        ]);
    }

    /**
     * Finds and displays a questionary entity.
     *
     * @Route("/{id}", name="questionary.show", requirements={"id":"\d+"})
     *
     * @param Questionary $questionary
     *
     * @return Response
     */
    public function show(Questionary $questionary) : Response
    {

            return $this->render('questionary/show.html.twig', [
                'questionary' => $questionary,
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
        $questionary = new Questionary();
        $form = $this->createForm(QuestionaryType::class, $questionary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $questionary->setUser($this->getUser());

            $em->persist($questionary);
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
     * @param Questionary $questionary
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(Request $request, Questionary $questionary, EntityManagerInterface $em) : Response
    {

        $this->denyAccessUnlessGranted('QUESTIONARY_OWNER', $questionary);

        $form = $this->createForm(QuestionaryType::class, $questionary);
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
     * @param Questionary $questionary
     *
     * @return Response
     */
    public function preview(Questionary $questionary) : Response
    {
        $deleteForm = $this->createDeleteForm($questionary);

        return $this->render('questionary/show.html.twig', [
            'questionary' => $questionary,
            'hasControlAccess' => true,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a questionary entity.
     *
     * @param Questionary $questionary
     *
     * @return FormInterface
     */
    private function createDeleteForm(Questionary $questionary) : FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('questionary.delete', ['token' => $questionary->getToken()]))
            ->setMethod('DELETE')
            ->getForm();
    }


    /**
     * Delete a questionary entity.
     *
     * @Route("questionary/{token}/delete", name="questionary.delete", methods="DELETE", requirements={"token" = "\w+"})
     *
     * @param Request $request
     * @param Questionary $questionary
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Questionary $questionary, EntityManagerInterface $em) : Response
    {

        $this->denyAccessUnlessGranted('QUESTIONARY_OWNER', $questionary);

        $form = $this->createDeleteForm($questionary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($questionary);
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

        $spreadsheet = new Spreadsheet();

        $iddocument = $questionary->getId();
        $namedocument = $questionary->getName();

        $spreadsheet
            ->getProperties()
            ->setCreator("UCOQUIZZ")
            ->setTitle('Preguntas exportadas por UCOQUIZZ')
            ->setDescription('Este documento fue generado por la aplicacion UCOQUIZZ')
            ->setKeywords('preguntas questionary exportar UCOQUIZZ');

        $question = $questionary->getQuestion();

        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle("CUESTIONARIO UCOQUIZZ");

        $nquestion= count($question)-1;

        $activeSheet->setCellValue("A1", "Título");
        $activeSheet->setCellValue("B1", "Descripción");
        $activeSheet->setCellValue("C1", "Respuesta 1");
        $activeSheet->setCellValue("D1", "Check 1");
        $activeSheet->setCellValue("E1", "Respuesta 2");
        $activeSheet->setCellValue("F1", "Check 2");
        $activeSheet->setCellValue("G1", "Respueta 3");
        $activeSheet->setCellValue("H1", "Check 3");
        $activeSheet->setCellValue("I1", "Respuesta 4");
        $activeSheet->setCellValue("J1", "Check 4");
        $activeSheet->setCellValue("K1", "Duración");
        
        for($i=0; $i<=$nquestion ;$i++ ) {
            $var=$i+2;
            $activeSheet->setCellValue("A$var", $question[$i]->getTitle());
            $activeSheet->setCellValue("B$var", $question[$i]->getDescription());
            $activeSheet->setCellValue("C$var", $question[$i]->getAnswer1());
            $activeSheet->setCellValue("D$var", $question[$i]->getCheck1());
            $activeSheet->setCellValue("E$var", $question[$i]->getAnswer2());
            $activeSheet->setCellValue("F$var", $question[$i]->getCheck2());
            $activeSheet->setCellValue("G$var", $question[$i]->getAnswer3());
            $activeSheet->setCellValue("H$var", $question[$i]->getCheck3());
            $activeSheet->setCellValue("I$var", $question[$i]->getAnswer4());
            $activeSheet->setCellValue("J$var", $question[$i]->getCheck4());
            $activeSheet->setCellValue("K$var", $question[$i]->getDuration());

        }
        $documentname = "cuestionario_$iddocument"."_$namedocument";
        /**
         * Los siguientes encabezados son necesarios para que
         * el navegador entienda que no le estamos mandando
         * simple HTML
         * Por cierto: no hagas ningún echo ni cosas de esas; es decir, no imprimas nada
         */

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $documentname . '"');
        header('Cache-Control: max-age=0');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
        exit;



    }
}
