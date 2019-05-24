<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questionary;
use App\Form\QuestionaryType;
use App\Form\QuestionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use App\DTO\FileUpdated;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Form\FileImpType;

class QuestionaryController extends AbstractController
{


    /**
     * Lists all questionary entities.
     *
     * @Route("/", name="questionary.list", methods="GET")
     *
     * @return Response
     */
    public function list(): Response
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
    public function show(Questionary $questionary): Response
    {
        $question = $questionary->getQuestion();
        $deleteForm = $this->createDeleteForm($questionary);

        return $this->render('questionary/show.html.twig', [
            'questionary' => $questionary,
            'question' => $question,
            'deleteForm' => $deleteForm->createView(),
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
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $questionary = new Questionary();
        $form = $this->createForm(QuestionaryType::class, $questionary);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $questionary->setUser($this->getUser());

            $em->persist($questionary);
            $em->flush();

            return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);        }

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
    public function edit(Request $request, Questionary $questionary, EntityManagerInterface $em): Response
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
    public function preview(Questionary $questionary): Response
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
    private function createDeleteForm(Questionary $questionary): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('questionary.delete', ['id' => $questionary->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }


    /**
     * Delete a questionary entity.
     *
     * @Route("questionary/{id}/delete", name="questionary.delete", methods="DELETE", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param Questionary $questionary
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Questionary $questionary, EntityManagerInterface $em): Response
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
    public function export(Questionary $questionary): Response
    {
        $this->denyAccessUnlessGranted('QUESTIONARY_OWNER', $questionary);

        $spreadsheet = new Spreadsheet();

        $iddocument = $questionary->getId();
        $namedocument = $questionary->getName();

        $spreadsheet
            ->getProperties()
            ->setCre2341a0cc82f47141db04ator("UCOQUIZZ")
            ->setTitle('Preguntas exportadas por UCOQUIZZ')
            ->setDescription('Este documento fue generado por la aplicacion UCOQUIZZ')
            ->setKeywords('preguntas questionary exportar UCOQUIZZ');

        $question = $questionary->getQuestion();

        $activeSheet = $spreadsheet->getActiveSheet();
        $activeSheet->setTitle("CUESTIONARIO UCOQUIZZ");

        $nquestion = count($question) - 1;

        $activeSheet->setCellValue("A1", "Título");
        $activeSheet->setCellValue("B1", "Respuesta 1");
        $activeSheet->setCellValue("C1", "Respuesta 2");
        $activeSheet->setCellValue("D1", "Respuesta 3");
        $activeSheet->setCellValue("E1", "Respuesta 4");
        $activeSheet->setCellValue("F1", "Correcta");
        $activeSheet->setCellValue("G1", "Duración");


        for ($i = 0; $i <= $nquestion; $i++) {
            $var = $i + 2;
            $activeSheet->setCellValue("A$var", $question[$i]->getTitle());

            $answer = $question[$i]->getAnswer();

            $activeSheet->setCellValue("B$var", $answer[0]->getAnswertitle());
            if($answer[0]->getCorrect()=="1"){
                $activeSheet->setCellValue("F$var", 1);
            }
            $activeSheet->setCellValue("C$var", $answer[1]->getAnswertitle());
            if($answer[1]->getCorrect()=="1"){
                $activeSheet->setCellValue("F$var", 2);
            }
            $activeSheet->setCellValue("D$var", $answer[2]->getAnswertitle());
            if($answer[2]->getCorrect()=="1"){
                $activeSheet->setCellValue("F$var", 3);
            }
            $activeSheet->setCellValue("E$var", $answer[3]->getAnswertitle());
            if($answer[3]->getCorrect()=="1"){
                $activeSheet->setCellValue("F$var", 4);
            }
            $activeSheet->setCellValue("G$var", $question[$i]->getDuration());

        }
        $documentname = "cuestionario_$iddocument" . "_$namedocument".".xlsx";
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

        return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);

        exit;

    }

    /**
     * Finds and displays a questionary entity.
     *
     * @Route("/import/{id}", name="questionary.import", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param Questionary $questionary
     */



    public function new(Request $request, EntityManagerInterface $em, Questionary $questionary)
    {

        $this->denyAccessUnlessGranted('QUESTIONARY_OWNER', $questionary);

        $product = new FileUpdated();
        $form = $this->createForm(FileImpType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $file stores the uploaded PDF file
            /** @var symfony/http-foundation/File/File.php $file */
            $file = $product->getFileupdate();

            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            try {


                $reader = IOFactory::createReaderForFile($file);

                $locations = $reader->load($file)->getSheet(0);

            } catch (FileException $e) {
                throw new InvalidArgumentException(sprintf('El fichero especificado (%s) no existe.', $file));
            }

            // updates the 'passwordgame' property to store the PDF file name
            // instead of its contents

            $question = new Question();
            $answer[0] =new Answer();
            $answer[1] =new Answer();
            $answer[2] =new Answer();
            $answer[3] =new Answer();

            foreach ($locations->getRowIterator(2) as $location) {
                $rowIndex = $location->getRowIndex();

                $question->setTitle($locations->getCellByColumnAndRow(1, $rowIndex));

                $question->setDuration($locations->getCellByColumnAndRow(7, $rowIndex)->getFormattedValue());

                $answer[0]->setAnswertitle($locations->getCellByColumnAndRow(2, $rowIndex));
                    if ($locations->getCellByColumnAndRow(6, $rowIndex)->getFormattedValue()==1){
                        $answer[0]->setCorrect(1);
                    }else{
                        $answer[0]->setCorrect(0);
                    }
                $question->addAnswer($answer[0]);

                $answer[1]->setAnswertitle($locations->getCellByColumnAndRow(3, $rowIndex));
                    if ($locations->getCellByColumnAndRow(6, $rowIndex)->getFormattedValue()==2){
                        $answer[1]->setCorrect(1);
                    }else{
                        $answer[1]->setCorrect(0);
                    }
                $question->addAnswer($answer[1]);

                $answer[2]->setAnswertitle($locations->getCellByColumnAndRow(4, $rowIndex));
                    if ($locations->getCellByColumnAndRow(6, $rowIndex)->getFormattedValue()==3){
                        $answer[2]->setCorrect(1);
                    }else{
                        $answer[2]->setCorrect(0);
                    }
                $question->addAnswer($answer[2]);


                $answer[3]->setAnswertitle($locations->getCellByColumnAndRow(5, $rowIndex));
                    if ($locations->getCellByColumnAndRow(6, $rowIndex)->getFormattedValue()==4){
                        $answer[3]->setCorrect(1);
                    }else{
                        $answer[3]->setCorrect(0);
                    }

                $question->addAnswer($answer[3]);

                $question->setQuestionary($questionary);

                $em->persist($question);
                $em->flush();

            }


            // ... persist the $product variable or any other work

            return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }



}