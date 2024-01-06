<?php

namespace common\helpers;

use common\models\AuthAssignment;
use common\models\User;
use yii\base\Exception;
use yii\base\UserException;
use yii\rbac\ManagerInterface;
use yii\rbac\Role;
use yii;


class Rbac
{
    const
        ITEM_TYPE_ROLE = 1,
        ITEM_TYPE_PERMISSION = 2;

    const
        ROLE_ADMIN = 'admin',
        ROLE_ROOT = 'root',
        ROLE_USER = 'user';

    const
        DEFAULT_ROLE = self::ROLE_USER;

    const
        PERMISSION_BOOKKEEP = 'bookkeep';

    const
        PERMISSION_CLIENT_READ = 'client-read',
        PERMISSION_CLIENT_WRITE = 'client-write';

    const
        PERMISSION_EVENT_READ = 'event-read',
        PERMISSION_EVENT_WRITE = 'event-write',
        PERMISSION_EVENT_DELETE = 'event-delete';

    const
        PERMISSION_FILE_READ = 'file-read',
        PERMISSION_FILE_WRITE = 'file-write';


    const
        PERMISSION_PRODUCT_READ = 'product-read',
        PERMISSION_PRODUCT_WRITE = 'product-write';

    const
        PERMISSION_STAFF_READ = 'staff-read',
        PERMISSION_STAFF_WRITE = 'staff-write';

    const
        PERMISSION_USER_READ = 'user-read',
        PERMISSION_USER_WRITE = 'user-write';

    /**
     * Provides Role => Permissions[] hierarchy.
     *
     * @param string|null $role
     *
     * @return array
     */
    public static function getPermissionsFor(string $role = null): array
    {
        $rolePermission = [
            self::ROLE_ROOT => [
                self::PERMISSION_FILE_READ,
                self::PERMISSION_FILE_WRITE,
                self::PERMISSION_USER_READ,
                self::PERMISSION_USER_WRITE,
            ],
            self::ROLE_ADMIN => [
                self::PERMISSION_CLIENT_READ,
                self::PERMISSION_CLIENT_WRITE,
                self::PERMISSION_STAFF_READ,
                self::PERMISSION_STAFF_WRITE,
                self::PERMISSION_EVENT_DELETE,
                self::PERMISSION_PRODUCT_READ,
                self::PERMISSION_PRODUCT_WRITE,
            ],
            self::ROLE_USER => [
                self::PERMISSION_EVENT_READ,
                self::PERMISSION_EVENT_WRITE,
            ],
        ];

        if (is_null($role) === false) {
            return $rolePermission[$role] ?? [];
        }

        return $rolePermission;
    }

    public static function getAuthChild(): array
    {
        return [
            self::ROLE_ROOT => [
                self::ROLE_ADMIN,
            ],
            self::ROLE_ADMIN => [
                self::ROLE_USER,
            ],
        ];
    }

    /**
     * Return system-native roles - that cannot be deleted
     *
     * @return array
     */
    final public static function getNativeRoles(): array
    {
        return [self::ROLE_ADMIN, self::ROLE_ROOT, self::ROLE_USER];
    }

    public static function getInheritedPermissions(string $roleName): array
    {
        $inheritedPermissions = [];

        /** @var \yii\rbac\Role[] $childRoles */
        $childRoles = self::getAuth()->getChildRoles($roleName);
        foreach ($childRoles as $childRoleName => $role) {
            if ($childRoleName === $roleName) continue;

            $inheritedPermissions = array_merge($inheritedPermissions, array_keys(self::getAuth()->getPermissionsByRole($role->name)));
        }

        return array_unique($inheritedPermissions);
    }

    public static function getAuth(): ManagerInterface
    {
        return Yii::$app->getAuthManager();
    }

    /**
     * @param Role $role
     * @param string[] $permissions
     *
     * @return array Assigned permission names
     *
     * @throws \yii\base\Exception
     */
    public static function assignPermissions(Role $role, array $permissions): array
    {
        $auth = self::getAuth();
        $assignedPermissions = [];

        foreach ($permissions as $name) {
            $permission = $auth->getPermission($name);
            if (is_null($permission)) {
                $permission = $auth->createPermission($name);
                $auth->add($permission);
            }
            $auth->addChild($role, $permission);
            $assignedPermissions[] = $name;
        }

        return $assignedPermissions;
    }

    public static function createRole(string $name, string $description = null, Role $parent = null): Role
    {
        $role = self::getAuth()->createRole($name);
        $role->description = $description;
        self::getAuth()->add($role);

        if ($parent) {
            self::getAuth()->addChild($parent, $role);
        }
        return $role;
    }

    /**
     * @param Role|string $role
     * @param User|int $user
     *
     * @return boolean
     *
     * @throws UserException
     */
    public static function assignRole($role, $user): bool
    {
        AuthAssignment::deleteAll(['user_id' => $user->user_id]);

        $authAssigment = new AuthAssignment();
        $authAssigment->item_name = $role;
        $authAssigment->user_id = $user->user_id;
        $authAssigment->created_at = time();
        if (!$authAssigment->save()) {
            return false;
        }

        return true;
    }
}
