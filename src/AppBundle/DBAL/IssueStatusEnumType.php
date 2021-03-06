<?php

namespace AppBundle\DBAL;

class IssueStatusEnumType extends EnumType
{
    const OPEN        = 'open';
    const IN_PROGRESS = 'in_progress';
    const CLOSED      = 'closed';

    const TYPE_NAME = 'issue_status_enum';

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::TYPE_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return [self::OPEN, self::IN_PROGRESS, self::CLOSED];
    }
}
