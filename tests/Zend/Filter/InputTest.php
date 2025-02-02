<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Filter
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Filter_Input
 */
// require_once 'Zend/Filter/Input.php';

/**
 * @see Zend_Loader
 */
// require_once 'Zend/Loader.php';


/**
 * @category   Zend
 * @package    Zend_Filter
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Filter
 */
class Zend_Filter_InputTest extends PHPUnit_Framework_TestCase
{
    /**
     * @group ZF-11267
     * If we pass in a validator instance that has a preset custom message, this
     * message should be used.
     */
    function testIfCustomMessagesOnValidatorInstancesCanBeUsed()
    {
        // test with a Digits validator
        // require_once 'Zend/Validate/Digits.php';
        // require_once 'Zend/Validate/NotEmpty.php';
        $data = ['field1' => 'invalid data'];
        $customMessage = 'Hey, that\'s not a Digit!!!';
        $validator = new Zend_Validate_Digits();
        $validator->setMessage($customMessage, 'notDigits');
        $this->assertFalse($validator->isValid('foo'), 'standalone validator thinks \'foo\' is a valid digit');
        $messages = $validator->getMessages();
        $this->assertSame($messages['notDigits'], $customMessage, 'stanalone validator does not have custom message');
        $validators = ['field1' => $validator];
        $input = new Zend_Filter_Input(null, $validators, $data);
        $this->assertFalse($input->isValid(), 'invalid input is valid');
        $messages = $input->getMessages();
        $this->assertSame($messages['field1']['notDigits'], $customMessage, 'The custom message is not used');
        
        // test with a NotEmpty validator
        $data = ['field1' => ''];
        $customMessage = 'You should really supply a value...';
        $validator = new Zend_Validate_NotEmpty();
        $validator->setMessage($customMessage, 'isEmpty');
        $this->assertFalse($validator->isValid(''), 'standalone validator thinks \'\' is not empty');
        $messages = $validator->getMessages();
        $this->assertSame($messages['isEmpty'], $customMessage, 'stanalone NotEmpty validator does not have custom message');
        $validators = ['field1' => $validator];
        $input = new Zend_Filter_Input(null, $validators, $data);
        $this->assertFalse($input->isValid(), 'invalid input is valid');
        $messages = $input->getMessages();
        $this->assertSame($messages['field1']['isEmpty'], $customMessage, 'For the NotEmpty validator the custom message is not used');
    }
    
    /**
     * 
     * If setAllowEmpty(true) is called, all fields are optional, but fields with
     * a NotEmpty validator attached to them, should contain a non empty value.
     * 
     * @group ZF-9289
     */
    function testAllowEmptyTrueRespectsNotEmtpyValidators()
    {
        // require_once 'Zend/Validate/NotEmpty.php';
        // require_once 'Zend/Validate/Digits.php';
        
        $data = [
            'field1' => 'foo',
            'field2' => ''
        ];
        
        $validators = [
            'field1' => [
                new Zend_Validate_NotEmpty(),
                Zend_Filter_Input::MESSAGES => [
                    [
                        Zend_Validate_NotEmpty::IS_EMPTY => '\'field1\' is required'
                    ]
                ]
            ],
        
            'field2' => [
                new Zend_Validate_NotEmpty()
            ]
        ];
        
        $options = [Zend_Filter_Input::ALLOW_EMPTY => true];
        $input = new Zend_Filter_Input( null, $validators, $data, $options );
        $this->assertFalse($input->isValid(), 'Ouch, the NotEmpty validators are ignored!');
        
        $validators = [
            'field1' => [
                'Digits',
                ['NotEmpty', 'integer'], 
                Zend_Filter_Input::MESSAGES => [
                    1 => 
                    [
                        Zend_Validate_NotEmpty::IS_EMPTY => '\'field1\' is required'
                    ]
                ],
            ],

        ];
        
        $data = [
            'field1' => 0,
            'field2' => ''
        ];
        $options = [Zend_Filter_Input::ALLOW_EMPTY => true];
        $input = new Zend_Filter_Input( null, $validators, $data, $options );
        $this->assertFalse($input->isValid(), 'Ouch, if the NotEmpty validator is not the first rule, the NotEmpty validators are ignored !');
        
        // and now with a string 'NotEmpty' instead of an instance:
        
        $validators = [
            'field1' => [
                'NotEmpty',
                Zend_Filter_Input::MESSAGES => [
                    0 => 
                    [
                        Zend_Validate_NotEmpty::IS_EMPTY => '\'field1\' is required'
                    ]
                ],
            ],

        ];
        
        $data = [
            'field1' => '',
            'field2' => ''
        ];
        
        $options = [Zend_Filter_Input::ALLOW_EMPTY => true];
        $input = new Zend_Filter_Input( null, $validators, $data, $options );
        $this->assertFalse($input->isValid(), 'If the NotEmpty validator is a string, the NotEmpty validator is ignored !');
        
        // and now with an array
        
        $validators = [
            'field1' => [
                ['NotEmpty', 'integer'],
                Zend_Filter_Input::MESSAGES => [
                    0 => 
                    [
                        Zend_Validate_NotEmpty::IS_EMPTY => '\'field1\' is required'
                    ]
                ],
            ],

        ];
        
        $data = [
            'field1' => 0,
            'field2' => ''
        ];
        
        $options = [Zend_Filter_Input::ALLOW_EMPTY => true];
        $input = new Zend_Filter_Input( null, $validators, $data, $options );
        $this->assertFalse($input->isValid(), 'If the NotEmpty validator is an array, the NotEmpty validator is ignored !');
    } 

     /**
      * @group ZF-8446
      * The issue reports about nested error messages. This is to assure these do not occur.
      * 
      * Example:
      * Expected Result
      *      array(2) {
      *        ["field1"] => array(1) {
      *          ["isEmpty"] => string(20) "'field1' is required"
      *        }
      *        ["field2"] => array(1) {
      *          ["isEmpty"] => string(36) "Value is required and can't be empty"
      *        }
      *      }
      *  Actual Result
      *      array(2) {
      *        ["field1"] => array(1) {
      *          ["isEmpty"] => array(1) {
      *            ["isEmpty"] => string(20) "'field1' is required"
      *          }
      *        }
      *        ["field2"] => array(1) {
      *          ["isEmpty"] => array(1) {
      *            ["isEmpty"] => string(20) "'field1' is required"
      *          }
      *        }
      *      }
     */
    public function testNoNestedMessageArrays()
    {
        // require_once 'Zend/Validate/NotEmpty.php';
        $data = [
            'field1' => '',
            'field2' => ''
        ];
        
        $validators = [
            'field1' => [
                new Zend_Validate_NotEmpty(),
                Zend_Filter_Input::MESSAGES => [
                    [
                        Zend_Validate_NotEmpty::IS_EMPTY => '\'field1\' is required'
                    ]
                ]
            ],
        
            'field2' => [
                new Zend_Validate_NotEmpty()
            ]
        ];
        
        $input = new Zend_Filter_Input( null, $validators, $data );
        
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertFalse(is_array($messages['field1']['isEmpty']), 'oh oh, we  may have got nested messages');
        $this->assertTrue(isset($messages['field1']['isEmpty']), 'oh no, we not even got the normally expected messages');
    }
    
    /**
     * @group ZF-11142, ZF-8446, ZF-9289
     */
    public function testTwoValidatorsInChainShowCorrectError()
    {
        // require_once 'Zend/Validate/NotEmpty.php';
        // require_once 'Zend/Validate/Float.php';
        $validators = [
            'field1'  => [
                    'NotEmpty', 'Float',
                    'presence'  => 'required',
                    'messages'  => [
                        'Field1 is empty',
                        [Zend_Validate_Float::NOT_FLOAT => "Field1 must be a number."]
                    ]
                ],
            'field2'    => [
                    'presence' => 'required'
                ]
        ];
        
        $data = ['field1' => 0.0, 'field2' => ''];
        $input = new Zend_Filter_Input(null, $validators, $data);
        $this->assertFalse($input->isValid());
        $messages = $input->getMessages();
        $this->assertSame($messages['field2']["isEmpty"], "You must give a non-empty value for field 'field2'");
        $this->assertSame('Field1 is empty', $messages['field1'][Zend_Validate_NotEmpty::IS_EMPTY], 'custom message not shown');
    }

    public function testFilterDeclareSingle()
    {
        $data = [
            'month' => '6abc '
        ];
        $filters = [
            'month' => 'digits'
        ];
        $input = new Zend_Filter_Input($filters, null, $data);

        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $month = $input->month;
        $this->assertEquals('6', $month);
    }

    public function testFilterDeclareByObject()
    {
        $data = [
            'month' => '6abc '
        ];
        // Zend_Loader::loadClass('Zend_Filter_Digits');
        $filters = [
            'month' => [new Zend_Filter_Digits()]
        ];
        $input = new Zend_Filter_Input($filters, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $month = $input->month;
        $this->assertEquals('6', $month);
    }

    public function testFilterDeclareByArray()
    {
        $data = [
            'month' => '_6_'
        ];
        $filters = [
            'month' => [
                ['StringTrim', '_']
            ]
        ];
        $input = new Zend_Filter_Input($filters, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $month = $input->month;
        $this->assertEquals('6', $month);
    }

    public function testFilterDeclareByChain()
    {
        $data = [
            'field1' => ' ABC '
        ];
        $filters = [
            'field1' => ['StringTrim', 'StringToLower']
        ];
        $input = new Zend_Filter_Input($filters, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals('abc', $input->field1);
    }

    public function testFilterWildcardRule()
    {
        $data = [
            'field1'  => ' 12abc ',
            'field2'  => ' 24abc '
        ];
        $filters = [
            '*'       => 'stringTrim',
            'field1'  => 'digits'
        ];
        $input = new Zend_Filter_Input($filters, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals('12', $input->field1);
        $this->assertEquals('24abc', $input->field2);
    }

    public function testFilterMultiValue()
    {
        $data = [
            'field1' => ['FOO', 'BAR', 'BaZ']
        ];
        $filters = [
            'field1' => 'StringToLower'
        ];
        $input = new Zend_Filter_Input($filters, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $f1 = $input->field1;
        $this->assertTrue(is_array($f1));
        $this->assertEquals(['foo', 'bar', 'baz'], $f1);
    }

    public function testValidatorSingle()
    {
        $data = [
            'month' => '6'
        ];
        $validators = [
            'month' => 'digits'
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $month = $input->month;
        $this->assertEquals('6', $month);
    }

    public function testValidatorSingleInvalid()
    {
        $data = [
            'month' => '6abc '
        ];
        $validators = [
            'month' => 'digits'
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['month'], array_keys($messages));
        $this->assertTrue(is_array($messages['month']));
        $this->assertEquals("'6abc ' must contain only digits", current($messages['month']));

        $errors = $input->getErrors();
        $this->assertTrue(is_array($errors));
        $this->assertEquals(['month'], array_keys($errors));
        $this->assertTrue(is_array($errors['month']));
        $this->assertEquals("notDigits", $errors['month'][0]);
    }

    public function testValidatorDeclareByObject()
    {
        $data = [
            'month' => '6'
        ];
        // Zend_Loader::loadClass('Zend_Validate_Digits');
        $validators = [
            'month' => [
                new Zend_Validate_Digits()
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $month = $input->month;
        $this->assertEquals('6', $month);
    }

    public function testValidatorDeclareByArray()
    {
        $data = [
            'month' => '6',
            'month2' => 13
        ];
        $validators = [
            'month' => [
                'digits',
                ['Between', 1, 12]
            ],
            'month2' => [
                'digits',
                ['Between', 1, 12]
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $month = $input->month;
        $this->assertEquals('6', $month);

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['month2'], array_keys($messages));
        $this->assertEquals("'13' is not between '1' and '12', inclusively", current($messages['month2']));
    }

    public function testValidatorChain()
    {
        $data = [
            'field1' => '50',
            'field2' => 'abc123',
            'field3' => 150,
        ];
        // Zend_Loader::loadClass('Zend_Validate_Between');
        $btw = new Zend_Validate_Between(1, 100);
        $validators = [
            'field1' => ['digits', $btw],
            'field2' => ['digits', $btw],
            'field3' => ['digits', $btw]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field2', 'field3'], array_keys($messages));
        $this->assertTrue(is_array( $messages['field2']));
        $this->assertTrue(is_array($messages['field3']));
        $this->assertEquals("'abc123' must contain only digits", current($messages['field2']));
        $this->assertEquals("'150' is not between '1' and '100', inclusively",
            current($messages['field3']));
    }

    public function testValidatorInvalidFieldInMultipleRules()
    {
        $data = [
            'field2' => 'abc123',
        ];
        // Zend_Loader::loadClass('Zend_Validate_Between');
        $validators = [
            'field2a' => [
                'digits',
                'fields' => 'field2'
            ],
            'field2b' => [
                new Zend_Validate_Between(1, 100),
                'fields' => 'field2'
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field2a', 'field2b'], array_keys($messages));
        $this->assertTrue(is_array($messages['field2a']));
        $this->assertTrue(is_array($messages['field2b']));
        $this->assertEquals("'abc123' must contain only digits",
            current($messages['field2a']));
        $this->assertEquals("'abc123' is not between '1' and '100', inclusively",
            current($messages['field2b']));
    }

    public function testValidatorWildcardRule()
    {
        $data = [
            'field1'  => '123abc',
            'field2'  => '246abc'
        ];
        $validators = [
            '*'       => 'alnum',
            'field1'  => 'digits'
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertNull($input->field1);
        $this->assertEquals('246abc', $input->field2);
    }

    public function testValidatorMultiValue()
    {
        $data = [
            'field1' => ['abc', 'def', 'ghi'],
            'field2' => ['abc', '123']
        ];
        $validators = [
            'field1' => 'alpha',
            'field2' => 'alpha'
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field2'], array_keys($messages));
        $this->assertEquals("'123' contains non alphabetic characters",
            current($messages['field2']));
    }

    public function testValidatorMultiField()
    {
        $data = [
            'password1' => 'EREIAMJH',
            'password2' => 'EREIAMJH',
            'password3' => 'VESPER'
        ];
        $validators = [
            'rule1' => [
                'StringEquals',
                'fields' => ['password1', 'password2']
            ],
            'rule2' => [
                'StringEquals',
                'fields' => ['password1', 'password3']
            ]
        ];
        $options = [
            Zend_Filter_Input::INPUT_NAMESPACE => 'TestNamespace'
        ];

        $ip = get_include_path();
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files';
        $newIp = $dir . PATH_SEPARATOR . $ip;
        set_include_path($newIp);

        $input = new Zend_Filter_Input(null, $validators, $data, $options);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        set_include_path($ip);
        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['rule2'], array_keys($messages));
        $this->assertEquals("Not all strings in the argument are equal",
            current($messages['rule2']));
    }

    /**
     * @group ZF-6711
     *
     */
    public function testValidatorMultiFieldAllowEmptyProcessing()
    {
        $data = [
            'password1' => 'EREIAMJH',
            'password2' => 'EREIAMJH',
            'password3' => '',
            'password4' => ''
        ];
        $validators = [
            'rule1' => [
                'StringEquals',
                'fields' => ['password1', 'password2']
            ],
            'rule2' => [
                Zend_Filter_Input::ALLOW_EMPTY => false,
                'StringEquals',
                'fields' => ['password1', 'password3']
            ],
            'rule3' => [
                Zend_Filter_Input::ALLOW_EMPTY => false,
                'StringEquals',
                'fields' => ['password3', 'password4']
            ]
        ];
        $options = [
            Zend_Filter_Input::INPUT_NAMESPACE => 'TestNamespace'
        ];

        $ip = get_include_path();
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files';
        $newIp = $dir . PATH_SEPARATOR . $ip;
        set_include_path($newIp);

        $input = new Zend_Filter_Input(null, $validators, $data, $options);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        set_include_path($ip);
        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['rule2', 'rule3'], array_keys($messages));
        $this->assertEquals(['isEmpty' => "You must give a non-empty value for field 'password3'"],
                            $messages['rule2']);
        $this->assertEquals(['isEmpty' => "You must give a non-empty value for field 'password3'",
                                          0 => "You must give a non-empty value for field 'password4'"
                                 ],
                            $messages['rule3']);
    }

    public function testValidatorBreakChain()
    {
        $data = [
            'field1' => '150',
            'field2' => '150'
        ];

        // Zend_Loader::loadClass('Zend_Validate_Between');

        $btw1 = new Zend_Validate_Between(1, 100);

        $btw2 = new Zend_Validate_Between(1, 125);
        $messageUserDefined = 'Something other than the default message';
        $btw2->setMessage($messageUserDefined, Zend_Validate_Between::NOT_BETWEEN);

        $validators = [
            'field1' => [$btw1, $btw2],
            'field2' => [$btw1, $btw2, Zend_Filter_Input::BREAK_CHAIN => true]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field1', 'field2'], array_keys($messages));
        $this->assertEquals(
            $messageUserDefined,
            current($messages['field1']),
            'Expected message to break 2 validators, the message of the latter overwriting that of the former'
            );
        $this->assertEquals(
            "'150' is not between '1' and '100', inclusively",
            current($messages['field2']),
            'Expected rule for field2 to break the validation chain at the first validator'
            );
    }

    public function testValidatorAllowEmpty()
    {
        $data = [
            'field1' => '',
            'field2' => ''
        ];
        $validators = [
            'field1' => [
                'alpha',
                Zend_Filter_Input::ALLOW_EMPTY => false
            ],
            'field2' => [
                'alpha',
                Zend_Filter_Input::ALLOW_EMPTY => true
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertNull($input->field1);
        $this->assertNotNull($input->field2);
        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field1'], array_keys($messages));
        $this->assertEquals("You must give a non-empty value for field 'field1'", current($messages['field1']));
    }

    /**
     * @group ZF-6708
     * @group ZF-1912
     */
    public function testValidatorAllowEmptyWithOtherValidatersProcessing()
    {
        $data = [
            'field1' => ''
        ];
        $validators = [
            'field1' => [
                'alpha',
                Zend_Filter_Input::ALLOW_EMPTY => false
            ],
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return true');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field1'], array_keys($messages));
        $this->assertEquals("You must give a non-empty value for field 'field1'", current($messages['field1']));
    }

    /**
     * @group ZF-6708
     */
    public function testValidatorShouldNotProcessZeroAsEmpty()
    {
        $validation = [
            'offset' =>  [
                'digits',
                'presence' => 'required'
            ]
        ];
        $data = [
            'offset' => 0,
        ];

        $input = new Zend_Filter_Input(null, $validation, $data);
        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $messages = $input->getMessages();
        $this->assertEquals([], array_keys($messages));
    }

    public function testValidatorAllowEmptyNoValidatorChain()
    {
        // Zend_Loader::loadClass('Zend_Filter_StringTrim');
        // Zend_Loader::loadClass('Zend_Filter_StripTags');
        // Zend_Loader::loadClass('Zend_Validate_EmailAddress');

        $data = [
            'nick'    => '',
            'email'   => 'someemail@server.com'
        ];

        $filters = [
            '*'       => new Zend_Filter_StringTrim(),
            'nick'    => new Zend_Filter_StripTags()
        ];

        $validators = [
            'email'   => [
                new Zend_Validate_EmailAddress(),
                Zend_Filter_Input::ALLOW_EMPTY => true
            ],
            /*
             * This is the case we're testing - when presense is required,
             * but there are no validators besides disallowing empty values.
             */
            'nick'    => [
                Zend_Filter_Input::PRESENCE    => Zend_Filter_Input::PRESENCE_REQUIRED,
                Zend_Filter_Input::ALLOW_EMPTY => false
            ]
        ];

        $input = new Zend_Filter_Input($filters, $validators, $data);

        if ($input->hasInvalid()) {
            $input->getMessages();
        }

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['nick'], array_keys($messages));
        $this->assertEquals(1, count($messages['nick']));
    }

    public function testValidatorAllowEmptySetNotEmptyMessage()
    {
        $data = [
            'field1' => '',
        ];
        $validators = [
            'field1Rule' => [
                Zend_Filter_Input::ALLOW_EMPTY => false,
                'fields' => 'field1'
            ]
        ];

        $options = [
            Zend_Filter_Input::NOT_EMPTY_MESSAGE => "You cannot give an empty value for field '%field%', according to rule '%rule%'"
        ];

        $input = new Zend_Filter_Input(null, $validators, $data, $options);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $this->assertNull($input->field1);
        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field1Rule'], array_keys($messages));
        $this->assertTrue(is_array($messages['field1Rule']));
        $this->assertEquals("You cannot give an empty value for field 'field1', according to rule 'field1Rule'", current($messages['field1Rule']));
    }

    public function testValidatorDefault()
    {
        $validators = [
            'field1'   => ['presence' => 'required', 'allowEmpty' => false],
            'field2'   => ['presence' => 'optional', 'allowEmpty' => false],
            'field3'   => ['presence' => 'required', 'allowEmpty' => true],
            'field4'   => ['presence' => 'optional', 'allowEmpty' => true],
            'field5'   => ['presence' => 'required', 'allowEmpty' => false, 'default' => 'field5default'],
            'field6'   => ['presence' => 'optional', 'allowEmpty' => false, 'default' => 'field6default'],
            'field7'   => ['presence' => 'required', 'allowEmpty' => true, 'default' => 'field7default'],
            'field8'   => ['presence' => 'optional', 'allowEmpty' => true, 'default' => ['field8default', 'field8default2']],
        ];
        $data = [];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $missing = $input->getMissing();
        $this->assertTrue(is_array($missing));
        // make sure field5 and field7 are not counted as missing
        $this->assertEquals(['field1', 'field3'], array_keys($missing));

        $this->assertNull($input->field1);
        $this->assertNull($input->field2);
        $this->assertNull($input->field3);
        $this->assertNull($input->field4);
        $this->assertEquals('field5default', $input->field5, 'Expected field5 to be non-null');
        $this->assertEquals('field6default', $input->field6, 'Expected field6 to be non-null');
        $this->assertEquals('field7default', $input->field7, 'Expected field7 to be non-null');
        $this->assertEquals('field8default', $input->field8, 'Expected field8 to be non-null');
    }

    /**
     * @group ZF-6761
     */
    public function testValidatorMissingDefaults()
    {
        $validators = [
            'rule1'   => ['presence' => 'required',
                               'fields'   => ['field1', 'field2'],
                               'default'  => ['field1default']]
        ];
        $data = [];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $missing = $input->getMissing();
        $this->assertTrue(is_array($missing));
        $this->assertEquals(['rule1'], array_keys($missing));
        $this->assertEquals(["Field 'field2' is required by rule 'rule1', but the field is missing"], $missing['rule1']);
    }

    public function testValidatorDefaultDoesNotOverwriteData()
    {
        $validators = [
            'field1'   => ['presence' => 'required', 'allowEmpty' => false, 'default' => 'abcd'],
            'field2'   => ['presence' => 'optional', 'allowEmpty' => false, 'default' => 'abcd'],
            'field3'   => ['presence' => 'required', 'allowEmpty' => true, 'default' => 'abcd'],
            'field4'   => ['presence' => 'optional', 'allowEmpty' => true, 'default' => 'abcd'],
        ];
        $data = [
            'field1' => 'ABCD',
            'field2' => 'ABCD',
            'field3' => 'ABCD',
            'field4' => 'ABCD'
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals('ABCD', $input->field1);
        $this->assertEquals('ABCD', $input->field2);
        $this->assertEquals('ABCD', $input->field3);
        $this->assertEquals('ABCD', $input->field4);
    }

    public function testValidatorNotAllowEmpty()
    {
        $filters = [
            'field1'   => 'Digits',
            'field2'   => 'Alnum'
        ];

        $validators = [
            'field1'   => ['Digits'],
            'field2'   => ['Alnum'],
            'field3'   => ['Alnum', 'presence' => 'required']
        ];
        $data = [
            'field1' => 'asd1', // Valid data
            'field2' => '$'     // Invalid data
        ];
        $input = new Zend_Filter_Input($filters, $validators, $data);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field2', 'field3'], array_keys($messages));
        $this->assertTrue(is_array($messages['field2']));
        $this->assertEquals("You must give a non-empty value for field 'field2'", current($messages['field2']));
    }

    public function testValidatorMessagesSingle()
    {
        $data = ['month' => '13abc'];
        $digitsMesg = 'Month should consist of digits';
        $validators = [
            'month' => [
                'digits',
                'messages' => $digitsMesg
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['month'], array_keys($messages));
        $this->assertEquals(1, count($messages['month']));
        $this->assertEquals($digitsMesg, current($messages['month']));
    }

    public function testValidatorMessagesMultiple()
    {
        $data = ['month' => '13abc'];
        $digitsMesg = 'Month should consist of digits';
        $betweenMesg = 'Month should be between 1 and 12';
        // Zend_Loader::loadClass('Zend_Validate_Between');
        $validators = [
            'month' => [
                'digits',
                new Zend_Validate_Between(1, 12),
                'messages' => [
                    $digitsMesg,
                    $betweenMesg
                ]
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['month'], array_keys($messages));
        $this->assertEquals(2, count($messages['month']));
        $this->assertEquals($digitsMesg, $messages['month']['notDigits']);
        $this->assertEquals($betweenMesg, $messages['month']['notBetween']);
    }

    public function testValidatorMessagesFieldsMultiple()
    {
        $data = ['field1' => ['13abc', '234']];
        $digitsMesg = 'Field1 should consist of digits';
        $betweenMesg = 'Field1 should be between 1 and 12';
        // Zend_Loader::loadClass('Zend_Validate_Between');
        $validators = [
            'field1' => [
                'digits',
                new Zend_Validate_Between(1, 12),
                'messages' => [
                    $digitsMesg,
                    $betweenMesg
                ]
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field1'], array_keys($messages));
        $this->assertEquals(3, count($messages['field1']));
        $this->assertEquals($digitsMesg, $messages['field1']['notDigits']);
        $this->assertEquals($betweenMesg, $messages['field1']['notBetween']);
    }

    public function testValidatorMessagesIntIndex()
    {
        $data = ['month' => '13abc'];
        $betweenMesg = 'Month should be between 1 and 12';
        // Zend_Loader::loadClass('Zend_Validate_Between');
        $validators = [
            'month' => [
                'digits',
                new Zend_Validate_Between(1, 12),
                'messages' => [
                    1 => $betweenMesg
                ]
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['month'], array_keys($messages));
        $this->assertEquals(2, count($messages['month']));
        $this->assertEquals("'13abc' must contain only digits", current($messages['month']));
        /**
         * @todo $this->assertEquals($betweenMesg, next($messages['month']));
         */
    }

    public function testValidatorMessagesSingleWithKeys()
    {
        $data = ['month' => '13abc'];
        $digitsMesg = 'Month should consist of digits';
        $validators = [
            'month' => [
                'digits',
                'messages' => ['notDigits' => $digitsMesg]
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['month'], array_keys($messages));
        $this->assertEquals(1, count($messages['month']));
        // $this->assertEquals($digitsMesg, $messages['month'][0]);
    }

    public function testValidatorMessagesMultipleWithKeys()
    {
        $data = ['month' => '13abc'];
        $digitsMesg = 'Month should consist of digits';
        $betweenMesg = 'Month should be between 1 and 12';
        // Zend_Loader::loadClass('Zend_Validate_Between');
        $validators = [
            'month' => [
                'digits',
                new Zend_Validate_Between(1, 12),
                'messages' => [
                    ['notDigits' => $digitsMesg],
                    ['notBetween' => $betweenMesg]
                ]
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['month'], array_keys($messages));
        $this->assertEquals(2, count($messages['month']));
        // $this->assertEquals($digitsMesg, $messages['month'][0]);
        // $this->assertEquals($betweenMesg, $messages['month'][1]);
    }

    public function testValidatorMessagesMixedWithKeys()
    {
        $data = ['month' => '13abc'];
        $digitsMesg = 'Month should consist of digits';
        $betweenMesg = 'Month should be between 1 and 12';
        // Zend_Loader::loadClass('Zend_Validate_Between');
        $validators = [
            'month' => [
                'digits',
                new Zend_Validate_Between(1, 12),
                'messages' => [
                    $digitsMesg,
                    ['notBetween' => $betweenMesg]
                ]
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['month'], array_keys($messages));
        $this->assertEquals(2, count($messages['month']));
        // $this->assertEquals($digitsMesg, $messages['month'][0]);
        // $this->assertEquals($betweenMesg, $messages['month'][1]);
    }

    public function testValidatorHasMissing()
    {
        $data = [];
        $validators = [
            'month' => [
                'digits',
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_REQUIRED
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');
    }

    public function testValidatorFieldOptional()
    {
        $data = [];
        $validators = [
            'month' => [
                'digits',
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_OPTIONAL
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');
    }

    public function testValidatorGetMissing()
    {
        $data = [];
        $validators = [
            'month' => [
                'digits',
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_REQUIRED
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $missing = $input->getMissing();
        $this->assertTrue(is_array($missing));
        $this->assertEquals(['month'], array_keys($missing));
        $this->assertEquals("Field 'month' is required by rule 'month', but the field is missing", $missing['month'][0]);
    }

    public function testValidatorSetMissingMessage()
    {
        $data = [];
        $validators = [
            'monthRule' => [
                'digits',
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_REQUIRED,
                'fields' => 'month'
            ]
        ];
        $options = [
            Zend_Filter_Input::MISSING_MESSAGE => 'I looked for %field% but I did not find it; it is required by rule %rule%'
        ];
        $input = new Zend_Filter_Input(null, $validators, $data, $options);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $missing = $input->getMissing();
        $this->assertTrue(is_array($missing));
        $this->assertEquals(['monthRule'], array_keys($missing));
        $this->assertEquals("I looked for month but I did not find it; it is required by rule monthRule", $missing['monthRule'][0]);
    }

    public function testValidatorHasUnknown()
    {
        $data = [
            'unknown' => 'xxx'
        ];
        $validators = [
            'month' => 'digits'
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expecting hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expecting hasInvalid() to return false');
        $this->assertTrue($input->hasUnknown(), 'Expecting hasUnknown() to return true');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');
    }

    public function testValidatorGetUnknown()
    {
        $data = [
            'unknown' => 'xxx'
        ];
        $validators = [
            'month' => 'digits'
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertTrue($input->hasUnknown(), 'Expected hasUnknown() to retrun true');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $unknown = $input->getUnknown();
        $this->assertTrue(is_array($unknown));
        $this->assertThat($unknown, $this->arrayHasKey('unknown'));
    }

    public function testValidatorGetInvalid()
    {
        $data = [
            'month' => '6abc '
        ];
        $validators = [
            'month' => 'digits',
            'field2' => ['digits', 'presence' => 'required']
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $invalid = $input->getInvalid();
        $missing = $input->getMissing();

        $this->assertTrue(is_array($messages));
        $this->assertEquals(['month', 'field2'], array_keys($messages));
        $this->assertTrue(is_array($invalid));
        $this->assertEquals(['month'], array_keys($invalid));
        $this->assertTrue(is_array($missing));
        $this->assertEquals(['field2'], array_keys($missing));
        $this->assertEquals(array_merge($invalid, $missing), $messages);
    }

    public function testValidatorIsValid()
    {
        $data = [
            'field1' => 'abc123',
            'field2' => 'abcdef'
        ];
        $validators = [
            'field1' => 'alpha',
            'field2' => 'alpha'
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertFalse($input->isValid());
        $this->assertFalse($input->isValid('field1'));
        $this->assertTrue($input->isValid('field2'));

        $input->setData(['field2' => 'abcdef']);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertTrue($input->isValid());
        $this->assertFalse($input->isValid('field1'));
        $this->assertTrue($input->isValid('field2'));
    }

    public function testAddNamespace()
    {
        $data = [
            'field1' => 'abc',
            'field2' => '123',
            'field3' => '123'
        ];
        $validators = [
            'field1' => 'MyDigits',
            'field2' => 'MyDigits',
            'field3' => 'digits'
        ];

        $ip = get_include_path();
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files';
        $newIp = $dir . PATH_SEPARATOR . $ip;
        set_include_path($newIp);

        $input = new Zend_Filter_Input(null, $validators, $data);
        $input->addNamespace('TestNamespace');

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');
        set_include_path($ip);

        $this->assertEquals('123', (string) $input->field2);
        $this->assertEquals('123', (string) $input->field3);

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertThat($messages, $this->arrayHasKey('field1'));
        $this->assertEquals("'abc' must contain only digits", current($messages['field1']));
    }

    public function testGetPluginLoader()
    {
        $input = new Zend_Filter_Input(null, null);

        $loader = $input->getPluginLoader(Zend_Filter_Input::VALIDATE);
        $this->assertTrue($loader instanceof Zend_Loader_PluginLoader,
            'Expected object of type Zend_Loader_PluginLoader, got ' , get_class($loader));

        try {
            $loader = $input->getPluginLoader('foo');
            $this->fail('Expected to catch Zend_Filter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertTrue($e instanceof Zend_Filter_Exception,
                'Expected object of type Zend_Filter_Exception, got '.get_class($e));
            $this->assertEquals('Invalid type "foo" provided to getPluginLoader()',
                $e->getMessage());
        }

    }

    public function testSetPluginLoader()
    {
        $input = new Zend_Filter_Input(null, null);

        $loader = new Zend_Loader_PluginLoader();

        $input->setPluginLoader($loader, Zend_Filter_Input::VALIDATE);
    }

    public function testSetPluginLoaderInvalidType()
    {
        $input = new Zend_Filter_Input(null, null);

        $loader = new Zend_Loader_PluginLoader();

        try {
            $input->setPluginLoader($loader, 'foo');
            $this->fail('Expected to catch Zend_Filter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertTrue($e instanceof Zend_Filter_Exception,
                'Expected object of type Zend_Filter_Exception, got '.get_class($e));
            $this->assertEquals('Invalid type "foo" provided to setPluginLoader()',
                $e->getMessage());
        }
    }

    public function testNamespaceExceptionClassNotFound()
    {
        $data = [
            'field1' => 'abc'
        ];
        $validators = [
            'field1' => 'MyDigits'
        ];
        // Do not add namespace on purpose, so MyDigits will not be found
        $input = new Zend_Filter_Input(null, $validators, $data);
        try {
            $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
            $this->fail('Expected to catch Zend_Filter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertTrue($e instanceof Zend_Loader_PluginLoader_Exception,
                'Expected object of type Zend_Filter_Exception, got '.get_class($e));
            $this->assertContains("not found in the registry", $e->getMessage());
        }
    }

    public function testNamespaceExceptionInvalidClass()
    {
        $data = [
            'field1' => 'abc'
        ];
        // Zend_Validate_Exception exists, but does not implement the needed interface
        $validators = [
            'field1' => 'Exception'
        ];

        $input = new Zend_Filter_Input(null, $validators, $data);

        try {
            $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
            $this->fail('Expected to catch Zend_Filter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertTrue($e instanceof Zend_Filter_Exception,
                'Expected object of type Zend_Filter_Exception, got '.get_class($e));
            $this->assertEquals("Class 'Zend_Validate_Exception' based on basename 'Exception' must implement the 'Zend_Validate_Interface' interface",
                $e->getMessage());
        }
    }

    public function testSetDefaultEscapeFilter()
    {
        $data = [
            'field1' => ' ab&c '
        ];
        $input = new Zend_Filter_Input(null, null, $data);
        $input->setDefaultEscapeFilter('StringTrim');

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals('ab&c', $input->field1);
    }

    public function testSetDefaultEscapeFilterExceptionWrongClassType()
    {
        $input = new Zend_Filter_Input(null, null);
        try {
            $input->setDefaultEscapeFilter(new StdClass());
            $this->fail('Expected to catch Zend_Filter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertTrue($e instanceof Zend_Filter_Exception,
                'Expected object of type Zend_Filter_Exception, got '.get_class($e));
            $this->assertEquals("Escape filter specified does not implement Zend_Filter_Interface", $e->getMessage());
        }
    }

    public function testOptionAllowEmpty()
    {
        $data = [
            'field1' => ''
        ];
        $validators = [
            'field1' => 'alpha'
        ];
        $options = [
            Zend_Filter_Input::ALLOW_EMPTY => true
        ];
        $input = new Zend_Filter_Input(null, $validators, $data, $options);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertNotNull($input->field1);
        $this->assertEquals('', $input->field1);
    }

    public function testOptionBreakChain()
    {
        $data = [
            'field1' => '150'
        ];
        // Zend_Loader::loadClass('Zend_Validate_Between');
        $btw1 = new Zend_Validate_Between(1, 100);
        $btw2 = new Zend_Validate_Between(1, 125);
        $validators = [
            'field1' => [$btw1, $btw2],
        ];
        $options = [
            Zend_Filter_Input::BREAK_CHAIN => true
        ];
        $input = new Zend_Filter_Input(null, $validators, $data, $options);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertEquals(['field1'], array_keys($messages));
        $this->assertEquals(1, count($messages['field1']), 'Expected rule for field1 to break 1 validator');
        $this->assertEquals("'150' is not between '1' and '100', inclusively",
            current($messages['field1']));
    }

    public function testOptionEscapeFilter()
    {
        $data = [
            'field1' => ' ab&c '
        ];
        $options = [
            Zend_Filter_Input::ESCAPE_FILTER => 'StringTrim'
        ];
        $input = new Zend_Filter_Input(null, null, $data, $options);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals('ab&c', $input->field1);
    }

    public function testOptionNamespace()
    {
        $data = [
            'field1' => 'abc',
            'field2' => '123',
            'field3' => '123'
        ];
        $validators = [
            'field1' => 'MyDigits',
            'field2' => 'MyDigits',
            'field3' => 'digits'
        ];
        $options = [
            Zend_Filter_Input::INPUT_NAMESPACE => 'TestNamespace'
        ];

        $ip = get_include_path();
        $dir = dirname(__FILE__) . DIRECTORY_SEPARATOR . '_files';
        $newIp = $dir . PATH_SEPARATOR . $ip;
        set_include_path($newIp);

        $input = new Zend_Filter_Input(null, $validators, $data, $options);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');
        set_include_path($ip);

        $this->assertEquals('123', (string) $input->field2);
        $this->assertEquals('123', (string) $input->field3);

        $messages = $input->getMessages();
        $this->assertTrue(is_array($messages));
        $this->assertThat($messages, $this->arrayHasKey('field1'));
        $this->assertEquals("'abc' must contain only digits", current($messages['field1']));
    }

    public function testOptionPresence()
    {
        $data = [
            'field1' => '123'
            // field2 is missing deliberately
        ];
        $validators = [
            'field1' => 'Digits',
            'field2' => 'Digits'
        ];
        $options = [
            Zend_Filter_Input::PRESENCE => true
        ];
        $input = new Zend_Filter_Input(null, $validators, $data, $options);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $missing = $input->getMissing();
        $this->assertTrue(is_array($missing));
        $this->assertEquals(['field2'], array_keys($missing));
        $this->assertEquals("Field 'field2' is required by rule 'field2', but the field is missing", $missing['field2'][0]);
    }

    public function testOptionExceptionUnknown()
    {
        $options = [
            'unknown' => 'xxx'
        ];
        try {
            $input = new Zend_Filter_Input(null, null, null, $options);
            $this->fail('Expected to catch Zend_Filter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertTrue($e instanceof Zend_Filter_Exception,
                'Expected object of type Zend_Filter_Exception, got '.get_class($e));
            $this->assertEquals("Unknown option 'unknown'", $e->getMessage());
        }
    }

    public function testGetEscaped()
    {
        $data = [
            'field1' => 'ab&c'
        ];
        $input = new Zend_Filter_Input(null, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals('ab&amp;c', $input->getEscaped('field1'));
        $this->assertNull($input->getEscaped('field2'));
    }

    public function testGetEscapedAllFields()
    {
        $data = [
            'field1' => 'ab&c'
        ];
        $input = new Zend_Filter_Input(null, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals(['field1' => 'ab&amp;c'], $input->getEscaped());
    }

    public function testMagicGetEscaped()
    {
        $data = [
            'field1' => 'ab&c'
        ];
        $input = new Zend_Filter_Input(null, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals('ab&amp;c', $input->field1);
        $this->assertNull($input->field2);
    }

    public function testGetEscapedMultiValue()
    {
        $data = [
            'multiSelect' => ['C&H', 'B&O', 'AT&T']
        ];
        $input = new Zend_Filter_Input(null, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $multi = $input->getEscaped('multiSelect');
        $this->assertTrue(is_array($multi));
        $this->assertEquals(3, count($multi));
        $this->assertEquals(['C&amp;H', 'B&amp;O', 'AT&amp;T'], $multi);
    }

    public function testGetUnescaped()
    {
        $data = [
            'field1' => 'ab&c'
        ];
        $input = new Zend_Filter_Input(null, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals('ab&c', $input->getUnescaped('field1'));
        $this->assertNull($input->getUnescaped('field2'));
    }

    public function testGetUnescapedAllFields()
    {
        $data = [
            'field1' => 'ab&c'
        ];
        $input = new Zend_Filter_Input(null, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertEquals(['field1' => 'ab&c'], $input->getUnescaped());
    }

    public function testMagicIsset()
    {
        $data = [
            'field1' => 'ab&c'
        ];
        $input = new Zend_Filter_Input(null, null, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertTrue(isset($input->field1));
        $this->assertFalse(isset($input->field2));
    }

    public function testProcess()
    {
        $data = [
            'field1' => 'ab&c',
            'field2' => '123abc'
        ];
        $filters = [
            '*'      => 'StringTrim',
            'field2' => 'digits'
        ];
        $validators = [
            'field1' => [Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_OPTIONAL],
            'field2' => [
                'digits',
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_REQUIRED
            ]
        ];
        $input = new Zend_Filter_Input($filters, $validators, $data);
        try {
            $input->process();
            $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
            $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
            $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
            $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');
        } catch (Zend_Exception $e) {
            $this->fail('Received Zend_Exception where none was expected');
        }
    }

    public function testProcessUnknownThrowsNoException()
    {
        $data = [
            'field1' => 'ab&c',
            'field2' => '123abc',
            'field3' => 'unknown'
        ];
        $filters = [
            '*'      => 'StringTrim',
            'field2' => 'digits'
        ];
        $validators = [
            'field1' => [Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_OPTIONAL],
            'field2' => [
                'digits',
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_REQUIRED
            ]
        ];
        $input = new Zend_Filter_Input($filters, $validators, $data);
        try {
            $input->process();
            $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
            $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
            $this->assertTrue($input->hasUnknown(), 'Expected hasUnknown() to retrun true');
            $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');
        } catch (Zend_Exception $e) {
            $this->fail('Received Zend_Exception where none was expected');
        }
    }

    public function testProcessInvalidThrowsException()
    {
        $data = [
            'field1' => 'ab&c',
            'field2' => 'abc' // invalid because no digits
        ];
        $filters = [
            '*'      => 'StringTrim',
            'field2' => 'digits'
        ];
        $validators = [
            'field1' => [Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_OPTIONAL],
            'field2' => [
                'digits',
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_REQUIRED
            ]
        ];
        $input = new Zend_Filter_Input($filters, $validators, $data);
        try {
            $input->process();
            $this->fail('Expected to catch Zend_Filter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertTrue($e instanceof Zend_Filter_Exception,
                'Expected object of type Zend_Filter_Exception, got '.get_class($e));
            $this->assertEquals("Input has invalid fields", $e->getMessage());
            $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
            $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
            $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
            $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');
        }
    }

    public function testProcessMissingThrowsException()
    {
        $data = [
            'field1' => 'ab&c'
            // field2 is missing on purpose for this test
        ];
        $filters = [
            '*'      => 'StringTrim',
            'field2' => 'digits'
        ];
        $validators = [
            'field1' => [
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_OPTIONAL
            ],
            'field2' => [
                'digits',
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_REQUIRED
            ]
        ];
        $input = new Zend_Filter_Input($filters, $validators, $data);
        try {
            $input->process();
            $this->fail('Expected to catch Zend_Filter_Exception');
        } catch (Zend_Exception $e) {
            $this->assertTrue($e instanceof Zend_Filter_Exception,
                'Expected object of type Zend_Filter_Exception, got '.get_class($e));
            $this->assertEquals("Input has missing fields", $e->getMessage());
            $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');
            $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
            $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
            $this->assertTrue($input->hasValid(), 'Expected hasValid() to return true');
        }
    }

    /**
     * @group ZF-3004
     */
    public function testInsertingNullDoesNotGetEscapedWithDefaultEscapeMethod()
    {
        $input = new Zend_Filter_Input(null, null, ['test' => null]);
        $input->process();

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertTrue($input->hasValid(),    'Expected hasValid() to return true');

        $this->assertNull($input->getUnescaped('test'), 'getUnescaped of test fails to return null');
        $this->assertNull($input->getEscaped('test'),   'getEscaped of test fails to return null');
        $this->assertNull($input->test,                 'magic get of test fails to return null');
    }

    /**
     * @group ZF-3100
     */
    public function testPluginLoaderInputNamespaceWithSameNameFilterAndValidatorLeadsToException()
    {
        $filters = [
            'date1' => ['Date']
        ];
        $validators = [
            'date1' => ['Date']
        ];
        $data = [
            'date1' => '1990-01-01'
        ];
        $options = [
            'inputNamespace' => ['MyZend_Filter', 'MyZend_Validate'],
        ];
        $filter = new Zend_Filter_Input($filters, $validators, $data, $options);

        try {
            $filter->process();
            $this->fail();
        } catch(Zend_Filter_Exception $e) {
            $this->assertEquals(
                "Class 'MyZend_Validate_Date' based on basename 'Date' must implement the 'Zend_Filter_Interface' interface",
                $e->getMessage()
            );
        }
    }

    /**
     * @group ZF-3100
     */
    public function testPluginLoaderWithFilterValidateNamespaceWithSameNameFilterAndValidatorWorksPerfectly()
    {
        // Array
        $filters = [
            'date1' => ['Date']
        ];
        $validators = [
            'date1' => ['Date']
        ];
        $data = [
            'date1' => '1990-01-01'
        ];
        $options = [
            'filterNamespace' => ['MyZend_Filter'],
            'validatorNamespace' => ['MyZend_Validate'],
        ];
        $filter = new Zend_Filter_Input($filters, $validators, $data, $options);

        try {
            $filter->process();
            $this->assertEquals("2000-01-01", $filter->date1);
        } catch(Zend_Filter_Exception $e) {
            $this->fail();
        }

        // String notation
        $options = [
            'filterNamespace' => 'MyZend_Filter',
            'validatorNamespace' => 'MyZend_Validate',
        ];
        $filter = new Zend_Filter_Input($filters, $validators, $data, $options);

        try {
            $filter->process();
            $this->assertEquals("2000-01-01", $filter->date1);
        } catch(Zend_Filter_Exception $e) {
            $this->fail();
        }
    }

    /**
     * @group ZF-7135
     */
    public function testValidatorAllowNull()
    {
        $data = [
            'field1' => null
        ];
        $validators = [
            'field1' => [
                'notEmpty'
            ]
        ];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertFalse($input->hasMissing(), 'Expected hasMissing() to return false');
        $this->assertTrue($input->hasInvalid(), 'Expected hasInvalid() to return true');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return true');

        $this->assertNull($input->field1);
    }

    /**
     * @group ZF-7034
     */
    public function testSettingNotEmptyMessageAndMessagePerKeyAndMessagePerArray()
    {
        // require_once 'Zend/Validate/NotEmpty.php';
        // require_once 'Zend/Validate/Regex.php';
        // require_once 'Zend/Validate/StringLength.php';

        $filters = [ ];
        $validators = [
            'street' =>  [
                new Zend_Validate_NotEmpty (),
                new Zend_Validate_Regex ( '/^[a-zA-Z0-9]{1,30}$/u' ),
                new Zend_Validate_StringLength ( 0, 10 ),
                Zend_Filter_Input::PRESENCE => Zend_Filter_Input::PRESENCE_REQUIRED,
                Zend_Filter_Input::DEFAULT_VALUE => '',
                Zend_Filter_Input::BREAK_CHAIN => true,
                'messages' =>  [
                    0 => 'Bitte geben Sie Ihre Straße ein.',
                    'Verwenden Sie bitte keine Sonderzeichen bei der Eingabe.',
                     [
                        Zend_Validate_StringLength::TOO_LONG => 'Bitte beschränken Sie sich auf %max% Zeichen'
                    ]
                ]
            ]
        ];

        $filter = new Zend_Filter_Input($filters, $validators, ['street' => '']);
        $this->assertFalse($filter->isValid());
        $message = $filter->getMessages();
        $this->assertContains('Bitte geben Sie Ihre Straße ein.', $message['street']['isEmpty']);

        $filter2 = new Zend_Filter_Input($filters, $validators, ['street' => 'Str!!']);
        $this->assertFalse($filter2->isValid());
        $message = $filter2->getMessages();
        $this->assertContains('Verwenden Sie bitte keine Sonderzeichen', $message['street']['regexNotMatch']);

        $filter3 = new Zend_Filter_Input($filters, $validators, ['street' => 'Str1234567890']);
        $this->assertFalse($filter3->isValid());
        $message = $filter3->getMessages();
        $this->assertContains('Bitte beschränken Sie sich auf', $message['street']['stringLengthTooLong']);
    }

    /**
     * @group ZF-7394
     */
    public function testSettingMultipleNotEmptyMessages()
    {
        // require_once 'Zend/Validate/NotEmpty.php';
        // require_once 'Zend/Validate/Regex.php';
        // require_once 'Zend/Validate/StringLength.php';

        $filters = [ ];
        $validators = [
            'name' => ['NotEmpty','messages' => 'Please enter your name'],
            'subject' => ['NotEmpty','messages' => 'Please enter a subject'],
            'email' => ['EmailAddress','messages' => 'Please enter a valid Email address'],
            'content' => ['NotEmpty','messages' => 'Please enter message contents']
        ];

        $data = [
            'name' => '',
            'subject' => '',
            'content' => ''
        ];

        $filter = new Zend_Filter_Input($filters, $validators, $data);
        $this->assertFalse($filter->isValid());
        $message = $filter->getMessages();
        $this->assertContains('Please enter your name', $message['name']['isEmpty']);
        $this->assertContains('Please enter a subject', $message['subject']['isEmpty']);
        $this->assertContains('Please enter message contents', $message['content']['isEmpty']);
    }

    /**
     * @group ZF-3736
     */
    public function testTranslateNotEmptyMessages()
    {
        // require_once 'Zend/Translate/Adapter/Array.php';
        $translator = new Zend_Translate_Adapter_Array(['missingMessage' => 'Still missing'], 'en');

        $validators = [
            'rule1'   => ['presence' => 'required',
                               'fields'   => ['field1', 'field2'],
                               'default'  => ['field1default']]
        ];
        $data = [];
        $input = new Zend_Filter_Input(null, $validators, $data);
        $input->setTranslator($translator);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');

        $missing = $input->getMissing();
        $this->assertTrue(is_array($missing));
        $this->assertEquals(['rule1'], array_keys($missing));
        $this->assertEquals(["Still missing"], $missing['rule1']);
    }

    /**
     * @group ZF-3736
     */
    public function testTranslateNotEmptyMessagesByUsingRegistry()
    {
        // require_once 'Zend/Translate/Adapter/Array.php';
        $translator = new Zend_Translate_Adapter_Array(['missingMessage' => 'Still missing'], 'en');
        // require_once 'Zend/Registry.php';
        Zend_Registry::set('Zend_Translate', $translator);

        $validators = [
            'rule1'   => ['presence' => 'required',
                               'fields'   => ['field1', 'field2'],
                               'default'  => ['field1default']]
        ];
        $data = [];
        $input = new Zend_Filter_Input(null, $validators, $data);

        $this->assertTrue($input->hasMissing(), 'Expected hasMissing() to return true');
        $this->assertFalse($input->hasInvalid(), 'Expected hasInvalid() to return false');
        $this->assertFalse($input->hasUnknown(), 'Expected hasUnknown() to return false');
        $this->assertFalse($input->hasValid(), 'Expected hasValid() to return false');

        $missing = $input->getMissing();
        $this->assertTrue(is_array($missing));
        $this->assertEquals(['rule1'], array_keys($missing));
        $this->assertEquals(["Still missing"], $missing['rule1']);
    }

    /**
     * If setAllowEmpty(true) is called, all fields are optional, but fields with
     * a NotEmpty validator attached to them, should contain a non empty value.
     *
     * @group ZF-9289
     */
    function testAllowEmptyTrueRespectsNotEmptyValidators()
    {
        $data = [
            'field1' => 'foo',
            'field2' => ''
        ];

        $validators = [
            'field1' => [
                new Zend_Validate_NotEmpty(),
                Zend_Filter_Input::MESSAGES => [
                    [
                        Zend_Validate_NotEmpty::IS_EMPTY => '\'field1\' is required'
                    ]
                ]
            ],

            'field2' => [
                new Zend_Validate_NotEmpty()
            ]
        ];

        $options = [Zend_Filter_Input::ALLOW_EMPTY => true];
        $input = new Zend_Filter_Input( null, $validators, $data, $options );
        $this->assertFalse($input->isValid(), 'Ouch, the NotEmpty validators are ignored!');

        $validators = [
            'field1' => [
                'Digits',
                ['NotEmpty', 'integer'],
                Zend_Filter_Input::MESSAGES => [
                    1 =>
                    [
                        Zend_Validate_NotEmpty::IS_EMPTY => '\'field1\' is required'
                    ]
                ]
            ]
        ];

        $data = [
            'field1' => 0,
            'field2' => ''
        ];
        $options = [Zend_Filter_Input::ALLOW_EMPTY => true];
        $input = new Zend_Filter_Input( null, $validators, $data, $options );
        $this->assertFalse($input->isValid(), 'Ouch, if the NotEmpty validator is not the first rule, the NotEmpty validators are ignored !');

        // and now with a string 'NotEmpty' instead of an instance:

        $validators = [
            'field1' => [
                'NotEmpty',
                Zend_Filter_Input::MESSAGES => [
                    0 =>
                    [
                        Zend_Validate_NotEmpty::IS_EMPTY => '\'field1\' is required'
                    ]
                ]
            ]
        ];

        $data = [
            'field1' => '',
            'field2' => ''
        ];

        $options = [Zend_Filter_Input::ALLOW_EMPTY => true];
        $input = new Zend_Filter_Input( null, $validators, $data, $options );
        $this->assertFalse($input->isValid(), 'If the NotEmpty validator is a string, the NotEmpty validator is ignored !');

        // and now with an array

        $validators = [
            'field1' => [
                ['NotEmpty', 'integer'],
                Zend_Filter_Input::MESSAGES => [
                    0 =>
                    [
                        Zend_Validate_NotEmpty::IS_EMPTY => '\'field1\' is required'
                    ]
                ]
            ]
        ];

        $data = [
            'field1' => 0,
            'field2' => ''
        ];

        $options = [Zend_Filter_Input::ALLOW_EMPTY => true];
        $input = new Zend_Filter_Input( null, $validators, $data, $options );
        $this->assertFalse($input->isValid(), 'If the NotEmpty validator is an array, the NotEmpty validator is ignored !');
    }
    
    /**
     * This test doesn't include any assertions as it's purpose is to 
     * ensure that passing an empty array value into a $validators rule 
     * doesn't cause a notice to be emitted
     *  
     * @group ZF-11819
     */
    public function testValidatorRuleCanHaveEmptyArrayAsMetacommandValue()
    {
        $validators = [
            'perms' => ['Int', 'default' => []],
        ];

        $validate = new Zend_Filter_Input(NULL, $validators);
        $validate->isValid();
    }    
}

class MyZend_Filter_Date implements Zend_Filter_Interface
{
    public function filter($value)
    {
        return "2000-01-01";
    }
}

class MyZend_Validate_Date implements Zend_Validate_Interface
{
    public function isValid($value)
    {
        return true;
    }

    public function getMessages()
    {
        return [];
    }

    public function getErrors()
    {
        return [];
    }
}
