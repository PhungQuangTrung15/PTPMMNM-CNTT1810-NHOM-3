<?php
declare(strict_types=1);

final class RegistrationPolicy 
{
    public function decide(int $capacity, int $registered, bool $open, bool $waitingEnabled): string 
    {
        // 1. Kiểm tra dữ liệu không hợp lệ hoặc số lượng đăng ký vượt quá dung lượng (corrupt-over-count)
        if ($capacity <= 0 || $registered < 0 || $registered > $capacity) {
            return 'rejected';
        }

        // 2. Kiểm tra nếu hệ thống đóng
        if (!$open) {
            return 'rejected';
        }

        // 3. Còn chỗ trống
        if ($registered < $capacity) {
            return 'confirmed';
        }

        // 4. Đã đầy (registered === capacity) -> cho phép chờ hay từ chối
        return $waitingEnabled ? 'waiting' : 'rejected';
    }
}