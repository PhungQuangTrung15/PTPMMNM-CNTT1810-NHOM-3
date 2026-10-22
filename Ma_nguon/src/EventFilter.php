<?php
declare(strict_types=1);

function filterEvents(array $events, string $query, string $category = '', bool $availableOnly = false): array
{
    $result = [];
    foreach ($events as $event) {
        // 1. Kiểm tra từ khóa tiêu đề (không phân biệt hoa/thường)
        $matchQuery = $query === '' || stripos($event['title'], $query) !== false;
        
        // 2. Kiểm tra danh mục
        $matchCategory = $category === '' || (isset($event['category']) && $event['category'] === $category);
        
        // 3. Kiểm tra còn chỗ (capacity lớn hơn registered)
        $isAvailable = ($event['capacity'] - $event['registered']) > 0;
        $matchAvailable = !$availableOnly || $isAvailable;

        // Kết hợp theo toán học AND
        if ($matchQuery && $matchCategory && $matchAvailable) {
            $result[] = $event;
        }
    }
    return $result;
}