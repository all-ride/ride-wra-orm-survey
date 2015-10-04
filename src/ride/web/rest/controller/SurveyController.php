<?php

namespace ride\web\rest\controller;

use ride\library\orm\OrmManager;

use ride\web\rest\controller\OrmModelController;

class SurveyController extends OrmModelController {

    public function surveyEvaluateAction(OrmManager $orm, $evaluation, $entry) {
        $entryModel = $orm->getSurveyEntryModel();
        $evaluationModel = $orm->getSurveyEvaluationModel();
        $ruleModel = $orm->getSurveyEvaluationRuleModel();

        $evaluation = $evaluationModel->getById($evaluation);
        if (!$evaluation) {
            $this->response->setNotFound();

            return;
        }

        $entry = $entryModel->getById($entry);
        if (!$entry) {
            $this->response->setNotFound();

            return;
        }

        $result = $evaluation->evaluate($entry);

        $this->document->setLink('self', $this->request->getUrl());
        $this->document->setResourceData($ruleModel->getMeta()->getOption('json.api'), $result->getRule());
        $this->document->setMeta('score', $result->getScore());
    }

    public function likertCompatibleAction(OrmManager $orm, $likert1, $likert2) {
        $likertModel = $orm->getSurveyLikertModel();

        $likert1 = $likertModel->getById($likert1);
        if (!$likert1) {
            $this->response->setNotFound();

            return;
        }

        $likert2 = $likertModel->getById($likert2);
        if (!$likert2) {
            $this->response->setNotFound();

            return;
        }

        $result = $likert1->isCompatible($likert2);

        $this->document->setLink('self', $this->request->getUrl());
        $this->document->setMeta('compatible', $result);
    }

}