<?php

declare(strict_types=1);

namespace Kamalo\KanbanTaskManagementSystem\Application\UseCase\Task\GetTaskHistory;

class GetTaskHistoryResponse
{
    /** @var TaskHistoryEntry[] */
    public array $entries;

    /**
     * @param TaskHistoryEntry[] $entries
     */
    public function __construct(array $entries)
    {
        $this->entries = $entries;
    }
}
// {
//   "entries": [
//     { "action": "create", "changes": null, "performedById": 1, "performedByName": "Alice", "createdAt": "2025-10-11T08:00:00Z" },
//     { "action": "assign", "changes": {"assignee": {"old": null, "new": 5}}, "performedById": 1, "performedByName": "Alice", "createdAt": "2025-10-11T09:00:00Z" },
//     { "action": "update", "changes": {"title": {"old": "Old", "new": "New"}, "description": {"old": "Desc1", "new": "Desc2"}}, "performedById": 2, "performedByName": "Bob", "createdAt": "2025-10-12T10:00:00Z" },
//     { "action": "change_status", "changes": {"status": {"old": "open", "new": "done"}}, "performedById": 2, "performedByName": "Bob", "createdAt": "2025-10-12T11:00:00Z" }
//   ]
// }