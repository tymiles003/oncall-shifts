<?php

use Nette\Application\UI;

class ShiftForm extends UI\Control
{

    protected $db;
    protected $dt;
    protected $dtstr;
    protected $people;
    protected $shifts;

    public function __construct($db, $dt, $people, $shifts) {
        parent::__construct();
        $this->db     = $db;
        $this->dt     = $dt;
        $this->dtstr  = $dt->__toString();
        $this->people = $people;
        $this->shifts = $shifts;
    }

    public function render() {
        $this->template->day_hour = $this->dt->format('Ymdh');
        $this->template->setFile(__DIR__ . '/ShiftForm.latte');
        $this->template->render();
    }

    protected function createComponent($name) {
        $form = new UI\Form($this, $name);
        $who = isset($this->shifts[$this->dtstr])
            ? $this->shifts[$this->dtstr]->people_id
            : NULL;
        $radio = $form->addSelect('who', '', $this->people)
            ->setDefaultValue($who)
            ->setPrompt('');
        //$radio->getSeparatorPrototype()->setName(NULL);

        if(!$this->presenter->user->isLoggedIn()) {
            $radio->setDisabled();
        }

        $form->addSubmit('send', 'Assign');
        $form->onSuccess[] = callback($this, 'processForm');

        return $form;
    }

    public function processForm(UI\Form $form) {
        if (!$this->presenter->user->isLoggedIn()) {
            if ($this->presenter->isAjax()) {
                $this->presenter->payload->message = 'Denied';
                $this->presenter->terminate();
            }
            return;
        }
        $this->db->exec(
            'DELETE FROM shifts WHERE', array(
                'day_hour' => $this->dt,
            )
        );
        $this->db->exec(
            'INSERT INTO shifts', array(
                'day_hour'  => $this->dt,
                'people_id' => $form->values['who'],
            )
        );
        if ($this->presenter->isAjax()) {
            $this->presenter->payload->message = 'Success';
            $this->presenter->terminate();
            return;
        }

        $this->presenter->redirect('this');
    }

}
