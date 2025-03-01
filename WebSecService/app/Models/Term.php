namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function getTotalCreditHoursAttribute()
    {
        return $this->grades->sum('credit_hours');
    }

    public function getGpaAttribute()
    {
        $total_points = $this->grades->sum(function ($grade) {
            return $grade->grade * $grade->credit_hours;
        });
        return $total_points / $this->total_credit_hours;
    }
}
