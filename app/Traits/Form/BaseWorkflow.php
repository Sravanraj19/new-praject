<?php

namespace App\Traits\Form;

use App\Objects\WorkflowGenerator;

trait BaseWorkflow
{
    public string $name;
    public bool $onPush;

    public $onPushBranches;

    public bool $onPullrequest;
    public array $onPullrequestBranches;
    public bool $manualTrigger;
    public string $databaseType; // 'none', 'mysql', 'postgresql', 'sqlite'
    public string $mysqlDatabase;
    public string $mysqlPasswordType; // 'skip
    public string $mysqlPassword; // password
    public string $mysqlVersion;
    public string $mysqlDatabaseName;
    public int $mysqlDatabasePort;
    public string $postgresqlDatabase;
    public string $postgresqlPasswordType; // 'skip
    public string $postgresqlPassword; // password
    public string $postgresqlVersion;
    public string $postgresqlDatabaseName;
    public int $postgresqlDatabasePort;
    public array $stepPhpVersions; // 7.4
    public bool $stepNodejs; // false
    public string $stepNodejsVersion; // 15.x
    public bool $stepCachePackages; //true
    public bool $stepCacheVendors; //true
    public bool $stepCacheNpmModules; // true
    public array $dependencyStability; // []



    public function loadDefaultsBaseWorkflow(): void
    {
        $this->name = "Test Laravel Github action";
        $this->onPush = true;
        $this->onPushBranches = ["main", "develop", "features/**"];
        $this->onPullrequest = false;
        $this->onPullrequestBranches = ["main", "develop"];
        $this->manualTrigger = false;
        $this->databaseType = WorkflowGenerator::DB_TYPE_MYSQL;
        $this->mysqlDatabase = "mysql";
        $this->mysqlPasswordType = "skip";
        $this->mysqlPassword = "DB_PASSWORD";
        $this->mysqlVersion = "5.7";
        $this->mysqlDatabaseName = "db_test_laravel";
        $this->mysqlDatabasePort = 33306;
        $this->postgresqlDatabase = "postgresql";
        $this->postgresqlPasswordType = "hardcoded";
        $this->postgresqlPassword = "postgres";
        $this->postgresqlVersion = "latest";
        $this->postgresqlDatabaseName = "db_test_laravel";
        $this->postgresqlDatabasePort = 55432;
        $this->stepPhpVersions = ["8.0", "7.4"];
        $this->stepNodejs = false;
        $this->stepNodejsVersion = "16.x";
        $this->stepCachePackages = true;
        $this->stepCacheVendors = true;
        $this->stepCacheNpmModules  = true;
        $this->dependencyStability = [ 'prefer-none' ];
    }

    public function loadBaseWorkflowFromJson($j): void
    {
        data_fill($j, "stepDirCodeSniffer", "app");
        data_fill($j, "dependencyStability", [ 'prefer-none' ]);
        $this->name = $j->name;
        $this->onPush = $j->on_push;
        $this->onPushBranches =  $j->on_push_branches;
        $this->onPullrequest = $j->on_pullrequest;
        $this->onPullrequestBranches = $j->on_pullrequest_branches;
        $this->manualTrigger = $j->manual_trigger;
        if (isset($j->mysqlService)) {
            if ($j->mysqlService === true) {
                $this->databaseType = WorkflowGenerator::DB_TYPE_MYSQL;
            } elseif ($j->mysqlService === false) {
                $this->databaseType = WorkflowGenerator::DB_TYPE_NONE;
            }
        } else {
            $this->databaseType = $j->databaseType;
        }
        $this->mysqlDatabase = $j->mysqlDatabase;
        $this->mysqlPasswordType = $j->mysqlPasswordType;
        $this->mysqlPassword = $j->mysqlPassword;
        $this->mysqlVersion = $j->mysqlVersion;
        $this->mysqlDatabaseName = $j->mysqlDatabaseName;
        $this->mysqlDatabasePort = $j->mysqlDatabasePort;
        if (isset($j->postgresqlDatabase)) {
            $this->postgresqlDatabase = $j->postgresqlDatabase;
            $this->postgresqlPasswordType =
                $j->postgresqlPasswordType ?? $this->postgresqlPasswordType;
            $this->postgresqlPassword =
                isset($j->postgresqlPassword) ?
                    $j->postgresqlPassword :
                    $this->postgresqlPassword;
            $this->postgresqlVersion =
                isset($j->postgresqlVersion) ?
                    $j->postgresqlVersion :
                    $this->postgresqlVersion;
            $this->postgresqlDatabaseName =
                isset($j->postgresqlDatabaseName) ?
                    $j->postgresqlDatabaseName :
                    $this->postgresqlDatabaseName;
            $this->postgresqlDatabasePort =
                isset($j->postgresqlDatabasePort) ?
                    $j->postgresqlDatabasePort :
                    $this->postgresqlDatabasePort;
        }
        $this->stepPhpVersions = $j->stepPhpVersions;
        $this->stepNodejs = $j->stepNodejs;
        $this->stepNodejsVersion = $j->stepNodejsVersion;
        $this->stepCachePackages = $j->stepCachePackages;
        $this->stepCacheVendors = $j->stepCacheVendors;
        $this->stepCacheNpmModules  = $j->stepCacheNpmModules;
        $this->dependencyStability = (array) $j->dependencyStability;
    }

    public function setDataBaseWorkflow($data): array
    {
        $data = WorkflowGenerator::compactObject(
            $this,
            "databaseType",
            "mysqlDatabase",
            "mysqlVersion",
            "mysqlDatabaseName",
            "mysqlDatabasePort",
            "mysqlPassword",
            "mysqlPasswordType",
            "postgresqlDatabase",
            "postgresqlVersion",
            "postgresqlDatabaseName",
            "postgresqlDatabasePort",
            "postgresqlPassword",
            "postgresqlPasswordType",
            "name",
            "on_push",
            "on_push_branches",
            "on_pullrequest",
            "on_pullrequest_branches",
            "manual_trigger",
            "stepPhpVersions",
            "stepNodejs",
            "stepNodejsVersion",
            "stepCachePackages",
            "stepCacheVendors",
            "stepCacheNpmModules"
        );
        $data["stepPhpVersionsString"] = WorkflowGenerator::arrayToString($this->stepPhpVersions);
        $data["on_pullrequest_branches"] = WorkflowGenerator::split($this->onPullrequestBranches);
        $data["on_push_branches"] = WorkflowGenerator::split($this->onPushBranches);
        $data["dependencyStabilityString"] = WorkflowGenerator::arrayToString($this->dependencyStability);
        $data["dependencyStability"] = $this->dependencyStability;

        return $data;
    }
}
