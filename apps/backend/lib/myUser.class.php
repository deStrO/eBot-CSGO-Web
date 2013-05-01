<?php

class myUser extends sfGuardSecurityUser
{
    public function hasCredential($credential, $useAnd = true) {
        return (parent::hasCredential($credential, $useAnd) || parent::hasPermission($credential));
    }

    public function hasPermission($permission) {
        return $this->hasCredential($permission);
    }
}
