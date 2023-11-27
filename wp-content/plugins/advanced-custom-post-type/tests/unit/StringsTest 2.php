<?php

namespace ACPT\Tests;

use ACPT\Core\Helper\Strings;

class StringsTest extends AbstractTestCase
{
	/**
	 * @test
	 */
	public function alphanumerically_valid()
	{
		$string = 'name';

		$this->assertTrue(Strings::alphanumericallyValid($string));

		$string = 'name_1';

		$this->assertTrue(Strings::alphanumericallyValid($string));

		$string = 'Name-1';

		$this->assertTrue(Strings::alphanumericallyValid($string));

		$string = 'nameèèè';

		$this->assertFalse(Strings::alphanumericallyValid($string));

		$string = 'name last name';

		$this->assertFalse(Strings::alphanumericallyValid($string));

		$string = 'Добрый';

		$this->assertFalse(Strings::alphanumericallyValid($string));
	}

    /**
     * @test
     */
    public function get_unique_name()
    {
        $string = 'name';

        $this->assertEquals($string.'_1', Strings::getUniqueName($string));

        $string = 'name_23';

        $this->assertEquals('name_24', Strings::getUniqueName($string));

        $string = 'name_45';

        $this->assertEquals('name_46', Strings::getUniqueName($string));
    }

    /**
     * @test
     */
    public function convert_camel_case_to_snake_case()
    {
        $string = "HelloMum";

        $this->assertEquals('hello_mum', Strings::toSnakeCase($string));
    }

    /**
     * @test
     */
    public function convert_snake_case_to_camel_case()
    {
        $string = "one-to-one-uni";

        $this->assertEquals('OneToOneUni', Strings::toCamelCase($string));

        $string = "one_to_one_uni";

        $this->assertEquals('OneToOneUni', Strings::toCamelCase($string));

        $string = "one to one uni";

        $this->assertEquals('OneToOneUni', Strings::toCamelCase($string));
    }
}