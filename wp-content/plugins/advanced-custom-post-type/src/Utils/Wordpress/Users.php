<?php

namespace ACPT\Utils\Wordpress;

class Users
{
    /**
     * @param $excludedUserId
     *
     * @return array
     */
    public static function getList($excludedUserId)
    {
        $list = [];
        $users = get_users( [
            'exclude' => [
                $excludedUserId
            ],
            'fields' => [
                'ID',
                'display_name',
            ],
        ]);

        usort($users, function ($a, $b){
            if(isset($a->last_name) and isset($b->last_name)){
                return strnatcasecmp($a->last_name, $b->last_name);
            }

            return false;
        });


        foreach($users as $user){
            $list[$user->ID] = self::getUserLabel($user);
        }

        return $list;
    }

    /**
     * @param \WP_User $user
     *
     * @return string
     */
    private static function getUserLabel($user)
    {
        $userData = get_userdata( $user->ID );

        if($userData->first_name and $userData->last_name){
            return $userData->first_name . ' ' . $userData->last_name;
        }

        return $user->display_name;
    }
}