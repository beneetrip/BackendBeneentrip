<?php

namespace AdminBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AdminBundle extends Bundle
{
// Pour que nos templates dans AdminBundle ecrase ceux de FOSuserBundle
public function getParent()
{
return 'FOSUserBundle';
}

}
