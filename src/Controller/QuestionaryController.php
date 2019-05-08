<?php

namespace App\Controller;

use App\Entity\Questionary;
use App\Entity\Answer;
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
            $question = $questionary->getQuestion();

            return $this->render('questionary/show.html.twig', [
                'questionary' => $questionary,
                'question'=> $question,
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
     * @Route("/questionary/{id}/edit", name="questionary.edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
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

        $this->denyAccessUnlessGranted('QUESTION_OWNER', $questionary);

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
        $activeSheet->setCellValue("B1", "Respuesta 1");
        $activeSheet->setCellValue("C1", "Check 1");
        $activeSheet->setCellValue("D1", "Respuesta 2");
        $activeSheet->setCellValue("E1", "Check 2");
        $activeSheet->setCellValue("F1", "Respueta 3");
        $activeSheet->setCellValue("G1", "Check 3");
        $activeSheet->setCellValue("H1", "Respuesta 4");
        $activeSheet->setCellValue("I1", "Check 4");
        $activeSheet->setCellValue("J1", "Duración");


        for($i=0; $i<=$nquestion ;$i++ ) {
            $var=$i+2;
            $activeSheet->setCellValue("A$var", $question[$i]->getTitle());

            $answer = $question[$i]->getAnswer();

            $activeSheet->setCellValue("B$var", $answer[0]->getAnswertitle());
            $activeSheet->setCellValue("C$var", $answer[0]->getCorrect());
            $activeSheet->setCellValue("D$var", $answer[1]->getAnswertitle());
            $activeSheet->setCellValue("E$var", $answer[1]->getCorrect());
            $activeSheet->setCellValue("F$var", $answer[2]->getAnswertitle());
            $activeSheet->setCellValue("G$var", $answer[2]->getCorrect());
            $activeSheet->setCellValue("H$var", $answer[3]->getAnswertitle());
            $activeSheet->setCellValue("I$var", $answer[3]->getCorrect());

            $activeSheet->setCellValue("J$var", $question[$i]->getDuration());

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

        return $this->render('questionary/exported.html.twig', [
            'questionary' => $questionary,
        ]);

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');



        exit;





    }
}
