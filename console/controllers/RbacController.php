<?php

namespace console\controllers;

use common\helpers\Rbac;
use common\services\RbacService;
use Yii;
use yii\console\ExitCode;


/**
 * RBAC commands.
 */
class RbacController extends BaseConsoleController
{

    /**
     * Recreate all Roles and Permissions defined in RbacHelper.
     *
     * @return int|void
     */
    public function actionInit()
    {
        $rbacService = new RbacService();

        $anyRolesInDb = (bool) count($rbacService->auth->getRoles());

        if ($anyRolesInDb && $this->askQuestion('Danger! This action reset all roles, rules and assignments to system default. Continue?') === false) {
            $this->echoLine("Nothing changed.");

            return ExitCode::OK;
        }

        $memoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '256M');

        $oldAssignments = $rbacService->getAuthAssignments();
        $oldItemChild = $rbacService->getItemChild();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $rbacService->auth->removeAll();
            $this->echoLine("All Auth records removed (if any exists), adding new ones...");

            $response = $rbacService->createRolesAndPermissions(Rbac::getPermissionsFor(), Rbac::getAuthChild());

            $response = $rbacService->restoreAuthAssignments($oldAssignments);

            $response = $rbacService->restoreItemChild($oldItemChild);

            $transaction->commit();

            $this->echoSuccess("...all done.");

            return ExitCode::OK;

        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->echoError('Something go wrong, all changes reverted.');
            $this->echoError('Exception message: ' . $e->getMessage());

            return ExitCode::UNSPECIFIED_ERROR;
        } finally {
            ini_set('memory_limit', $memoryLimit);
        }
    }

}
