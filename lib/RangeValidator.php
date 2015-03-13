<?php

namespace Kfi\Validator;

class RangeValidator extends AbstractValidator implements ValidatorInterface {

  const OUT_OF_RANGE = 'outOfRange';

  protected $_messageTemplates = array(
    self::OUT_OF_RANGE => "This field must be at least '%min%' and at maximum '%max%' characters."
  );

  public function validate($value, $conf = array()) {
    // get min value
    $cond = is_array($conf) && array_key_exists('min', $conf) && is_numeric($conf['min']);
    $min = $cond ? (int)$conf['min'] : 0;
    $this->setMin($min);

    $cond = is_array($conf) && array_key_exists('max', $conf) && is_numeric($conf['max']);
    $max = $cond ? (int)$conf['max'] : 10;

    // validate
    if (strlen($value) < $min || strlen($value) > $max) {
      $this->setIsValid(false);
      $this->addError(self::OUT_OF_RANGE);
      $this->checkOwnMessage($conf);
    }
  }

  private function checkOwnMessage($conf) {
    if (array_key_exists('messages', $conf) && is_array($conf['messages'])) {
      foreach ($conf['messages'] as $error => $message) {
        $error = constant('self::OUT_OF_' . strtoupper($error));
        $this->_messageTemplates[$error] = wire('sanitizer')->text($message);
      }
    }
  }

}
