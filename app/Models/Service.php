<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services'; // Tên bảng

    protected $fillable = [
        'name',
        'description',
        'price',
        'type',
    ];

    // Định nghĩa hằng số cho type
    public const TYPE_CARE = 1; // Chăm sóc
    public const TYPE_EXAMINATION = 2; // Khám
    public const TYPE_CONSIGNMENT = 3; // Kí gửi

    /**
     * Kiểm tra dịch vụ có phải là dịch vụ chăm sóc không.
     */
    public function isCare(): bool
    {
        return $this->type === self::TYPE_CARE;
    }

    /**
     * Kiểm tra dịch vụ có phải là dịch vụ khám không.
     */
    public function isExamination(): bool
    {
        return $this->type === self::TYPE_EXAMINATION;
    }

    /**
     * Kiểm tra dịch vụ có phải là dịch vụ kí gửi không.
     */
    public function isConsignment(): bool
    {
        return $this->type === self::TYPE_CONSIGNMENT;
    }
}
