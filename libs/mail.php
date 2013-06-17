<?php

class Mail
{
    const REGEX_EMAIL = '/^\w[-._\w]*\w@\w[-._\w]*\w\.\w{2,3}$/';

    private $receivers;
    private $subject;
    private $message;

    function __construct()
    {
        $this->receivers = array();
    }


    public function addReceiver($email)
    {
        if (!preg_match(self::REGEX_EMAIL, $email)) {
            throw new Exception('The email address must be standard. (i.e. infos@dutailier.com.');
        }

        $this->receivers[] = strtolower($email);
    }

    public function removeReceiver($email)
    {
        foreach ($this->receivers as $index => $value) {
            if (strtolower($email) == $value) {
                unset($this->receivers[$index]);
            }
        }
    }

    public function getReceivers()
    {
        return $this->receivers;
    }

    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    public function getSubject()
    {
        return $this->subject;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function Send()
    {
        $to = $this->receivers[0];

        for ($i = 1; $i < count($this->receivers); $i++) {
            $to .= ', ' . $this->receivers[$i];
        }

        return mail($to, $this->subject, $this->message);
    }
}