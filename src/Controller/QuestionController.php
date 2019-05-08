<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Questionary;
use App\Entity\Question;
use App\Form\QuestionType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;



class QuestionController extends AbstractController
{
    /**
     * Finds and displays a question entity.
     *
     * @Route("question/{id}", name="question.show", requirements={"id":"\d+"})
     *
     * @param Question $question
     *
     * @return Response
     */
    public function show(Question $question) : Response
    {

        return $this->render('question/show.html.twig', [
            'question' => $question,
        ]);

    }

    /**
     * Creates a new question entity.
     *
     * @Route("/question/create/{id}", name="question.create", methods={"GET", "POST"}, requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em, Questionary $questionary) : Response
    {
        $this->denyAccessUnlessGranted('QUESTIONARY_OWNER', $questionary);

        $question = new Question();

        $answer1 = new Answer();
        $answer1->setAnswertitle('answer1');
        $question->getAnswer()->add($answer1);

        $answer2 = new Answer();
        $answer2->setAnswertitle('answer2');
        $question->getAnswer()->add($answer2);

        $answer3 = new Answer();
        $answer3->setAnswertitle('answer3');
        $question->getAnswer()->add($answer3);

        $answer4 = new Answer();
        $answer4->setAnswertitle('answer4');
        $question->getAnswer()->add($answer4);


        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $question->setQuestionary($questionary);

            $answer1->setQuestion($question);
            $answer2->setQuestion($question);
            $answer3->setQuestion($question);
            $answer4->setQuestion($question);

            $em->persist($question);
            $em->flush();

            return $this->redirectToRoute('questionary.list');
        }

        return $this->render( 'question/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit existing question entity
     *
     * @Route("/question/{toke}/edit", name="question.edit", methods={"GET", "POST"}, requirements={"toke" = "\w+"})
     *
     * @param Request $request
     * @param Question $pregunta
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(Request $request, Question $pregunta, EntityManagerInterface $em) : Response
    {
        $form = $this->createForm(QuestionType::class, $pregunta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('questionary.list');
        }

        return $this->render('question/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays the preview page for a question entity.
     *
     * @Route("questions/{toke}", name="question.preview", methods="GET", requirements={"toke" = "\w+"})
     *
     * @param Question $pregunta
     *
     * @return Response
     */
    public function preview(Question $pregunta) : Response
    {
        $deleteForm = $this->createDeleteForm($pregunta);

        return $this->render('question/show.html.twig', [
            'question' => $pregunta,
            'hasControlAccess' => true,
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a form to delete a question entity.
     *
     * @param Question $pregunta
     *
     * @return FormInterface
     */
    private function createDeleteForm(Question $pregunta) : FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('question.delete', ['toke' => $pregunta->getToke()]))
            ->setMethod('DELETE')
            ->getForm();
    }


    /**
     * Delete a question entity.
     *
     * @Route("question/{toke}/delete", name="question.delete", methods="DELETE", requirements={"toke" = "\w+"})
     *
     * @param Request $request
     * @param Question $pregunta
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Question $pregunta, EntityManagerInterface $em) : Response
    {
        $form = $this->createDeleteForm($pregunta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->remove($pregunta);
            $em->flush();
        }

        return $this->redirectToRoute('questionary.list');
    }






}
