<?php
/**
* @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
*    Confidential and Proprietary. Any dissemination outside of Quicken Loans
*    is strictly prohibited.
*/

namespace QL\Hal\Core\Entity\Credential;

use PHPUnit_Framework_TestCase;

class AWSCredentialTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $cred = new AWSCredential;

        $this->assertSame('', $cred->key());
        $this->assertSame('', $cred->secret());
    }

    public function testProperties()
    {
        $cred = new AWSCredential('key1', 'secret2');

        $this->assertSame('key1', $cred->key());
        $this->assertSame('secret2', $cred->secret());
    }
}