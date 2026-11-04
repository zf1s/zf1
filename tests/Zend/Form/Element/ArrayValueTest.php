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
 * @package    Zend_Form
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * Array values submitted for elements holding a single value, e.g. field[]=... in a tampered request
 *
 * @category   Zend
 * @package    Zend_Form
 * @subpackage UnitTests
 * @group      Zend_Form
 */
class Zend_Form_Element_ArrayValueTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        Zend_Form::setDefaultTranslator(null);
    }

    public function tearDown()
    {
        Zend_Form::setDefaultTranslator(null);
    }

    public function singleValueElements()
    {
        $multiOptions = array('multiOptions' => array('a' => 'A'));
        return array(
            array('Zend_Form_Element_Text', array()),
            array('Zend_Form_Element_Textarea', array()),
            array('Zend_Form_Element_Password', array()),
            array('Zend_Form_Element_Hidden', array()),
            array('Zend_Form_Element_Hash', array()),
            array('Zend_Form_Element_Select', $multiOptions),
            array('Zend_Form_Element_Radio', $multiOptions),
        );
    }

    /**
     * @dataProvider singleValueElements
     */
    public function testSingleValueElementRejectsArrayValue($class, array $options)
    {
        $element = new $class('foo', $options);
        $this->assertFalse($element->isValid(array('a')));
        $this->assertNull($element->getValue());
        $this->assertSame(array(Zend_Form_Element::INVALID_TYPE), $element->getErrors());
        $this->assertSame(
            array(Zend_Form_Element::INVALID_TYPE => 'Invalid type given. String expected'),
            $element->getMessages()
        );
    }

    public function testArrayValueDoesNotReachFiltersAndView()
    {
        // StringTrim turned the array into "Array" (with a warning), which passed NotEmpty
        $element = new Zend_Form_Element_Text('foo', array('required' => true, 'filters' => array('StringTrim')));
        $this->assertFalse($element->isValid(array('a')));
        $this->assertSame('', $element->getValue());

        $element = new Zend_Form_Element_Text('foo');
        $element->isValid(array('a'));
        $this->assertContains('value=""', $element->render(new Zend_View()));
    }

    public function testFormWithTamperedArrayValueIsNotValid()
    {
        $form = new Zend_Form();
        $form->addElement('text', 'foo', array('required' => true));
        $this->assertFalse($form->isValid(array('foo' => array('a'))));
        $this->assertSame(array('foo' => null), $form->getValues());
    }

    public function testArrayElementsAcceptArrayValue()
    {
        $element = new Zend_Form_Element_Text('foo');
        $element->setIsArray(true);
        $this->assertTrue($element->isValid(array('a', 'b')));
        $this->assertSame(array('a', 'b'), $element->getValue());

        $element = new Zend_Form_Element_Multiselect('foo', array('multiOptions' => array('a' => 'A', 'b' => 'B')));
        $this->assertTrue($element->isValid(array('a', 'b')));
    }

    public function testOtherElementsKeepReceivingArrayValue()
    {
        $element = new Zend_Form_Element('foo');
        $this->assertTrue($element->isValid(array('a')));
        $this->assertSame(array('a'), $element->getValue());

        $element = new Zend_Form_Element_ArrayValueTest_DateElement('foo');
        $this->assertTrue($element->isValid(array('year' => '2026', 'month' => '11', 'day' => '04')));
        $this->assertSame('2026-11-04', $element->getValue());
    }

    public function testSubclassConvertingArrayValueInSetValueIsNotAffected()
    {
        $element = new Zend_Form_Element_ArrayValueTest_TagsElement('foo');
        $this->assertTrue($element->isValid(array('a', 'b')));
        $this->assertSame('a, b', $element->getValue());
    }

    public function testCustomErrorMessagesAreUsed()
    {
        $element = new Zend_Form_Element_Text('foo');
        $element->setErrorMessages(array('Please enter text'));
        $this->assertFalse($element->isValid(array('a')));
        $this->assertSame(array('Please enter text'), $element->getMessages());
    }

    public function testMessageIsTranslated()
    {
        $translator = new Zend_Translate(
            'array',
            array('Invalid type given. String expected' => 'Ungültiger Typ angegeben. String erwartet'),
            'de'
        );
        $element = new Zend_Form_Element_Text('foo');
        $element->setTranslator($translator);
        $this->assertFalse($element->isValid(array('a')));
        $this->assertSame(
            array(Zend_Form_Element::INVALID_TYPE => 'Ungültiger Typ angegeben. String erwartet'),
            $element->getMessages()
        );
    }
}

/**
 * Composite element built from several inputs, accepting an array value
 */
class Zend_Form_Element_ArrayValueTest_DateElement extends Zend_Form_Element_Xhtml
{
    public function setValue($value)
    {
        if (is_array($value)) {
            $value = $value['year'] . '-' . $value['month'] . '-' . $value['day'];
        }
        return parent::setValue($value);
    }
}

/**
 * Subclass of a single value element turning an array value into a string
 */
class Zend_Form_Element_ArrayValueTest_TagsElement extends Zend_Form_Element_Text
{
    public function setValue($value)
    {
        if (is_array($value)) {
            $value = implode(', ', $value);
        }
        return parent::setValue($value);
    }
}
