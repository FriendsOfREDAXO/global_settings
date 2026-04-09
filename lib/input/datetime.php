<?php

namespace FriendsOfRedaxo\GlobalSettings\Input;

class Datetime extends Input
{
    private $dateInput;
    private $timeInput;

    public function __construct()
    {
        parent::__construct();

        $this->dateInput = \FriendsOfRedaxo\GlobalSettings\Input\Input::factory('date');
        $this->timeInput = \FriendsOfRedaxo\GlobalSettings\Input\Input::factory('time');
    }

    public function setValue($value)
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Expecting $value to be an array!');
        }

        $this->dateInput->setValue($value);
        $this->timeInput->setValue($value);

        parent::setValue($value);
    }

    public function getValue()
    {
        return array_merge($this->dateInput->getValue(), $this->timeInput->getValue());
    }

    public function setAttribute($name, $value)
    {
        $this->dateInput->setAttribute($name, $value);
        $this->timeInput->setAttribute($name, $value);

        parent::setAttribute($name, $value);
    }

    public function getDaySelect()
    {
        return $this->dateInput->daySelect;
    }

    public function getMonthSelect()
    {
        return $this->dateInput->monthSelect;
    }

    public function getYearSelect()
    {
        return $this->dateInput->yearSelect;
    }

    public function getHourSelect()
    {
        return $this->hourSelect;
    }

    public function getMinuteSelect()
    {
        return $this->minuteSelect;
    }

    public function getHtml()
    {
        $html = '<div class="global-settings-date-wrapper">' . $this->dateInput->getHtml() . '</div>';
        $html .= '<span class="rex-form-select-separator">-</span>';
        $html .= '<div class="global-settings-time-wrapper">' . $this->timeInput->getHtml() . '</div>';
        return $html;
    }
}
