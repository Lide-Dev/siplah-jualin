<?php
class Conversation_exception extends Exception
{
  public function errorMessage()
  {
    $error_msg = 'Error on line ' . $this->getLine() . ' in ' . $this->getFile() . ": <b>" . $this->getMessage() . '</b>';
    return $error_msg;
  }
}
