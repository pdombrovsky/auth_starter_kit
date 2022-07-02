<?php

namespace App\Models;

use Phalcon\Mvc\Model\Behavior\Timestampable;
use Phalcon\Mvc\Model as BaseModel;

use Phalcon\DI;

BaseModel::setup(
    [
        'exceptionOnFailedSave' => true,
        'notNullValidations' => false,
        'castLastInsertIdToInt' => true
    ]
);

abstract class Model extends BaseModel
{
    protected static string $format;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        self::$format = $this->di->getConfig()->dateTimeFormat;

        $this->addBehavior(
            new Timestampable(
                [
                    'beforeCreate' => [
                        'field'  => 'created',
                        'format' => self::$format
                    ]
                ]
            )
        );
        $this->addBehavior(
            new Timestampable(
                [
                    'beforeSave' => [
                        'field'  => 'modified',
                        'format' => self::$format
                    ]
                ]
            )
        );
    }

    public static function createUuid()
    {
        return DI::getDefault()->getRandom()->uuid();
    }

}
