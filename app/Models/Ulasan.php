<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'ulasan';

    // Primary key
    protected $primaryKey = 'id_ulasan';

    // Atribut yang dapat diisi
    protected $fillable = [
        'user_id',
        'id_Produk',
        'id_pesanan',
        'rating',
        'komentar',
        'balasan_admin',
        'tanggal_balasan',
        'admin_reply_by',
        'is_verified_purchase',
        'foto_review',
        'status_review',
        'is_helpful',
        'helpful_count'
    ];

    protected $casts = [
        'tanggal_balasan' => 'datetime',
        'is_verified_purchase' => 'boolean',
        'foto_review' => 'array',
        'is_helpful' => 'boolean',
        'helpful_count' => 'integer'
    ];

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'id_Produk', 'id_Produk');
    }

    // Relasi ke pesanan (direct relationship)
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'id_pesanan', 'id_pesanan');
    }

    // Enhanced relationships
    public function adminReplier()
    {
        return $this->belongsTo(User::class, 'admin_reply_by');
    }

    public function interactions()
    {
        return $this->hasMany(ReviewInteraction::class, 'ulasan_id', 'id_ulasan');
    }

    public function helpfulInteractions()
    {
        return $this->hasMany(ReviewInteraction::class, 'ulasan_id', 'id_ulasan')
                   ->where('interaction_type', 'helpful');
    }

    // Review status methods
    public function getStatusBadgeAttribute(): string
    {
        // All reviews are now automatically approved
        return '<span class="badge bg-success">Dipublikasi</span>';
    }

    public function getRatingStarsAttribute(): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<i class="fas fa-star text-warning"></i>';
            } else {
                $stars .= '<i class="far fa-star text-muted"></i>';
            }
        }
        return $stars;
    }

    // Admin reply methods
    public function addAdminReply(string $reply): void
    {
        $this->update([
            'balasan_admin' => $reply,
            'tanggal_balasan' => now(),
            'admin_reply_by' => \Illuminate\Support\Facades\Auth::id()
        ]);
    }

    public function hasAdminReply(): bool
    {
        return !empty($this->balasan_admin);
    }

    // Interaction methods
    public function toggleInteraction(int $userId, string $type): void
    {
        $existing = $this->interactions()
                        ->where('user_id', $userId)
                        ->first();

        if ($existing) {
            if ($existing->interaction_type === $type) {
                // Remove interaction if same type
                $existing->delete();
                $this->updateHelpfulCount();
                return;
            } else {
                // Update interaction type
                $existing->update(['interaction_type' => $type]);
            }
        } else {
            // Create new interaction
            $this->interactions()->create([
                'user_id' => $userId,
                'interaction_type' => $type
            ]);
        }

        $this->updateHelpfulCount();
    }

    public function updateHelpfulCount(): void
    {
        $helpfulCount = $this->helpfulInteractions()->count();
        $this->update(['helpful_count' => $helpfulCount]);
    }

    public function getUserInteraction(int $userId): ?ReviewInteraction
    {
        return $this->interactions()
                   ->where('user_id', $userId)
                   ->first();
    }

    // Verification methods
    public function verifyPurchase(): void
    {
        $this->update(['is_verified_purchase' => true]);
    }

    public function getVerificationBadgeAttribute(): string
    {
        if ($this->is_verified_purchase) {
            return '<span class="badge bg-primary text-white" title="Pembelian Terverifikasi">
                        <i class="fas fa-check-circle"></i> Verified
                    </span>';
        }
        return '';
    }

    // Photo methods
    public function hasPhotos(): bool
    {
        return !empty($this->foto_review) &&
               (is_array($this->foto_review) || is_string($this->foto_review));
    }

    public function getPhotosAttribute(): array
    {
        if (empty($this->foto_review)) {
            return [];
        }

        // Handle different data formats
        $photos = $this->foto_review;

        // If it's a string, try to decode it as JSON
        if (is_string($photos)) {
            $decoded = json_decode($photos, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $photos = $decoded;
            } else {
                // If not JSON, treat as single photo
                $photos = [$photos];
            }
        }

        // Ensure it's an array
        if (!is_array($photos)) {
            return [];
        }

        // Filter out empty/null values and ensure files exist
        return array_filter($photos, function($photo) {
            if (empty($photo) || $photo === null || trim($photo) === '' || $photo === 'null') {
                return false;
            }

            // Check if file exists in different possible locations
            $possiblePaths = [];

            if (str_starts_with($photo, 'uploads/')) {
                // New format: uploads/reviews/filename
                $possiblePaths[] = public_path($photo);
            } else if (str_starts_with($photo, 'reviews/')) {
                // Old format: reviews/filename
                $possiblePaths[] = public_path('uploads/' . $photo);
                $possiblePaths[] = storage_path('app/public/' . $photo);
            } else {
                // Other formats
                $possiblePaths[] = storage_path('app/public/' . $photo);
                $possiblePaths[] = public_path('uploads/' . $photo);
            }

            // Check if any of the possible paths exist
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    return true;
                }
            }

            return false;
        });
    }

    public function getPhotoUrlsAttribute(): array
    {
        return array_map(function($photo) {
            // Handle different path formats and check file existence
            $possibleUrls = [];

            if (str_starts_with($photo, 'uploads/')) {
                // New format: uploads/reviews/filename
                $possibleUrls[] = asset($photo);
            } else if (str_starts_with($photo, 'reviews/')) {
                // Old format: reviews/filename - convert to new format
                $possibleUrls[] = asset('uploads/' . $photo);
                $possibleUrls[] = asset('storage/' . $photo);
            } else {
                // Other formats
                $possibleUrls[] = asset('storage/' . $photo);
                $possibleUrls[] = asset('uploads/' . $photo);
            }

            // Return the first URL that corresponds to an existing file
            foreach ($possibleUrls as $url) {
                $filePath = public_path(str_replace(url('/'), '', $url));
                if (file_exists($filePath)) {
                    return $url;
                }

                // Also check storage path for legacy files
                $storagePath = storage_path('app/public/' . $photo);
                if (file_exists($storagePath)) {
                    return asset('storage/' . $photo);
                }
            }

            // If no file exists, return the first URL (which should be the correct new format)
            return $possibleUrls[0] ?? asset('uploads/' . $photo);
        }, $this->photos);
    }
}
