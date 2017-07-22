<?php
namespace Braintree\Exception;

use Braintree\Exception;

/**
 * Raised when authorized fails.
 * This may be caused by an incorrect Configuration
 *
 * @package    Braintree
 * @subpackage Exception
 */
class Authentication extends Exception
{

}
class_alias('Braintree\Exception\Authentication', 'Braintree_Exception_Authentication');
