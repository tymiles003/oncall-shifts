<?php

use Nette\Application\UI;

class HomepagePresenter extends BasePresenter {

    public function renderDefault($date = 'today') {
        $date = new \Nette\DateTime($date);
        $first = new \Nette\DateTime($date->format('Y-m-01'));
        $last = clone $first;
        $last->add(new DateInterval('P1M'));

        for ($day = $first; $day < $last; $day->add(new DateInterval('P1D'))) {
                $days[] = clone $day;
        }
        $this->template->days = $days;
        $this->template->hours = range(0, 23);
        $this->template->date = $date;
        $previous = clone $date;
        $previous->sub(new DateInterval('P1M'));
        $next = clone $date;
        $next->add(new DateInterval('P1M'));
        $this->template->previous = $previous->format('Ymd');
        $this->template->next = $next->format('Ymd');

        $db = $this->context->database;
        $this->template->people = $db->table('people')->fetchPairs('id', 'name');

    }

    public function createComponentShiftFormsContainer() {
        $db = $this->context->database;
        $people = $db->table('people')->fetchPairs('id', 'name');
        foreach ($people as $id => $name) {
            preg_match_all('#(?<=\s|\b)\pL#u', $name, $initials);
            $people[$id] = implode('', $initials[0]) . "   $name";
        }
        $shifts = array();
        foreach ($db->table('shifts') as $shift) {
            $shifts[$shift->day_hour->__toString()] = $shift;
        }
        //dump($shifts);
        return new UI\Multiplier(function($day_hour) use ($db, $people, $shifts) {
            list($year, $month, $day, $hour) = explode('_', $day_hour);
            $dt = new \Nette\DateTime("$year-$month-$day $hour:00:00");
            //dump($dt->__toString());

            return new ShiftForm($db, $dt, $people, $shifts);
        });
    }

    function createComponentSign($name) {
        return new SignControl($this, $name);
    }
}
