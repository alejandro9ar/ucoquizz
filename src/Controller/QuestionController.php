<?php

/*
 * This file is part of the ucoquizz project.
 *
 * (c) Alejandro Arroyo Ruiz <i42arrua@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questionary;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function show(Question $question): Response
    {
        $this->denyAccessUnlessGranted('QUESTION_OWNER', $question);

        $deleteForm = $this->createDeleteForm($question);

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answer' => $question->getAnswer(),
            'deleteForm' => $deleteForm->createView(),
        ]);
    }

    /**
     * Creates a new question entity.
     *
     * @Route("/question/create/{id}", name="question.create", methods={"GET", "POST"}, requirements={"id":"\d+"})
     *
     * @param Request                $request
     * @param EntityManagerInterface $em
     *
     * @return RedirectResponse|Response
     */
    public function create(Request $request, EntityManagerInterface $em, Questionary $questionary): Response
    {
        $this->denyAccessUnlessGranted('QUESTIONARY_OWNER', $questionary);

        $question = new Question();

        $answer1 = new Answer();
        $question->getAnswer()->add($answer1);

        $answer2 = new Answer();
        $question->getAnswer()->add($answer2);

        $answer3 = new Answer();
        $question->getAnswer()->add($answer3);

        $answer4 = new Answer();
        $question->getAnswer()->add($answer4);

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setQuestionary($questionary);

            $answer1->setQuestion($question);
            $answer2->setQuestion($question);
            $answer3->setQuestion($question);
            $answer4->setQuestion($question);
            $question->setActivated(1);

            if(($answer1->getCorrect() == 1 && $answer2->getCorrect()== 0 && $answer3->getCorrect() == 0 && $answer4->getCorrect() ==0)
                || ($answer1->getCorrect() == 0 && $answer2->getCorrect()== 1 && $answer3->getCorrect() == 0 && $answer4->getCorrect() ==0)
                || ($answer1->getCorrect() == 0 && $answer2->getCorrect()== 0 && $answer3->getCorrect() == 1 && $answer4->getCorrect() ==0)
                || ($answer1->getCorrect() == 0 && $answer2->getCorrect()== 0 && $answer3->getCorrect() == 0 && $answer4->getCorrect() ==1)) {

                $em->persist($question);
                $em->flush();

            }else{
                $this->addFlash('notice', 'Selecciona (solo) una correcta');

                return $this->render('question/create.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);
        }

        return $this->render('question/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit existing question entity.
     *
     * @Route("/question/{id}/edit", name="question.edit", methods={"GET", "POST"}, requirements={"id":"\d+"})
     *
     * @param Request                $request
     * @param Question               $question
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(Request $request, Question $question, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('QUESTION_OWNER', $question);

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        $answer = $question->getAnswer();


        if ($form->isSubmitted() && $form->isValid()) {

            if(($answer[0]->getCorrect() == 1 && $answer[1]->getCorrect()== 0 && $answer[2]->getCorrect() == 0 && $answer[3]->getCorrect() ==0)
                || ($answer[0]->getCorrect() == 0 && $answer[1]->getCorrect()== 1 && $answer[2]->getCorrect() == 0 && $answer[3]->getCorrect() ==0)
                || ($answer[0]->getCorrect() == 0 && $answer[1]->getCorrect()== 0 && $answer[2]->getCorrect() == 1 && $answer[3]->getCorrect() ==0)
                || ($answer[0]->getCorrect() == 0 && $answer[1]->getCorrect()== 0 && $answer[2]->getCorrect() == 0 && $answer[3]->getCorrect() ==1)) {


                $em->persist($question);
                $em->flush();

                return $this->redirectToRoute('questionary.show', ['id' => $question->getQuestionary()->getId()]);

            }else{
                $this->addFlash('notice', 'Selecciona (solo) una correcta');

                return $this->render('question/create.html.twig', [
                    'form' => $form->createView(),
                ]);
            }
            return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);
        }

        return $this->render('question/edit.html.twig', [
            'form' => $form->createView(),
            'questionary' => $question->getQuestionary(),
        ]);
    }


    private function createDeleteForm(Question $question): FormInterface
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('question.delete', ['id' => $question->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Delete a question entity.
     *
     * @Route("question/{id}/delete", name="question.delete", methods="DELETE", requirements={"id":"\d+"})
     *
     * @param Request                $request
     * @param Question               $question
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function delete(Request $request, Question $question, EntityManagerInterface $em): Response
    {
        $questionary = $question->getQuestionary();

        $answer = $question->getAnswer();


        $question->setActivated(0);
        $em->persist($question);
        $em->flush();

        return $this->redirectToRoute('questionary.show', ['id' => $questionary->getId()]);
    }
}
