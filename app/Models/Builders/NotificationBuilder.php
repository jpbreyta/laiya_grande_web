<?php

namespace App\Models\Builders;

use Illuminate\Database\Eloquent\Builder;

class NotificationBuilder extends Builder
{
    /**
     * Preserve compatibility with old queries that used the removed `read` column.
     */
    public function where($column, $operator = null, $value = null, $boolean = 'and')
    {
        if ($column !== 'read') {
            return parent::where($column, $operator, $value, $boolean);
        }

        $argumentCount = func_num_args();
        $isRead = $argumentCount === 2 ? (bool) $operator : (bool) $value;

        if ($argumentCount >= 3 && in_array($operator, ['!=', '<>'], true)) {
            $isRead = ! $isRead;
        }

        return $isRead
            ? $this->whereNotNull('read_at', $boolean)
            : $this->whereNull('read_at', $boolean);
    }
}
