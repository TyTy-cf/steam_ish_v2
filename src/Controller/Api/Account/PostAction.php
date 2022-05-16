<?php


namespace App\Controller\Api\Account;


use App\Entity\Account;
use Drosalys\Bundle\ApiBundle\Routing\Attributes\Post;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Deserializable;
use Drosalys\Bundle\ApiBundle\Serializer\Attributes\Serializable;

/**
 * Class PostAction.php
 *
 * @author Kevin Tourret
 */
class PostAction
{

    /**
     * Create a new Account
     * @param Account $account
     * @return Account
     */
    #[Post('/api/account')]
    #[Serializable(groups: 'Account'), Deserializable('account', groups: 'AccountPost')]
    public function __invoke(Account $account): Account
    {
        return $account;
    }

}
