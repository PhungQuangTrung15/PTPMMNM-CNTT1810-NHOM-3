<?php
declare(strict_types=1);

function canComplete(array $session, int $eventId, int $now, int $ttl): bool {
    // 1. Kiểm tra thời gian sống (TTL) phải lớn hơn 0
    if ($ttl <= 0) {
        return false;
    }

    // 2. Kiểm tra xem phiên có tồn tại bản nháp hay không
    if (!isset($session['draft']) || !is_array($session['draft'])) {
        return false;
    }

    $draft = $session['draft'];

    // 3. Kiểm tra cấu trúc bản nháp phải có 'data' và 'created_at'
    if (!isset($draft['data'], $draft['created_at']) || !is_array($draft['data'])) {
        return false;
    }

    $createdAt = (int)$draft['created_at'];

    // 4. Thời điểm tạo không được nằm ở tương lai
    if ($createdAt > $now) {
        return false;
    }

    // 5. Kiểm tra thời hạn hết hạn: tuổi bản nháp >= ttl thì coi như hết hạn (lưu ý mốc đúng ngưỡng hết hạn trả về false)
    if ($now - $createdAt >= $ttl) {
        return false;
    }

    // 6. Kiểm tra sự kiện (event_id) phải khớp với bản nháp
    if (!isset($draft['data']['event_id']) || (int)$draft['data']['event_id'] !== $eventId) {
        return false;
    }

    return true;
}