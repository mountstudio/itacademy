<?php

namespace App\Controllers\Front;

use \Core\View;
use Core\CustomException;
use Models\VacancyQuery;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Vacancy extends Base
{

    /**
     * Show the index page
     *
     * @return void
     */


     public function indexAction()
     {
         try {

             $vacancies = VacancyQuery::create()->orderBySortableRank()->find();

             $this->breadcrumbs->appendItem('Вакансии', '/vacancies');
             $this->breadcrumbs->setOnRightSide(false);

             $this->data = array_merge( $this->data,
                 array(
                     'vacancies' => $vacancies
                 )
             );

             View::renderTemplate('Front/vacancies.html', $this->data);

         } catch (CustomException $e) {
             if ($e->getCode() == 404){
                 http_response_code(404);
                 View::renderTemplate('Front/404.html');
             } else {
                 $this->data = array_merge(  $this->data,
                                             $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
                   );
                 View::renderTemplate('Front/messagePage.html', $this->data);
             }
         }
     }

    public function singleAction()
    {
        $altUrl = (isset($this->params['alturl']) ? $this->params['alturl'] : null);
        try {

            $vacancy = VacancyQuery::create()->findOneByAltUrl(trim($altUrl));
            if (is_null($vacancy)){
                throw new CustomException("Вакансия не найдена", 404);
            }

            $this->breadcrumbs->appendItem('Вакансии', '/vacancies');
            $this->breadcrumbs->appendItem($vacancy->getName(), '/vacancies/' . $vacancy->getAltUrl());
            $this->breadcrumbs->setOnRightSide(false);

            $this->data = array_merge( $this->data,
                array(
                    'vacancy' => $vacancy
                ));

            View::renderTemplate('Front/vacancy.html', $this->data);

        } catch (CustomException $e) {
            if ($e->getCode() == 404){
                http_response_code(404);
                View::renderTemplate('Front/404.html');
            } else {
                $this->data = array_merge(  $this->data,
                    $this->helper->buildMessagePage('Ошибка 403', $e->getMessage())
                );
                View::renderTemplate('Front/messagePage.html', $this->data);
            }
        }
    }

}
