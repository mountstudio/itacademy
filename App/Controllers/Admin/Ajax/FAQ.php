<?php

namespace App\Controllers\Admin\Ajax;

use \Core\View;
use \Core\Helper;
use \Core\JsonResponse;
use \Core\CustomException;
use \Core\Image;
use \Core\Functions;
use \Core\Mail;
use App\Config;

use \Models\FAQQuery;

use \Models\Group;
use \Models\GroupQuery;



use \Models\VerificationToken;
use \Models\VerificationTokenQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class FAQ extends Base
{

    public function listAction()
    {
        $this->response = new JsonResponse($pagination = true);
        try {
            $this->helper->shouldHavePrivilege('FAQ_ADMIN');
            $paginator = $this->helper->paginator();
            $faqs = FAQQuery::create()->orderBySortableRank('asc')->paginate($page = $paginator['page'], $maxPerPage = $paginator['max']);

            $this->response->setPaginationDetails($faqs);
            $faqsData = array();
            foreach ($faqs as $faq) {
                $faqsData[] = array('id' => $faq->getId(),
                    'question' => $faq->getQuestion(),
                    'answer' => strip_tags($faq->getAnswer()),
                    'actions' => array(
                        'isFirst' => $faq->isFirst(),
                        'isLast' => $faq->isLast()
                    ),
                    'createdAt' => $faq->getCreatedAt(),
                    'updatedAt' => $faq->getUpdatedAt()
                );
            }
            $this->response->setData($faqsData);
            $this->response->setStatus(JsonResponse::SUCCESS);
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function deleteAction()
    {
        $faqId = (isset($_POST['id']) ? $_POST['id'] : null);
        try {
            $this->helper->shouldHavePrivilege('FAQ_ADMIN');

            if (is_null($faqId) || intval($faqId) == 0){
                throw new CustomException("ID FAQ не был указан", 1);
            }

            $faq = FAQQuery::create()->findPK(intval($faqId));
            if (is_null($faq)){
                throw new CustomException("FAQ не найден", 1);
            }

            $faq->delete();
            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage('FAQ успешно удален');
            $fromEditAction = (isset($_POST['fromEditAction']) ? $_POST['fromEditAction'] : null);
            if (!is_null($fromEditAction) && $fromEditAction == 'true'){
                $this->response->setRedirect('/admin/faqs/all');
            }
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function editAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $question = (isset($_POST['question']) ? $_POST['question'] : null);
        $answer = (isset($_POST['answer']) ? $_POST['answer'] : null);

        try {
            $this->helper->shouldHavePrivilege('FAQ_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID FAQ не был указан", 1);
            }

            $faq = FAQQuery::create()->findPK(intval($id));
            if (is_null($faq)){
                throw new CustomException("FAQ не найден", 1);
            }

            if (is_null($question) || empty(trim($question)) ){
                throw new CustomException("Вопрос не был введен", 1);
            }

            if (is_null($answer) || empty(trim($answer)) ){
                throw new CustomException("Ответ не был введен", 1);
            }

            if (trim($question) != $faq->getQuestion()){
                $faqByQuestion = FAQQuery::create()->findOneByQuestion(trim($question));
                if (!is_null($faqByQuestion)) {
                    throw new CustomException("Такой вопрос существует", 1);


                }
            }

            $faq->setQuestion(trim($question));
            $faq->setAnswer(trim($answer));
            $faq->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("FAQ успешно сохранен");
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function updateAction()
    {
        $id = (isset($_POST['id']) ? $_POST['id'] : null);
        $action = (isset($_POST['action']) ? $_POST['action'] : null);
        try {
            $this->helper->shouldHavePrivilege('FAQ_ADMIN');

            if (is_null($id) || intval($id) == 0){
                throw new CustomException("ID FAQ неверный", 1);
            }

            $faq = FAQQuery::create()->findPK($id);
            if (is_null($faq)){
                throw new CustomException("FAQ не найден", 1);
            }


            switch ($action){
                case 'toTop':
                    $faq->moveUp();
                    $this->response->setMessage("FAQ успешно перемещен вверх", 0);
                    break;
                case 'toBottom':
                    $faq->moveDown();
                    $this->response->setMessage("FAQ успешно перемещен вниз", 0);
                    break;
                default:
                    throw new CustomException("Неправильное действие", 1);
            }

            $this->response->setStatus(JsonResponse::SUCCESS);

        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

    public function addAction()
    {
        $question = (isset($_POST['question']) ? $_POST['question'] : null);
        $answer = (isset($_POST['answer']) ? $_POST['answer'] : null);

        try {
            $this->helper->shouldHavePrivilege('FAQ_ADMIN');

            if (is_null($question) || empty(trim($question)) ){
                throw new CustomException("Вопрос не был введен", 1);
            }

            if (is_null($answer) || empty(trim($answer)) ){
                throw new CustomException("Ответ не был введен", 1);
            }

            $faqByQuestion = FAQQuery::create()->findOneByQuestion(trim($question));
            if (!is_null($faqByQuestion)) {
                throw new CustomException("Такой вопрос существует", 1);
            }

            $faq = new \Models\FAQ();

            $faq->setQuestion(trim($question));
            $faq->setAnswer(trim($answer));
            $faq->save();

            $this->response->setStatus(JsonResponse::SUCCESS);
            $this->response->setMessage("FAQ успешно создан");
            $this->response->setRedirect('/admin/faqs/all');
        } catch (CustomException $e) {
            $this->response->setException($e);
        }

        $this->response->show();
    }

}
