<?php

namespace common\services;

use common\models\AuthAssignment;
use common\models\AuthItemChild;
use common\helpers\Rbac;
use Yii;
use yii\base\Exception;
use yii\rbac\Item;


class RbacService
{

    /** @var yii\rbac\ManagerInterface */
    public $auth;


    public function __construct()
    {
        $this->auth = Rbac::getAuth();
    }


    /**
     * @param array<string,string> $rolesPermissions [role => [permission]]
     * @param array<string,string> $childrenHierarchy [parenRole => childRole]
     *
     * @return WebResponse
     *
     * @throws Exception
     */
    public function createRolesAndPermissions(array $rolesPermissions, array $childrenHierarchy = [])
    {
        foreach ($rolesPermissions as $roleName => $permissions) {
            $role = Rbac::createRole($roleName);

            $assignedPermissions = Rbac::assignPermissions($role, $permissions);
        }

        foreach ($childrenHierarchy as $parentRoleName => $childrenRoleNames) {
            if ($parentRole = Rbac::getAuth()->getRole($parentRoleName)) {
                foreach ($childrenRoleNames as $childrenRoleName) {
                    if ($childrenRole = Rbac::getAuth()->getRole($childrenRoleName)) {
                        Rbac::getAuth()->addChild($parentRole, $childrenRole);
                    }
                }
            }
        }

        return null;
    }

    /**
     * @return AuthAssignment[]
     */
    public function getAuthAssignments(): array
    {
        return AuthAssignment::find()->all();
    }

    /**
     * @return AuthItemChild[]
     */
    public function getItemChild(): array
    {
        return AuthItemChild::find()->all();
    }

    /**
     * @param AuthAssignment[] $assignments
     *
     * @throws \Exception
     */
    public function restoreAuthAssignments(array $assignments)
    {
        foreach ($assignments as $assignment) {
            if (!$item = $this->auth->getRole($assignment->item_name)) {
                if (!$item = $this->auth->getPermission($assignment->item_name)) {
                    continue;
                }
            }
            $this->auth->assign($item, $assignment->user_id);
        }

        return null;
    }

    /**
     * @param AuthItemChild[] $itemChildren
     * @throws \yii\base\Exception
     */
    public function restoreItemChild(array $itemChildren)
    {
        foreach ($itemChildren as $itemChild) {
            if (AuthItemChild::find()->where([
                    'parent' => $itemChild->parent,
                    'child' => $itemChild->child,
                ])->count() == 0) {
                $parent = (new Item(['name' => $itemChild->parent]));
                $child = (new Item(['name' => $itemChild->child]));
                try {
                    Rbac::getAuth()->addChild($parent, $child);
                } catch (Exception $e) {
                }
            }
        }

        return null;
    }
}
