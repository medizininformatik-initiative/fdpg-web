<?php

namespace ACPT\Tests;

use ACPT\Core\Models\CustomPostType\CustomPostTypeMetaBoxFieldModel;
use ACPT\Utils\Data\Sanitizer;

class SanitizerTest extends AbstractTestCase
{
    /**
     * @test
     */
    public function can_sanitize_text()
    {
        $sanitized_text = Sanitizer::sanitizePostTypeRawData(CustomPostTypeMetaBoxFieldModel::TEXT_TYPE, "bla bla bla bla");

        $this->assertEquals('bla bla bla bla', $sanitized_text);
    }
}